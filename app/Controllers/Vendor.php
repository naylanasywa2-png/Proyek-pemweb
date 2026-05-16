<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Vendor extends BaseController
{
    // 1. Halaman Dashboard (Memuat Data Riil Database)
    public function index()
    {
        $db = \Config\Database::connect();

        // A. Hitung Pesanan Baru (Status pesanan yang baru masuk / pending)
        $pesananBaru = $db->table('orders')
                          ->where('status_pesanan', 'pending')
                          ->countAllResults();

        // B. Hitung Total Penghasilan Riil (Jumlah uang dari pesanan yang berstatus 'selesai')
        $penghasilanData = $db->table('orders')
                              ->selectSum('total_bayar') // Menghitung total dari kolom total_bayar
                              ->where('status_pesanan', 'selesai')
                              ->get()
                              ->getRowArray();
        $totalPenghasilan = $penghasilanData['total_bayar'] ?? 0;

        // C. Hitung Pesanan yang Sedang Diproses
        $sedangDiproses = $db->table('orders')
                             ->where('status_pesanan', 'diproses')
                             ->countAllResults();

        // D. Hitung Menunggu Konfirmasi (Pesanan masuk yang belum diubah statusnya oleh vendor)
        $menungguKonfirmasi = $db->table('orders')
                                 ->where('status_pesanan', 'pending')
                                 ->countAllResults();

        // Menyusun semua data riil ke dalam array untuk dikirim ke view dashboard
        $data = [
            'pesanan_baru'        => $pesananBaru,
            'total_penghasilan'   => number_format($totalPenghasilan, 0, ',', '.'), // Format rupiah tanpa desimal
            'sedang_diproses'     => $sedangDiproses,
            'menunggu_konfirmasi' => $menungguKonfirmasi
        ];

        return view('vendor/dashboard', $data);
    }

    public function dashboard()
    {
        return $this->index();
    }

    // 2. Halaman Daftar Pesanan (Read)
    public function pesanan()
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('orders');
        $builder->select('orders.*, users.email as email_pembeli');
        $builder->join('users', 'users.id_user = orders.id_user');
        $builder->orderBy('orders.id_order', 'DESC');
        
        $data['orders'] = $builder->get()->getResultArray();

        return view('vendor/pesanan', $data);
    }

    // 3. Fungsi Update Status Pesanan ke "Diproses"
    public function updateStatus($id)
    {
        $db = \Config\Database::connect();
        
        $db->table('orders')
           ->where('id_order', $id)
           ->update(['status_pesanan' => 'diproses']);

        return redirect()->to(base_url('vendor/pesanan'))->with('message', 'Pesanan berhasil dikonfirmasi!');
    }

    // 4. Menangani Proses Unggah File Album dari Modal Bootstrap
    public function uploadDesain()
    {
        $id_order = $this->request->getPost('id_order');
        $fileDesain = $this->request->getFile('file_desain');

        // Validasi apakah berkas yang diunggah valid dan belum dipindahkan
        if ($fileDesain && $fileDesain->isValid() && !$fileDesain->hasMoved()) {
            
            // Membuat nama acak yang unik dan aman untuk server
            $namaFileBaru = $fileDesain->getRandomName();

            // Pindahkan berkas langsung ke folder target: public/uploads/results/
            $fileDesain->move(FCPATH . 'uploads/results', $namaFileBaru);

            // Perbarui kolom nama file dan ubah status pesanan menjadi selesai di database
            $db = \Config\Database::connect();
            $db->table('orders')->where('id_order', $id_order)->update([
                'file_desain'    => $namaFileBaru,
                'status_pesanan' => 'selesai'
            ]);

            return redirect()->to(base_url('vendor/pesanan'))->with('message', 'File desain album berhasil diunggah dan dikirim!');
        }

        // Jika proses gagal atau berkas tidak valid
        return redirect()->to(base_url('vendor/pesanan'))->with('message', 'Gagal mengunggah berkas. Silakan coba lagi.');
    }

    // 5. Halaman Portofolio
    public function portfolio()
    {
        $db = \Config\Database::connect();
        // Mengambil data portfolio dari database untuk ditampilkan di halaman
        $data['portfolios'] = $db->table('portfolio')->get()->getResultArray();

        return view('vendor/portfolio', $data);
    }

    // 6. Fungsi Simpan Desain Baru Ke Portofolio
    public function savePortfolio()
    {
        $db = \Config\Database::connect();

        // Ambil file gambar yang diupload
        $fileGambar = $this->request->getFile('gambar');

        // Olah upload gambar
        if ($fileGambar->isValid() && !$fileGambar->hasMoved()) {
            // Beri nama acak agar tidak ada nama file yang sama di server
            $namaGambar = $fileGambar->getRandomName();
            // Pindahkan ke folder public/uploads/portfolio
            $fileGambar->move('uploads/portfolio/', $namaGambar);
        } else {
            $namaGambar = 'default.jpg'; // Jika gagal upload gunakan gambar default
        }

        // Siapkan data untuk dimasukkan ke database
        $data = [
            'nama_tema' => $this->request->getPost('nama_tema'),
            'harga'     => $this->request->getPost('harga'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'gambar'    => $namaGambar
        ];

        // Proses Insert ke tabel 'portfolio'
        $db->table('portfolio')->insert($data);

        // Kembali ke halaman portfolio dengan notifikasi sukses
        return redirect()->to(base_url('vendor/portfolio'))->with('message', 'Desain baru berhasil ditambahkan ke portfolio!');
    }
}