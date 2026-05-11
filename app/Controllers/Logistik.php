<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\OngkirService;

class Logistik extends BaseController
{
    // Daftar kota tujuan (sementara hardcode — nanti bisa diisi dari API destination/search)
    // ID ini perlu disesuaikan dengan Komerce Destination ID setelah API aktif
    private array $kotaTujuan = [
        ['id' => 4982,  'name' => 'Jakarta Barat - Cengkareng'],
        ['id' => 5007,  'name' => 'Jakarta Pusat - Gambir'],
        ['id' => 5046,  'name' => 'Jakarta Selatan - Kebayoran Baru'],
        ['id' => 5095,  'name' => 'Jakarta Timur - Jatinegara'],
        ['id' => 5132,  'name' => 'Jakarta Utara - Kelapa Gading'],
        ['id' => 12043, 'name' => 'Surabaya - Genteng'],
        ['id' => 6428,  'name' => 'Bandung - Coblong'],
        ['id' => 1542,  'name' => 'Denpasar - Denpasar Selatan'],
        ['id' => 11113, 'name' => 'Yogyakarta - Gondokusuman'],
        ['id' => 13350, 'name' => 'Medan - Medan Kota'],
    ];

    // Kota asal: Surabaya - Genteng (sesuaikan jika berbeda)
    private int $asalId = 12043;

    public function tesOngkir()
    {
        helper(['url', 'form']);

        $method  = strtolower($this->request->getMethod());
        $service = new OngkirService();

        if ($method === 'post') {
            $tujuan  = (int) $this->request->getPost('tujuan');
            $berat   = (float) $this->request->getPost('berat'); // dalam gram dari form
        } else {
            $tujuan  = 4982; // Default: Jakarta Barat - Cengkareng
            $berat   = 1000;  // 1000 gram
        }

        // Konversi gram ke kg (Komerce pakai kg)
        $beratKg = $berat / 1000;

        $result = $service->cekOngkir(
            shipperId:  $this->asalId,
            receiverId: $tujuan,
            weight:     $beratKg,
            itemValue:  50000,
            cod:        'no'
        );

        return view('cek_ongkir', [
            'result'     => $result,
            'kota_list'  => $this->kotaTujuan,
            'tujuan'     => $tujuan,
            'berat'      => (int) $berat,
            'is_post'    => ($method === 'post'),
        ]);
    }
   
    public function testEmail()
    {
        $email = \Config\Services::email();
        $tujuan = "punyakega3@gmail.com";

        $email->setTo($tujuan);
        $email->setFrom('punyakega3@gmail.com', 'Sistem Digital Memories');
        $email->setSubject('Tes Notifikasi Sistem');
        $email->setMessage('Halo Cindy! Sistem email kamu sudah aman.');

        if ($email->send()) {
            echo "<h1> hore! Email berhasil dikirim </h1>";
        } else {
            echo "<pre>" . $email->printDebugger() . "</pre>";
        }
    }

    public function testSimpanOrder()
    {
        try {
            $db = \Config\Database::connect();
            $builder = $db->table('orders');

            $data = [
                'id_user'        => 1,
                'id_desain'      => 1,
                'id_vendor'      => 1,
                'jumlah'         => 1,
                'total_bayar'    => 55000.00,
                'ongkir'         => 19000.00,
                'status_pesanan' => 'pending',
                'created_at'     => date('Y-m-d H:i:s')
            ];

            if ($builder->insert($data)) {
                return "<h1>✅ SUKSES!</h1> Data sudah masuk ke tabel orders.";
            } else {
                return "❌ Gagal: " . json_encode($db->error());
            }
        } catch (\Throwable $e) {
            return "<h1>🚨 KETEMU ERRORNYA:</h1>" .
                "<p><b>Pesan:</b> " . $e->getMessage() . "</p>" .
                "<p><b>Di file:</b> " . $e->getFile() . "</p>" .
                "<p><b>Baris ke:</b> " . $e->getLine() . "</p>";
        }
    }

    public function simpanPesanan()
    {
        $db = \Config\Database::connect();

        // Menangkap data dari form (termasuk id_desain dari temanmu)
        $id_desain_terpilih = $this->request->getPost('id_desain');
        $biaya_ongkir = (int) $this->request->getPost('biaya');
        $kurir        = $this->request->getPost('kurir')   ?? '-';
        $layanan      = $this->request->getPost('layanan') ?? '-';

        $data = [
            'id_user'        => 1, // Sementara manual — nanti ganti dengan session user
            'id_desain'      => $id_desain_terpilih ?? 1,
            'id_vendor'      => 1,
            'jumlah'         => 1,
            'ongkir'         => $biaya_ongkir,
            'total_bayar'    => 50000 + $biaya_ongkir,
            'status_pesanan' => 'pending',
            'created_at'     => date('Y-m-d H:i:s')
        ];

        if ($db->table('orders')->insert($data)) {
            session()->setFlashdata('sukses', "✅ Pesanan berhasil! Kurir: {$kurir} ({$layanan}), Ongkir: Rp " . number_format($biaya_ongkir, 0, ',', '.'));
            return redirect()->to(base_url('logistik/tesongkir'));
        } else {
            session()->setFlashdata('error', 'Gagal menyimpan pesanan. Silakan coba lagi.');
            return redirect()->to(base_url('logistik/tesongkir'));
        }
    }

    public function daftarPesanan()
    {
        $db = \Config\Database::connect();
        // Mengambil semua data dari tabel orders, diurutkan dari yang terbaru
        $query = $db->table('orders')->orderBy('created_at', 'DESC')->get();

        $data['semua_pesanan'] = $query->getResultArray();

        return view('daftar_pesanan', $data);
    }

    public function hapusPesanan($id)
    {
        $db = \Config\Database::connect();

        // Melakukan penghapusan berdasarkan id_order
        $hapus = $db->table('orders')->where('id_order', $id)->delete();

        if ($hapus) {
            session()->setFlashdata('sukses', 'Pesanan berhasil dihapus! 🗑️');
        } else {
            session()->setFlashdata('error', 'Gagal menghapus pesanan.');
        }

        return redirect()->to(base_url('logistik/daftar-pesanan'));
    }
}