<?php

/**
 * =============================================================================
 * FILE: app/Controllers/Logistik.php
 * =============================================================================
 *
 * FUNGSI FILE INI:
 *   Controller yang mengatur semua halaman dan proses di modul logistik/ongkir.
 *   Menjadi "penghubung" antara request user, Service (OngkirService),
 *   database (tabel orders), dan View (halaman yang ditampilkan).
 *
 * ALUR KERJA CONTROLLER:
 *   1. User buka halaman → Controller ambil data → kirim ke View
 *   2. User submit form  → Controller validasi → proses → redirect
 *
 * METHOD YANG ADA DI SINI:
 *   - tesOngkir()      → Halaman cek ongkir (GET = tampilkan form, POST = proses)
 *   - detailPesanan()  → Halaman konfirmasi sebelum pesan (POST only)
 *   - simpanPesanan()  → Simpan pesanan ke database (POST only)
 *   - daftarPesanan()  → Tampilkan semua riwayat pesanan (GET)
 *   - hapusPesanan()   → Hapus pesanan yang masih pending (POST only)
 *
 * ROUTES YANG DIBUTUHKAN (di app/Config/Routes.php):
 *   $routes->group('logistik', function($routes) {
 *       $routes->get('tesongkir',               'Logistik::tesOngkir');
 *       $routes->post('tesongkir',              'Logistik::tesOngkir');
 *       $routes->post('detail-pesanan',         'Logistik::detailPesanan');
 *       $routes->post('simpan-pesanan',         'Logistik::simpanPesanan');
 *       $routes->get('daftar-pesanan',          'Logistik::daftarPesanan');
 *       $routes->post('hapus-pesanan/(:num)',   'Logistik::hapusPesanan/$1');
 *   });
 *
 * LOKASI FILE:
 *   app/Controllers/Logistik.php
 * =============================================================================
 */

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\OngkirService;

class Logistik extends BaseController
{
    // =========================================================================
    // DATA KOTA TUJUAN
    // =========================================================================

    /**
     * $kotaTujuan
     *
     * Daftar kota pengiriman yang tersedia, berformat array of array.
     * Setiap kota punya:
     *   - 'id'   → ID kota di sistem Komerce (dipakai untuk request API)
     *   - 'name' → Nama tampilan untuk dropdown di form
     *
     * CATATAN: ID ini HARUS sesuai dengan database Komerce.
     * Untuk cari ID kota lain, pakai endpoint:
     *   GET /destination/search?keyword=nama_kota
     * atau buka halaman /diagnostik-api
     *
     * Properti ini private karena hanya dipakai di dalam Controller ini.
     */
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

    /**
     * $asalId
     *
     * ID kota ASAL pengiriman (Surabaya - Genteng = 12043).
     * Ini adalah lokasi toko/gudang kita.
     * Nilainya tetap/fixed, tidak bisa diubah oleh user.
     */
    private int $asalId = 12043;


    // =========================================================================
    // HELPER PRIVATE
    // =========================================================================

    /**
     * getNamaKota()
     *
     * Mencari nama kota berdasarkan ID-nya dari array $kotaTujuan.
     * Dipakai untuk menampilkan nama kota (bukan ID) di View konfirmasi.
     *
     * @param int $id ID kota yang dicari
     * @return string Nama kota, atau string kosong jika tidak ditemukan
     */
    private function getNamaKota(int $id): string
    {
        foreach ($this->kotaTujuan as $kota) {
            if ($kota['id'] === $id) {
                return $kota['name'];
            }
        }
        return '';
    }

    /**
     * isKotaValid()
     *
     * Mengecek apakah ID kota yang dikirim dari form ada di daftar $kotaTujuan.
     * Ini adalah validasi whitelist — hanya ID yang ada di daftar yang diterima.
     * Mencegah user mengirim ID sembarangan via form manipulation.
     *
     * @param int $id ID yang akan dicek
     * @return bool true jika valid, false jika tidak ada di daftar
     */
    private function isKotaValid(int $id): bool
    {
        return in_array($id, array_column($this->kotaTujuan, 'id'), true);
    }


    // =========================================================================
    // METHOD 1: CEK ONGKIR
    // =========================================================================

    /**
     * tesOngkir()
     *
     * Menangani dua skenario sekaligus:
     *   - GET  → tampilkan form kosong (user baru buka halaman)
     *   - POST → proses form dan tampilkan hasil ongkir
     *
     * Kenapa satu method untuk GET dan POST?
     * Supaya URL tetap sama (/logistik/tesongkir) dan kode tidak duplikat.
     * Controller membedakannya dengan mengecek HTTP method.
     *
     * VALIDASI YANG DILAKUKAN:
     *   1. Kota tujuan harus ada di daftar whitelist
     *   2. Berat antara 100 - 30.000 gram
     *   3. Semua error dikumpulkan dulu, baru ditampilkan sekaligus
     *
     * DATA YANG DIKIRIM KE VIEW:
     *   - result       → hasil cek ongkir (null jika belum submit)
     *   - kota_list    → daftar kota untuk dropdown
     *   - tujuan       → ID kota yang dipilih (untuk set selected)
     *   - tujuan_nama  → nama kota yang dipilih (untuk tampilan)
     *   - berat        → berat yang diisi user (untuk isi ulang form)
     *   - is_post      → apakah sudah submit form? (untuk logika tampilan)
     *   - errors       → array error validasi (kosong jika tidak ada)
     *   - filter_kurir → filter kurir yang dipilih
     */
    public function tesOngkir()
    {
        // Muat helper 'url' (untuk base_url()) dan 'form' (untuk form_error())
        helper(['url', 'form']);

        // Deteksi HTTP method (GET atau POST)
        $method = strtolower($this->request->getMethod());

        // Inisialisasi variabel dengan nilai default
        $service     = new OngkirService();
        $tujuan      = 4982;   // Default: Jakarta Barat
        $berat       = 1000;   // Default: 1000 gram = 1 kg
        $result      = null;   // Null = belum ada hasil
        $errors      = [];     // Kosong = belum ada error
        $filterKurir = '';     // Kosong = tampilkan semua kurir

        // Hanya proses logika ini kalau user submit form (method POST)
        if ($method === 'post') {

            // -----------------------------------------------------------------
            // Ambil data dari form
            // getPost() = ambil data dari $_POST (form yang disubmit)
            // (int) = paksa jadi integer, (string) trim = bersihkan spasi
            // -----------------------------------------------------------------
            $tujuan      = (int) $this->request->getPost('tujuan');
            $berat       = (int) $this->request->getPost('berat');
            $filterKurir = strtoupper(trim($this->request->getPost('filter_kurir') ?? ''));

            // -----------------------------------------------------------------
            // Validasi Input
            // -----------------------------------------------------------------

            // Validasi kota: harus ada di daftar whitelist
            if (! $this->isKotaValid($tujuan)) {
                $errors['tujuan'] = 'Kota tujuan tidak valid. Silakan pilih dari daftar.';
                $tujuan = 4982; // Reset ke default
            }

            // Validasi berat: minimal 100 gram, maksimal 30kg
            if ($berat < 100) {
                $errors['berat'] = 'Berat minimal 100 gram.';
                $berat = 100;
            } elseif ($berat > 30000) {
                $errors['berat'] = 'Berat maksimal 30.000 gram (30 kg).';
                $berat = 30000;
            }

            // -----------------------------------------------------------------
            // Proses cek ongkir HANYA jika tidak ada error validasi
            // -----------------------------------------------------------------
            if (empty($errors)) {

                // Konversi gram ke kg karena API Komerce pakai KG
                $beratKg = $berat / 1000;

                // Panggil OngkirService untuk dapat tarif
                $result = $service->cekOngkir(
                    shipperId:  $this->asalId,
                    receiverId: $tujuan,
                    weight:     $beratKg,
                    itemValue:  50000,
                    cod:        'no'
                );

                // -----------------------------------------------------------------
                // Filter kurir di sisi server (tambahan keamanan)
                // Filter utama sudah ada di View (JavaScript), ini sebagai backup
                // -----------------------------------------------------------------
                if (
                    ! empty($filterKurir)
                    && $filterKurir !== 'SEMUA'
                    && isset($result['data'])
                ) {
                    $result['data'] = array_values(
                        array_filter(
                            $result['data'],
                            fn($item) => strtoupper($item['courier']) === $filterKurir
                        )
                    );
                }
            }
        }

        // Kirim data ke View untuk ditampilkan
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


    // =========================================================================
    // METHOD 2: DETAIL PESANAN (Halaman Konfirmasi)
    // =========================================================================

    /**
     * detailPesanan()
     *
     * Menampilkan halaman konfirmasi sebelum pesanan benar-benar disimpan.
     * User bisa review detail dan mengisi catatan sebelum lanjut ke simpan.
     *
     * KENAPA ADA HALAMAN KONFIRMASI?
     * Supaya user tidak langsung menyimpan pesanan tanpa tahu detailnya.
     * Ini juga memberi kesempatan user untuk batal dan pilih kurir lain.
     *
     * ALUR:
     *   cek_ongkir → [user klik "Pilih"] → detail_pesanan → [user klik "Konfirmasi"] → simpanPesanan
     *
     * METHOD: POST only
     * Kenapa POST? Karena data tarif dikirim dari form di cek_ongkir.
     * Tidak boleh GET karena data bisa dimanipulasi lewat URL.
     *
     * VALIDASI:
     *   - Method harus POST
     *   - Biaya harus > 0
     *   - Kurir dan layanan tidak boleh kosong
     */
    public function detailPesanan()
    {
        helper(['url', 'form']);

        // Tolak request selain POST
        if (strtolower($this->request->getMethod()) !== 'post') {
            return redirect()->to(base_url('logistik/tesongkir'));
        }

        // Ambil semua data dari form hidden yang dikirim oleh cek_ongkir.php
        $biaya      = (int)   $this->request->getPost('biaya');
        $biayaNet   = (int)  ($this->request->getPost('biaya_net') ?? 0);
        $kurir      = trim(   $this->request->getPost('kurir')     ?? '');
        $layanan    = trim(   $this->request->getPost('layanan')   ?? '');
        $deskripsi  = trim(   $this->request->getPost('deskripsi') ?? '');
        $etd        = trim(   $this->request->getPost('etd')       ?? '-');
        $isCod      =        ($this->request->getPost('is_cod') === '1');
        $kotaTujuan = (int)   $this->request->getPost('kota_tujuan');
        $berat      = (int)   $this->request->getPost('berat');

        // Validasi data minimum yang harus ada
        if ($biaya <= 0 || empty($kurir) || empty($layanan)) {
            session()->setFlashdata('error', 'Data pengiriman tidak valid. Silakan pilih ulang.');
            return redirect()->to(base_url('logistik/tesongkir'));
        }

        // Harga desain buku memori (fixed untuk saat ini)
        // Di masa depan bisa diambil dari database tabel desain
        $hargaDesain = 50000;

        // Kirim ke View konfirmasi
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


    // =========================================================================
    // METHOD 3: SIMPAN PESANAN
    // =========================================================================

    /**
     * simpanPesanan()
     *
     * Menyimpan data pesanan ke database tabel 'orders'.
     * Dipanggil saat user klik tombol "Konfirmasi Pesanan" di halaman detail_pesanan.
     *
     * METHOD: POST only (tidak boleh akses lewat URL langsung)
     *
     * VALIDASI BERLAPIS:
     *   Layer 1 - Method harus POST
     *   Layer 2 - Biaya > 0
     *   Layer 3 - Kurir dan layanan tidak kosong
     *   Layer 4 - Berat dalam range valid (100 - 30.000 gram)
     *
     * SANITASI DATA:
     *   - strip_tags()  → hapus tag HTML dari catatan (cegah XSS)
     *   - mb_substr()   → batasi panjang catatan maksimal 500 karakter
     *   - strtoupper()  → nama kurir selalu HURUF BESAR
     *
     * NAMA KOLOM TABEL ORDERS:
     *   kota_tujuan  → nama kota sebagai string (bukan ID)
     *   berat        → berat dalam gram sebagai integer
     *   (Sesuaikan dengan hasil migration yang sudah dijalankan)
     *
     * SETELAH BERHASIL:
     *   → Set flashdata sukses dengan nomor order
     *   → Redirect ke halaman daftar pesanan
     */
    public function simpanPesanan()
    {
        helper(['url']);

        // Layer 1: Tolak jika bukan POST
        if (strtolower($this->request->getMethod()) !== 'post') {
            return redirect()->to(base_url('logistik/tesongkir'));
        }

        // Ambil data dari form hidden di detail_pesanan.php
        $biaya          = (int)  $this->request->getPost('biaya');
        $kurir          = trim(  $this->request->getPost('kurir')            ?? '');
        $layanan        = trim(  $this->request->getPost('layanan')          ?? '');
        $kotaTujuanNama = trim(  $this->request->getPost('kota_tujuan_nama') ?? '');
        $beratGram      = (int)  $this->request->getPost('berat_gram');
        $catatan        = trim(  $this->request->getPost('catatan')          ?? '');

        // Layer 2: Validasi biaya
        if ($biaya <= 0) {
            session()->setFlashdata('error', 'Biaya ongkir tidak valid.');
            return redirect()->to(base_url('logistik/tesongkir'));
        }

        // Layer 3: Validasi kurir dan layanan
        if (empty($kurir) || empty($layanan)) {
            session()->setFlashdata('error', 'Data kurir tidak lengkap.');
            return redirect()->to(base_url('logistik/tesongkir'));
        }

        // Layer 4: Validasi berat
        if ($beratGram < 100 || $beratGram > 30000) {
            session()->setFlashdata('error', 'Berat tidak valid (100 – 30.000 gram).');
            return redirect()->to(base_url('logistik/tesongkir'));
        }

        // Sanitasi catatan: hapus HTML tag, batasi 500 karakter
        $catatan = mb_substr(strip_tags($catatan), 0, 500);

        // Simpan ke database
        try {
            $db          = \Config\Database::connect();
            $hargaDesain = 50000;

            // Array data yang akan di-INSERT ke tabel orders
            // PENTING: nama key harus PERSIS sama dengan nama kolom di database
            $dataOrder = [
                'id_user'        => session()->get('user_id') ?? 1,
                'id_desain'      => 1,
                'id_vendor'      => 1,
                'jumlah'         => 1,
                'kurir'          => strtoupper($kurir),
                'layanan'        => $layanan,
                'kota_tujuan'    => $kotaTujuanNama,  // ← kolom kota_tujuan (string)
                'berat'          => $beratGram,        // ← kolom berat (integer gram)
                'ongkir'         => $biaya,
                'total_bayar'    => $hargaDesain + $biaya,
                'catatan'        => $catatan,
                'status_pesanan' => 'pending',
                'created_at'     => date('Y-m-d H:i:s'),
            ];

            if ($db->table('orders')->insert($dataOrder)) {
                $idOrder = $db->insertID(); // Ambil ID yang baru saja dibuat

                session()->setFlashdata(
                    'sukses',
                    "Pesanan #ORD-{$idOrder} berhasil! {$kurir} {$layanan} | "
                    . "Ongkir: Rp " . number_format($biaya, 0, ',', '.')
                );

                return redirect()->to(base_url('logistik/daftar-pesanan'));
            }

            // Jika insert() mengembalikan false tanpa exception
            session()->setFlashdata('error', 'Gagal menyimpan pesanan. Coba beberapa saat lagi.');
            return redirect()->to(base_url('logistik/tesongkir'));

        } catch (\Throwable $e) {
            // Tangkap semua error database dan log untuk debugging
            log_message('error', '[Logistik::simpanPesanan] ' . $e->getMessage());
            session()->setFlashdata('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
            return redirect()->to(base_url('logistik/tesongkir'));
        }
    }


    // =========================================================================
    // METHOD 4: DAFTAR PESANAN
    // =========================================================================

    /**
     * daftarPesanan()
     *
     * Menampilkan semua pesanan dari database, diurutkan dari yang terbaru.
     * Di sini tidak ada filter per-user (tampilkan semua) karena untuk saat ini
     * belum ada sistem login yang terintegrasi penuh.
     *
     * Untuk produksi, tambahkan:
     *   ->where('id_user', session()->get('user_id'))
     *
     * DATA YANG DIKIRIM KE VIEW:
     *   - semua_pesanan → array semua baris dari tabel orders
     */
    public function daftarPesanan()
    {
        $db = \Config\Database::connect();

        // Ambil semua pesanan, diurutkan dari terbaru ke lama
        $semuaPesanan = $db->table('orders')
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResultArray();

        return view('daftar_pesanan', [
            'semua_pesanan' => $semuaPesanan,
        ]);
    }


    // =========================================================================
    // METHOD 5: HAPUS PESANAN
    // =========================================================================

    /**
     * hapusPesanan()
     *
     * Menghapus pesanan dari database.
     * Hanya bisa dilakukan jika status pesanan masih 'pending'.
     * Pesanan yang sudah diproses, dikirim, atau selesai tidak bisa dihapus.
     *
     * METHOD: POST only
     * Kenapa tidak GET? Karena hapus data adalah operasi yang mengubah state.
     * GET request bisa dipicu oleh browser prefetch, bot, atau link preview
     * yang bisa menghapus data secara tidak sengaja.
     *
     * VALIDASI:
     *   1. Method harus POST
     *   2. ID harus angka positif
     *   3. Pesanan harus ada di database
     *   4. Status harus 'pending'
     *
     * @param int $id ID pesanan yang akan dihapus (dari URL segment)
     */
    public function hapusPesanan(int $id)
    {
        // Pastikan method POST
        if (strtolower($this->request->getMethod()) !== 'post') {
            session()->setFlashdata('error', 'Metode request tidak diizinkan.');
            return redirect()->to(base_url('logistik/daftar-pesanan'));
        }

        // Validasi ID
        $id = (int) $id;
        if ($id <= 0) {
            session()->setFlashdata('error', 'ID pesanan tidak valid.');
            return redirect()->to(base_url('logistik/daftar-pesanan'));
        }

        $db = \Config\Database::connect();

        // Cari pesanan di database
        $pesanan = $db->table('orders')
            ->where('id_order', $id)
            ->get()
            ->getRowArray();

        // Pesanan tidak ditemukan
        if (! $pesanan) {
            session()->setFlashdata('error', "Pesanan #ORD-{$id} tidak ditemukan.");
            return redirect()->to(base_url('logistik/daftar-pesanan'));
        }

        // Hanya pesanan 'pending' yang boleh dihapus
        if ($pesanan['status_pesanan'] !== 'pending') {
            session()->setFlashdata('error', 'Hanya pesanan dengan status PENDING yang bisa dihapus.');
            return redirect()->to(base_url('logistik/daftar-pesanan'));
        }

        // Lakukan penghapusan
        if ($db->table('orders')->where('id_order', $id)->delete()) {
            session()->setFlashdata('sukses', "Pesanan #ORD-{$id} berhasil dihapus.");
        } else {
            session()->setFlashdata('error', 'Gagal menghapus pesanan. Coba lagi.');
        }

        return redirect()->to(base_url('logistik/daftar-pesanan'));
    }
}