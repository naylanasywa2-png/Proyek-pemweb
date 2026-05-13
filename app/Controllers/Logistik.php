<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\OngkirService;

/**
 * Controller Logistik
 *
 * Kolom tabel orders (sesuai migration yang sudah dijalankan):
 *   kurir          VARCHAR(50)
 *   layanan        VARCHAR(100)
 *   kota_tujuan    VARCHAR(150)   ← BUKAN kota_tujuan_nama
 *   berat          INT (gram)     ← BUKAN berat_gram
 *   catatan        TEXT
 */
class Logistik extends BaseController
{
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

    private function getNamaKota(int $id): string
    {
        foreach ($this->kotaTujuan as $k) {
            if ($k['id'] === $id) return $k['name'];
        }
        return '';
    }

    // =========================================================
    // CEK ONGKIR
    // =========================================================
    public function tesOngkir()
    {
        helper(['url', 'form']);

        $method      = strtolower($this->request->getMethod());
        $service     = new OngkirService();
        $tujuan      = 4982;
        $berat       = 1000;
        $result      = null;
        $errors      = [];
        $filterKurir = '';

        if ($method === 'post') {
            $tujuan      = (int)   $this->request->getPost('tujuan');
            $berat       = (int)   $this->request->getPost('berat');
            $filterKurir = strtoupper(trim($this->request->getPost('filter_kurir') ?? ''));

            // Validasi tujuan (whitelist)
            if (!in_array($tujuan, array_column($this->kotaTujuan, 'id'), true)) {
                $errors['tujuan'] = 'Kota tujuan tidak valid.';
                $tujuan = 4982;
            }

            // Validasi berat
            if ($berat < 100) {
                $errors['berat'] = 'Berat minimal 100 gram.';
                $berat = 100;
            } elseif ($berat > 30000) {
                $errors['berat'] = 'Berat maksimal 30.000 gram (30 kg).';
                $berat = 30000;
            }

            if (empty($errors)) {
                $result = $service->cekOngkir(
                    shipperId:  $this->asalId,
                    receiverId: $tujuan,
                    weight:     $berat / 1000,  // API butuh kg
                    itemValue:  50000,
                    cod:        'no'
                );

                // Filter server-side
                if (!empty($filterKurir) && $filterKurir !== 'SEMUA' && isset($result['data'])) {
                    $result['data'] = array_values(array_filter(
                        $result['data'],
                        fn($item) => strtoupper($item['courier']) === $filterKurir
                    ));
                }
            }
        }

        return view('cek_ongkir', [
            'result'       => $result,
            'kota_list'    => $this->kotaTujuan,
            'tujuan'       => $tujuan,
            'tujuan_nama'  => $this->getNamaKota($tujuan),
            'berat'        => $berat,
            'is_post'      => ($method === 'post'),
            'errors'       => $errors,
            'filter_kurir' => $filterKurir,
        ]);
    }

    // =========================================================
    // DETAIL PESANAN - halaman konfirmasi
    // =========================================================
    public function detailPesanan()
    {
        helper(['url', 'form']);

        if (strtolower($this->request->getMethod()) !== 'post') {
            return redirect()->to(base_url('logistik/tesongkir'));
        }

        $biaya      = (int)  $this->request->getPost('biaya');
        $biayaNet   = (int) ($this->request->getPost('biaya_net') ?? 0);
        $kurir      = trim(  $this->request->getPost('kurir')     ?? '');
        $layanan    = trim(  $this->request->getPost('layanan')   ?? '');
        $kotaTujuan = (int)  $this->request->getPost('kota_tujuan');
        $berat      = (int)  $this->request->getPost('berat');
        $etd        = trim(  $this->request->getPost('etd')       ?? '-');
        $deskripsi  = trim(  $this->request->getPost('deskripsi') ?? '');
        $isCod      =       ($this->request->getPost('is_cod') === '1');

        if ($biaya <= 0 || empty($kurir) || empty($layanan)) {
            session()->setFlashdata('error', 'Data pengiriman tidak valid. Silakan pilih ulang.');
            return redirect()->to(base_url('logistik/tesongkir'));
        }

        $hargaDesain = 50000;

        return view('detail_pesanan', [
            'biaya'          => $biaya,
            'biaya_net'      => $biayaNet,
            'kurir'          => strtoupper($kurir),
            'layanan'        => $layanan,
            'deskripsi'      => $deskripsi,
            'etd'            => $etd,
            'is_cod'         => $isCod,
            'kota_tujuan_id' => $kotaTujuan,
            'kota_tujuan'    => $this->getNamaKota($kotaTujuan),
            'berat'          => $berat,
            'harga_desain'   => $hargaDesain,
            'total_bayar'    => $hargaDesain + $biaya,
        ]);
    }

    // =========================================================
    // SIMPAN PESANAN
    // PENTING: nama kolom harus PERSIS sesuai migration
    //   kota_tujuan (bukan kota_tujuan_nama)
    //   berat       (bukan berat_gram)
    // =========================================================
    public function simpanPesanan()
    {
        helper(['url']);

        if (strtolower($this->request->getMethod()) !== 'post') {
            return redirect()->to(base_url('logistik/tesongkir'));
        }

        $biaya          = (int)  $this->request->getPost('biaya');
        $kurir          = trim(  $this->request->getPost('kurir')            ?? '');
        $layanan        = trim(  $this->request->getPost('layanan')          ?? '');
        $kotaTujuanNama = trim(  $this->request->getPost('kota_tujuan_nama') ?? '');
        $beratGram      = (int)  $this->request->getPost('berat_gram');   // dari form hidden
        $catatan        = trim(  $this->request->getPost('catatan')       ?? '');

        // Validasi
        if ($biaya <= 0) {
            session()->setFlashdata('error', '❌ Biaya ongkir tidak valid.');
            return redirect()->to(base_url('logistik/tesongkir'));
        }
        if (empty($kurir) || empty($layanan)) {
            session()->setFlashdata('error', '❌ Data kurir tidak lengkap.');
            return redirect()->to(base_url('logistik/tesongkir'));
        }
        if ($beratGram < 100 || $beratGram > 30000) {
            session()->setFlashdata('error', '❌ Berat tidak valid (100–30.000 gram).');
            return redirect()->to(base_url('logistik/tesongkir'));
        }

        $catatan = mb_substr(strip_tags($catatan), 0, 500);

        try {
            $db          = \Config\Database::connect();
            $hargaDesain = 50000;

            $data = [
                'id_user'        => 1,
                'id_desain'      => 1,
                'id_vendor'      => 1,
                'jumlah'         => 1,
                'kurir'          => strtoupper($kurir),
                'layanan'        => $layanan,
                'kota_tujuan'    => $kotaTujuanNama,   // ← nama kolom di DB
                'berat'          => $beratGram,         // ← nama kolom di DB (gram)
                'ongkir'         => $biaya,
                'total_bayar'    => $hargaDesain + $biaya,
                'catatan'        => $catatan,
                'status_pesanan' => 'pending',
                'created_at'     => date('Y-m-d H:i:s'),
            ];

            if ($db->table('orders')->insert($data)) {
                $idOrder = $db->insertID();
                session()->setFlashdata(
                    'sukses',
                    "✅ Pesanan #ORD-{$idOrder} berhasil! {$kurir} {$layanan} | Rp "
                        . number_format($biaya, 0, ',', '.')
                );
                return redirect()->to(base_url('logistik/daftar-pesanan'));
            }

            session()->setFlashdata('error', '❌ Gagal menyimpan pesanan.');
            return redirect()->to(base_url('logistik/tesongkir'));

        } catch (\Throwable $e) {
            log_message('error', '[simpanPesanan] ' . $e->getMessage());
            session()->setFlashdata('error', '❌ Error sistem: ' . $e->getMessage());
            return redirect()->to(base_url('logistik/tesongkir'));
        }
    }

    // =========================================================
    // DAFTAR PESANAN
    // =========================================================
    public function daftarPesanan()
    {
        $db = \Config\Database::connect();
        $data['semua_pesanan'] = $db->table('orders')
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResultArray();

        return view('daftar_pesanan', $data);
    }

    // =========================================================
    // HAPUS PESANAN - POST only
    // =========================================================
    public function hapusPesanan(int $id)
    {
        if (strtolower($this->request->getMethod()) !== 'post') {
            session()->setFlashdata('error', 'Metode tidak diizinkan.');
            return redirect()->to(base_url('logistik/daftar-pesanan'));
        }

        $id = (int) $id;
        if ($id <= 0) {
            session()->setFlashdata('error', 'ID tidak valid.');
            return redirect()->to(base_url('logistik/daftar-pesanan'));
        }

        $db      = \Config\Database::connect();
        $pesanan = $db->table('orders')->where('id_order', $id)->get()->getRowArray();

        if (!$pesanan) {
            session()->setFlashdata('error', 'Pesanan tidak ditemukan.');
            return redirect()->to(base_url('logistik/daftar-pesanan'));
        }

        if ($pesanan['status_pesanan'] !== 'pending') {
            session()->setFlashdata('error', 'Hanya pesanan pending yang bisa dihapus.');
            return redirect()->to(base_url('logistik/daftar-pesanan'));
        }

        if ($db->table('orders')->where('id_order', $id)->delete()) {
            session()->setFlashdata('sukses', "Pesanan #ORD-{$id} berhasil dihapus.");
        } else {
            session()->setFlashdata('error', 'Gagal menghapus pesanan.');
        }

        return redirect()->to(base_url('logistik/daftar-pesanan'));
    }

    // DEV TOOLS
    public function testEmail()
    {
        $email = \Config\Services::email();
        $email->setTo('punyakega3@gmail.com');
        $email->setFrom('punyakega3@gmail.com', 'Sistem Digital Memories');
        $email->setSubject('Tes Email');
        $email->setMessage('Sistem email berjalan normal.');
        echo $email->send() ? '<h1>✅ Berhasil!</h1>' : '<pre>' . $email->printDebugger() . '</pre>';
    }

    public function testSimpanOrder()
    {
        try {
            $db = \Config\Database::connect();
            return $db->table('orders')->insert([
                'id_user' => 1, 'id_desain' => 1, 'id_vendor' => 1,
                'jumlah' => 1, 'ongkir' => 19000, 'total_bayar' => 69000,
                'status_pesanan' => 'pending', 'created_at' => date('Y-m-d H:i:s'),
            ]) ? '<h1>✅ SUKSES!</h1>' : '❌ Gagal: ' . json_encode($db->error());
        } catch (\Throwable $e) {
            return '<p>🚨 ' . $e->getMessage() . '</p>';
        }
    }
}