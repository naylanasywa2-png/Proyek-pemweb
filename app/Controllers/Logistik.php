<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Logistik extends BaseController
{
   public function tesOngkir()
    {
        // 1. WAJIB: Load helper url agar base_url() di View bisa bekerja
        helper(['url', 'form']);

        // 2. Pastikan method diubah ke huruf kecil semua agar if-statement berfungsi
        $method = strtolower($this->request->getMethod());

        if ($method === 'post') {
            $tujuan = $this->request->getPost('tujuan');
            $berat  = $this->request->getPost('berat');
        } else {
            // Default saat halaman baru dibuka
            $tujuan = "151";
            $berat = 1000;
        }

        $hasilJson = $this->hitungOngkir($tujuan, $berat);
        $data['results'] = json_decode($hasilJson, true);

        // Kirim status ke view untuk memastikan data terproses
        $data['is_post'] = ($method === 'post');

        return view('cek_ongkir', $data);
    }

    private function hitungOngkir($tujuan, $berat)
    {
        // Masukkan API Key BinderByte kamu di sini
        $apiKey = "b4e6ecf08961a23b57ea371a26dfd66f9f9e63a5db69d552f4f5ae86f5fe7199";

        $curl = curl_init();

        $url = "https://api.binderbyte.com/v1/cost?api_key=$apiKey&courier=jne&origin=surabaya&destination=$tujuan&weight=$berat";

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            // 3. WAJIB DI XAMPP: Bypass SSL agar request API tidak ditolak oleh localhost
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        return ($err) ? "Error" : $response;
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
        $biaya_ongkir = $this->request->getPost('biaya');

        $data = [
            'id_user'        => 1, // Sementara manual
            'id_desain'      => $id_desain_terpilih ?? 1, // Gunakan ID desain terpilih
            'id_vendor'      => 1,
            'jumlah'         => 1,
            'ongkir'         => $biaya_ongkir,
            'total_bayar'    => 50000 + $biaya_ongkir,
            'status_pesanan' => 'pending',
            'created_at'     => date('Y-m-d H:i:s')
        ];

        if ($db->table('orders')->insert($data)) {
            session()->setFlashdata('sukses', 'Yey! Pesanan kamu berhasil dibuat ✨');
            return redirect()->to(base_url('logistik/daftar-pesanan'));
        } else {
            return "Gagal menyimpan data.";
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