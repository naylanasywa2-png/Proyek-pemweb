<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\OngkirService;

class Logistik extends BaseController
{
    // Daftar kota tujuan
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

    private int $asalId = 12043;

    // =========================================================
    // CEK ONGKIR - dengan validasi server-side lengkap
    // =========================================================
    public function tesOngkir()
    {
        helper(['url', 'form']);

        $method  = strtolower($this->request->getMethod());
        $service = new OngkirService();

        // Default values
        $tujuan   = 4982;
        $berat    = 1000;
        $result   = null;
        $errors   = [];
        $filterKurir = '';

        if ($method === 'post') {
            // ---- Validasi Server-Side ----
            $tujuanRaw = $this->request->getPost('tujuan');
            $beratRaw  = $this->request->getPost('berat');
            $filterKurir = strtoupper(trim($this->request->getPost('filter_kurir') ?? ''));

            // Validasi tujuan
            $tujuan = (int) $tujuanRaw;
            $kotaIds = array_column($this->kotaTujuan, 'id');
            if (!in_array($tujuan, $kotaIds, true)) {
                $errors['tujuan'] = 'Kota tujuan tidak valid.';
                $tujuan = 4982; // reset ke default
            }

            // Validasi berat
            $berat = (int) $beratRaw;
            if ($berat < 100) {
                $errors['berat'] = 'Berat minimal 100 gram.';
                $berat = 100;
            }
            if ($berat > 30000) {
                $errors['berat'] = 'Berat maksimal 30.000 gram (30 kg).';
                $berat = 30000;
            }

            // Jika tidak ada error, panggil API
            if (empty($errors)) {
                $beratKg = $berat / 1000;
                $result = $service->cekOngkir(
                    shipperId:  $this->asalId,
                    receiverId: $tujuan,
                    weight:     $beratKg,
                    itemValue:  50000,
                    cod:        'no'
                );

                // Filter kurir jika dipilih
                if (!empty($filterKurir) && $filterKurir !== 'SEMUA' && isset($result['data'])) {
                    $result['data'] = array_values(array_filter(
                        $result['data'],
                        fn($item) => strtoupper($item['courier']) === $filterKurir
                    ));
                }

                // Normalisasi nilai ongkir jika API mengembalikan jumlah terlalu besar
                if (isset($result['data']) && is_array($result['data'])) {
                    foreach ($result['data'] as &$item) {
                        if (isset($item['price'])) {
                            $item['price'] = $this->normalizeShippingRate($item['price']);
                        }
                        if (isset($item['price_net'])) {
                            $item['price_net'] = $this->normalizeShippingRate($item['price_net']);
                        }
                    }
                    unset($item);
                }
            }
        }

        // Ambil nama kota tujuan untuk ditampilkan
        $kotaTujuanNama = '';
        foreach ($this->kotaTujuan as $k) {
            if ($k['id'] === $tujuan) {
                $kotaTujuanNama = $k['name'];
                break;
            }
        }

        return view('cek_ongkir', [
            'result'          => $result,
            'kota_list'       => $this->kotaTujuan,
            'tujuan'          => $tujuan,
            'tujuan_nama'     => $kotaTujuanNama,
            'berat'           => $berat,
            'is_post'         => ($method === 'post'),
            'errors'          => $errors,
            'filter_kurir'    => $filterKurir,
        ]);
    }

    // =========================================================
    // DETAIL PESANAN - halaman konfirmasi sebelum simpan
    // =========================================================
    public function detailPesanan()
    {
        helper(['url', 'form']);

        if (strtolower($this->request->getMethod()) !== 'post') {
            return redirect()->to(base_url('logistik/tesongkir'));
        }

        // Ambil data dari form cek ongkir
        $biaya      = (int) $this->request->getPost('biaya');
        $biayaNet   = (int) $this->request->getPost('biaya_net');
        $kurir      =        $this->request->getPost('kurir')       ?? '';
        $layanan    =        $this->request->getPost('layanan')     ?? '';
        $kotaTujuan = (int) $this->request->getPost('kota_tujuan');
        $berat      = (int) $this->request->getPost('berat');
        $etd        =        $this->request->getPost('etd')         ?? '-';
        $deskripsi  =        $this->request->getPost('deskripsi')   ?? '';
        $isCod      =        $this->request->getPost('is_cod') === '1';

        // Validasi server-side
        if ($biaya <= 0 || empty($kurir) || empty($layanan)) {
            session()->setFlashdata('error', 'Data pengiriman tidak valid. Silakan pilih ulang.');
            return redirect()->to(base_url('logistik/tesongkir'));
        }

        // Ambil nama kota tujuan
        $kotaTujuanNama = '';
        foreach ($this->kotaTujuan as $k) {
            if ($k['id'] === $kotaTujuan) {
                $kotaTujuanNama = $k['name'];
                break;
            }
        }

        // Hardcode harga desain untuk sementara
        $hargaDesain = 50000;
        $totalBayar  = $hargaDesain + $biaya;

        return view('detail_pesanan', [
            'biaya'          => $biaya,
            'biaya_net'      => $biayaNet,
            'kurir'          => strtoupper($kurir),
            'layanan'        => $layanan,
            'deskripsi'      => $deskripsi,
            'etd'            => $etd,
            'is_cod'         => $isCod,
            'kota_tujuan_id' => $kotaTujuan,
            'kota_tujuan'    => $kotaTujuanNama,
            'berat'          => $berat,
            'harga_desain'   => $hargaDesain,
            'total_bayar'    => $totalBayar,
        ]);
    }

    // =========================================================
    // SIMPAN PESANAN - dari halaman konfirmasi (detail)
    // =========================================================
    public function simpanPesanan()
    {
        helper(['url']);

        if (strtolower($this->request->getMethod()) !== 'post') {
            return redirect()->to(base_url('logistik/tesongkir'));
        }

        // Ambil data dari form detail_pesanan
        $biaya          = (int)   $this->request->getPost('biaya');
        $kurir          = trim(    $this->request->getPost('kurir')            ?? '');
        $layanan        = trim(    $this->request->getPost('layanan')          ?? '');
        $kotaTujuanNama = trim(    $this->request->getPost('kota_tujuan_nama') ?? '');
        $berat          = (int)   $this->request->getPost('berat_gram');
        $catatan        = trim(    $this->request->getPost('catatan')          ?? '');

        // Validasi wajib
        if ($biaya <= 0) {
            session()->setFlashdata('error', '❌ Biaya ongkir tidak valid.');
            return redirect()->to(base_url('logistik/tesongkir'));
        }
        if (empty($kurir) || empty($layanan)) {
            session()->setFlashdata('error', '❌ Data kurir tidak lengkap.');
            return redirect()->to(base_url('logistik/tesongkir'));
        }
        if ($berat < 100 || $berat > 30000) {
            session()->setFlashdata('error', '❌ Berat tidak valid.');
            return redirect()->to(base_url('logistik/tesongkir'));
        }

        // Sanitasi catatan
        $catatan = mb_substr(strip_tags($catatan), 0, 500);

        try {
            $db = \Config\Database::connect();

            $hargaDesain = 50000;
            $data = [
                'id_user'        => 1,
                'id_desain'      => 1,
                'id_vendor'      => 1,
                'jumlah'         => 1,
                'kurir'          => strtoupper($kurir),
                'layanan'        => $layanan,
                'kota_tujuan'    => $kotaTujuanNama,  // sesuai kolom di migration
                'berat'          => $berat,             // sesuai kolom di migration
                'ongkir'         => $biaya,
                'total_bayar'    => $hargaDesain + $biaya,
                'catatan'          => $catatan,
                'status_pesanan'   => 'pending',
                'created_at'       => date('Y-m-d H:i:s'),
            ];

            if ($db->table('orders')->insert($data)) {
                $idOrder = $db->insertID();
                session()->setFlashdata('sukses', "✅ Pesanan #ORD-{$idOrder} berhasil dibuat! Kurir: {$kurir} {$layanan} | Ongkir: Rp " . number_format($biaya, 0, ',', '.'));
                return redirect()->to(base_url('logistik/daftar-pesanan'));
            } else {
                session()->setFlashdata('error', '❌ Gagal menyimpan pesanan. Silakan coba lagi.');
                return redirect()->to(base_url('logistik/tesongkir'));
            }
        } catch (\Throwable $e) {
            log_message('error', '[simpanPesanan] ' . $e->getMessage());
            session()->setFlashdata('error', '❌ Terjadi kesalahan sistem: ' . $e->getMessage());
            return redirect()->to(base_url('logistik/tesongkir'));
        }
    }

    // =========================================================
    // DAFTAR PESANAN
    // =========================================================
    public function daftarPesanan()
    {
        $db = \Config\Database::connect();
        $query = $db->table('orders')->orderBy('created_at', 'DESC')->get();
        $data['semua_pesanan'] = $query->getResultArray();
        return view('daftar_pesanan', $data);
    }

    // =========================================================
    // HAPUS PESANAN - sekarang via POST (lebih aman)
    // =========================================================
    public function hapusPesanan($id)
    {
        // Pastikan via POST
        if (strtolower($this->request->getMethod()) !== 'post') {
            session()->setFlashdata('error', 'Metode tidak diizinkan.');
            return redirect()->to(base_url('logistik/daftar-pesanan'));
        }

        $id = (int) $id;
        if ($id <= 0) {
            session()->setFlashdata('error', 'ID pesanan tidak valid.');
            return redirect()->to(base_url('logistik/daftar-pesanan'));
        }

        $db = \Config\Database::connect();

        // Cek dulu pesanannya ada tidak
        $pesanan = $db->table('orders')->where('id_order', $id)->get()->getRowArray();
        if (!$pesanan) {
            session()->setFlashdata('error', 'Pesanan tidak ditemukan.');
            return redirect()->to(base_url('logistik/daftar-pesanan'));
        }

        // Hanya boleh hapus yang masih pending
        if ($pesanan['status_pesanan'] !== 'pending') {
            session()->setFlashdata('error', 'Pesanan yang sudah diproses tidak bisa dihapus.');
            return redirect()->to(base_url('logistik/daftar-pesanan'));
        }

        if ($db->table('orders')->where('id_order', $id)->delete()) {
            session()->setFlashdata('sukses', "Pesanan #ORD-{$id} berhasil dihapus.");
        } else {
            session()->setFlashdata('error', 'Gagal menghapus pesanan.');
        }

        return redirect()->to(base_url('logistik/daftar-pesanan'));
    }

    // =========================================================
    // TEST EMAIL & DB (untuk development)
    // =========================================================
    public function testEmail()
    {
        $email = \Config\Services::email();
        $email->setTo('punyakega3@gmail.com');
        $email->setFrom('punyakega3@gmail.com', 'Sistem Digital Memories');
        $email->setSubject('Tes Notifikasi Sistem');
        $email->setMessage('Halo! Sistem email sudah berjalan dengan baik.');
        if ($email->send()) {
            echo '<h1>✅ Email berhasil dikirim!</h1>';
        } else {
            echo '<pre>' . $email->printDebugger() . '</pre>';
        }
    }

    public function testSimpanOrder()
    {
        try {
            $db = \Config\Database::connect();
            $data = [
                'id_user'        => 1,
                'id_desain'      => 1,
                'id_vendor'      => 1,
                'jumlah'         => 1,
                'total_bayar'    => 69000.00,
                'ongkir'         => 19000.00,
                'status_pesanan' => 'pending',
                'created_at'     => date('Y-m-d H:i:s'),
            ];
            if ($db->table('orders')->insert($data)) {
                return '<h1>✅ SUKSES!</h1> Data masuk ke tabel orders.';
            }
            return '❌ Gagal: ' . json_encode($db->error());
        } catch (\Throwable $e) {
            return '<h1>🚨 ERROR:</h1><p>' . $e->getMessage() . '</p>';
        }
    }

    /**
     * Normalize shipping rates from API responses.
     *
     * If a value looks unrealistically large for domestic shipping,
     * assume it is expressed in the smallest unit and divide by 1000.
     */
    private function normalizeShippingRate($rate)
    {
        if (!is_numeric($rate)) {
            return $rate;
        }

        $rate = (float) $rate;

        if ($rate > 1000000) {
            $rate = $rate / 1000;
        }

        return ($rate === (int) $rate) ? (int) $rate : $rate;
    }
}
