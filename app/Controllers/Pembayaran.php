<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\TelegramService;

/**
 * Controller Pembayaran
 *
 * Menangani seluruh alur pembayaran:
 * 1. User upload bukti transfer / QRIS
 * 2. Admin melihat daftar pembayaran menunggu konfirmasi
 * 3. Admin konfirmasi atau tolak pembayaran
 * 4. Sistem otomatis kirim notifikasi Telegram ke vendor
 *
 * Route yang perlu ditambahkan di app/Config/Routes.php:
 *
 *   // Dalam group auth (user):
 *   $routes->get('pembayaran/upload/(:num)',   'Pembayaran::formUpload/$1');
 *   $routes->post('pembayaran/upload/(:num)',  'Pembayaran::prosesUpload/$1');
 *   $routes->get('pembayaran/status/(:num)',   'Pembayaran::statusPembayaran/$1');
 *
 *   // Dalam group auth admin:
 *   $routes->get('admin/pembayaran',                      'Pembayaran::daftarAdmin');
 *   $routes->post('admin/pembayaran/konfirmasi/(:num)',   'Pembayaran::konfirmasi/$1');
 *   $routes->post('admin/pembayaran/tolak/(:num)',        'Pembayaran::tolak/$1');
 */
class Pembayaran extends BaseController
{
    // Folder upload (relative dari public/)
    private string $uploadFolder = 'uploads/bukti_pembayaran/';

    // =========================================================
    // USER: Halaman form upload bukti pembayaran
    // =========================================================
    public function formUpload(int $idOrder)
    {
        helper(['url', 'form']);

        $db    = \Config\Database::connect();
        $order = $db->table('orders')->where('id_order', $idOrder)->get()->getRowArray();

        if (! $order) {
            session()->setFlashdata('error', 'Pesanan tidak ditemukan.');
            return redirect()->to(base_url('logistik/daftar-pesanan'));
        }

        // Cek apakah sudah ada pembayaran sebelumnya
        $existingPayment = $db->table('payments')
            ->where('id_order', $idOrder)
            ->where('status !=', 'ditolak')
            ->get()->getRowArray();

        return view('pembayaran/upload_bukti', [
            'order'           => $order,
            'existing_payment' => $existingPayment,
        ]);
    }

    // =========================================================
    // USER: Proses upload bukti pembayaran
    // =========================================================
    public function prosesUpload(int $idOrder)
    {
        helper(['url']);

        $db    = \Config\Database::connect();
        $order = $db->table('orders')->where('id_order', $idOrder)->get()->getRowArray();

        if (! $order) {
            session()->setFlashdata('error', 'Pesanan tidak ditemukan.');
            return redirect()->to(base_url('logistik/daftar-pesanan'));
        }

        // Validasi: hanya pesanan pending yang bisa upload
        if ($order['status_pesanan'] !== 'pending') {
            session()->setFlashdata('error', 'Pesanan ini sudah tidak bisa diupload bukti pembayarannya.');
            return redirect()->to(base_url('logistik/daftar-pesanan'));
        }

        $metodeBayar  = $this->request->getPost('metode_bayar') ?? 'transfer_bank';
        $catatanUser  = trim($this->request->getPost('catatan') ?? '');
        $fileBukti    = $this->request->getFile('bukti_pembayaran');

        // Validasi file
        if (! $fileBukti || ! $fileBukti->isValid()) {
            session()->setFlashdata('error', 'File bukti pembayaran wajib diunggah.');
            return redirect()->to(base_url("pembayaran/upload/{$idOrder}"));
        }

        // Validasi tipe file
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'application/pdf'];
        if (! in_array($fileBukti->getMimeType(), $allowedTypes)) {
            session()->setFlashdata('error', 'Format file tidak didukung. Gunakan JPG, PNG, WEBP, atau PDF.');
            return redirect()->to(base_url("pembayaran/upload/{$idOrder}"));
        }

        // Validasi ukuran file (maks 5MB)
        if ($fileBukti->getSize() > 5 * 1024 * 1024) {
            session()->setFlashdata('error', 'Ukuran file maksimal 5MB.');
            return redirect()->to(base_url("pembayaran/upload/{$idOrder}"));
        }

        // Buat folder jika belum ada
        $uploadPath = FCPATH . $this->uploadFolder;
        if (! is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Generate nama file unik
        $namaFile = 'bukti_ORD' . $idOrder . '_' . time() . '.' . $fileBukti->getExtension();

        // Pindahkan file
        if (! $fileBukti->move($uploadPath, $namaFile)) {
            session()->setFlashdata('error', 'Gagal mengunggah file. Coba lagi.');
            return redirect()->to(base_url("pembayaran/upload/{$idOrder}"));
        }

        try {
            // Hapus pembayaran yang sebelumnya ditolak (jika ada)
            $db->table('payments')
               ->where('id_order', $idOrder)
               ->where('status', 'ditolak')
               ->delete();

            // Simpan data pembayaran
            $db->table('payments')->insert([
                'id_order'     => $idOrder,
                'metode_bayar' => $metodeBayar,
                'nominal'      => $order['total_bayar'],
                'file_bukti'   => $this->uploadFolder . $namaFile,
                'catatan_user' => mb_substr(strip_tags($catatanUser), 0, 500),
                'status'       => 'menunggu',
                'created_at'   => date('Y-m-d H:i:s'),
            ]);

            // Update status pesanan menjadi 'diproses' (menunggu konfirmasi admin)
            $db->table('orders')->where('id_order', $idOrder)->update([
                'status_pesanan' => 'diproses',
            ]);

            session()->setFlashdata('sukses', "Bukti pembayaran #ORD-{$idOrder} berhasil diunggah. Menunggu konfirmasi admin.");
            return redirect()->to(base_url('logistik/daftar-pesanan'));

        } catch (\Throwable $e) {
            // Hapus file yang sudah terupload jika database gagal
            if (file_exists($uploadPath . $namaFile)) {
                unlink($uploadPath . $namaFile);
            }
            log_message('error', '[Pembayaran::prosesUpload] ' . $e->getMessage());
            session()->setFlashdata('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
            return redirect()->to(base_url("pembayaran/upload/{$idOrder}"));
        }
    }

    // =========================================================
    // ADMIN: Daftar pembayaran menunggu konfirmasi
    // =========================================================
    public function daftarAdmin()
    {
        $db = \Config\Database::connect();

        // Join payments dengan orders untuk info lengkap
        $semua = $db->table('payments p')
            ->select('p.*, o.kurir, o.layanan, o.kota_tujuan, o.total_bayar as total_order, o.status_pesanan')
            ->join('orders o', 'o.id_order = p.id_order', 'left')
            ->orderBy('p.created_at', 'DESC')
            ->get()
            ->getResultArray();

        // Pisahkan berdasarkan status
        $menunggu     = array_filter($semua, fn($p) => $p['status'] === 'menunggu');
        $dikonfirmasi = array_filter($semua, fn($p) => $p['status'] === 'dikonfirmasi');
        $ditolak      = array_filter($semua, fn($p) => $p['status'] === 'ditolak');

        return view('pembayaran/admin_daftar', [
            'menunggu'     => array_values($menunggu),
            'dikonfirmasi' => array_values($dikonfirmasi),
            'ditolak'      => array_values($ditolak),
        ]);
    }

    // =========================================================
    // ADMIN: Konfirmasi pembayaran
    // =========================================================
    public function konfirmasi(int $idPayment)
    {
        if (strtolower($this->request->getMethod()) !== 'post') {
            return redirect()->to(base_url('admin/pembayaran'));
        }

        $db      = \Config\Database::connect();
        $payment = $db->table('payments')->where('id_payment', $idPayment)->get()->getRowArray();

        if (! $payment) {
            session()->setFlashdata('error', 'Data pembayaran tidak ditemukan.');
            return redirect()->to(base_url('admin/pembayaran'));
        }

        if ($payment['status'] !== 'menunggu') {
            session()->setFlashdata('error', 'Pembayaran ini sudah dikonfirmasi atau ditolak sebelumnya.');
            return redirect()->to(base_url('admin/pembayaran'));
        }

        $catatanAdmin = trim($this->request->getPost('catatan_admin') ?? '');
        $adminId      = session()->get('user_id');
        $idOrder      = $payment['id_order'];

        try {
            // Update status pembayaran
            $db->table('payments')->where('id_payment', $idPayment)->update([
                'status'            => 'dikonfirmasi',
                'catatan_admin'     => mb_substr(strip_tags($catatanAdmin), 0, 500),
                'dikonfirmasi_oleh' => $adminId,
                'dikonfirmasi_at'   => date('Y-m-d H:i:s'),
            ]);

            // Update status pesanan menjadi selesai
            $db->table('orders')->where('id_order', $idOrder)->update([
                'status_pesanan' => 'selesai',
            ]);

            // Ambil data order lengkap
            $order = $db->table('orders')->where('id_order', $idOrder)->get()->getRowArray();

            // Kirim notifikasi Telegram ke vendor
            $this->kirimNotifikasiVendor($order, 'konfirmasi', $db);

            session()->setFlashdata('sukses', "Pembayaran #ORD-{$idOrder} berhasil dikonfirmasi. Notifikasi dikirim ke vendor.");

        } catch (\Throwable $e) {
            log_message('error', '[Pembayaran::konfirmasi] ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal mengkonfirmasi: ' . $e->getMessage());
        }

        return redirect()->to(base_url('admin/pembayaran'));
    }

    // =========================================================
    // ADMIN: Tolak pembayaran
    // =========================================================
    public function tolak(int $idPayment)
    {
        if (strtolower($this->request->getMethod()) !== 'post') {
            return redirect()->to(base_url('admin/pembayaran'));
        }

        $db      = \Config\Database::connect();
        $payment = $db->table('payments')->where('id_payment', $idPayment)->get()->getRowArray();

        if (! $payment || $payment['status'] !== 'menunggu') {
            session()->setFlashdata('error', 'Pembayaran tidak ditemukan atau sudah diproses.');
            return redirect()->to(base_url('admin/pembayaran'));
        }

        $catatanAdmin = trim($this->request->getPost('catatan_admin') ?? '');
        if (empty($catatanAdmin)) {
            session()->setFlashdata('error', 'Alasan penolakan wajib diisi.');
            return redirect()->to(base_url('admin/pembayaran'));
        }

        $idOrder = $payment['id_order'];

        try {
            $db->table('payments')->where('id_payment', $idPayment)->update([
                'status'            => 'ditolak',
                'catatan_admin'     => mb_substr(strip_tags($catatanAdmin), 0, 500),
                'dikonfirmasi_oleh' => session()->get('user_id'),
                'dikonfirmasi_at'   => date('Y-m-d H:i:s'),
            ]);

            // Kembalikan status pesanan ke pending agar user bisa upload ulang
            $db->table('orders')->where('id_order', $idOrder)->update([
                'status_pesanan' => 'pending',
            ]);

            session()->setFlashdata('sukses', "Pembayaran #ORD-{$idOrder} ditolak. User akan diminta upload ulang.");

        } catch (\Throwable $e) {
            log_message('error', '[Pembayaran::tolak] ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal menolak pembayaran: ' . $e->getMessage());
        }

        return redirect()->to(base_url('admin/pembayaran'));
    }

    // =========================================================
    // HELPER: Kirim notifikasi ke vendor via Telegram
    // =========================================================
    private function kirimNotifikasiVendor(array $order, string $tipe, $db): void
    {
        try {
            // Ambil vendor default (id_vendor dari order)
            $idVendor = $order['id_vendor'] ?? 1;
            $vendor   = $db->table('vendor')
                ->where('id_vendor', $idVendor)
                ->get()->getRowArray();

            if (! $vendor || empty($vendor['telegram_chat_id'])) {
                log_message('info', "[Pembayaran] Vendor tidak punya Telegram Chat ID, notifikasi dilewati.");
                return;
            }

            $telegram = new TelegramService();

            if ($tipe === 'konfirmasi') {
                $telegram->kirimNotifikasiPembayaranDikonfirmasi($order, $vendor['telegram_chat_id']);
            } else {
                $telegram->kirimNotifikasiPesanan($order, $vendor['telegram_chat_id']);
            }

        } catch (\Throwable $e) {
            // Notifikasi gagal tidak boleh menggagalkan proses utama
            log_message('error', '[Pembayaran::kirimNotifikasiVendor] ' . $e->getMessage());
        }
    }
}