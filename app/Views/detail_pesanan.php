<!DOCTYPE html>
<html lang="id">
<!--
=================================================================================
FILE: app/Views/detail_pesanan.php
=================================================================================

FUNGSI FILE INI:
  Halaman konfirmasi yang muncul SETELAH user memilih kurir di cek_ongkir.php
  dan SEBELUM pesanan benar-benar disimpan ke database.

  Ini adalah "jembatan" antara cek ongkir dan simpan pesanan.
  User bisa review semua detail, mengisi catatan, lalu konfirmasi atau batal.

VARIABEL YANG DITERIMA DARI CONTROLLER (Logistik::detailPesanan):
  $biaya          → int harga ongkir yang dipilih
  $biaya_net      → int harga ongkir net (kalau ada, biasanya 0)
  $kurir          → string nama kurir uppercase, misal "JNE"
  $layanan        → string nama layanan, misal "REG"
  $deskripsi      → string deskripsi layanan
  $etd            → string estimasi tiba, misal "2-3 Hari"
  $is_cod         → bool apakah COD?
  $kota_tujuan_id → int ID kota tujuan
  $kota_tujuan    → string nama kota tujuan
  $berat          → int berat dalam gram
  $harga_desain   → int harga buku memori (fixed 50000)
  $total_bayar    → int total = harga_desain + biaya

ALUR DATA:
  cek_ongkir.php → [POST form hidden] → Logistik::detailPesanan() → detail_pesanan.php
  detail_pesanan.php → [POST form hidden] → Logistik::simpanPesanan() → database
=================================================================================
-->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pesanan - Digital Memories</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: linear-gradient(135deg, #fff5f7 0%, #ffe4e9 100%);
            font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
            min-height: 100vh;
            padding: 40px 20px;
            color: #555;
        }

        /* Container lebih sempit dari cek_ongkir karena kontennya vertikal */
        .container {
            background: white;
            padding: 40px;
            border-radius: 24px;
            box-shadow: 0 15px 40px rgba(255, 107, 149, 0.15);
            max-width: 680px;
            margin: auto;
        }

        /* ================================================================
           HEADER
        ================================================================ */
        .page-header { text-align: center; margin-bottom: 28px; }
        .page-header .icon { font-size: 3rem; margin-bottom: 10px; }
        h2 { color: #ff4d7d; font-size: 1.7rem; font-weight: 800; margin-bottom: 6px; }
        .subtitle { color: #aaa; font-size: 0.88rem; }

        /* ================================================================
           STEP INDICATOR
           Menunjukkan posisi user dalam alur pemesanan:
           [✓ Cek Ongkir] → [2 Konfirmasi] → [3 Selesai]
        ================================================================ */
        .steps {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            margin-bottom: 28px;
        }
        .step { display: flex; align-items: center; gap: 8px; font-size: 0.8rem; font-weight: 600; }
        .step-num {
            width: 28px; height: 28px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; font-weight: 700;
        }
        /* Step yang sudah selesai: warna pink muda */
        .step.done .step-num   { background: #ffdae3; color: #ff4d7d; }
        .step.done .step-label { color: #ff4d7d; }
        /* Step aktif (sekarang): warna solid */
        .step.active .step-num   { background: #ff4d7d; color: white; }
        .step.active .step-label { color: #ff4d7d; font-weight: 700; }
        /* Step berikutnya: abu-abu */
        .step.pending .step-num   { background: #f0f0f0; color: #bbb; }
        .step.pending .step-label { color: #bbb; }
        /* Garis penghubung antar step */
        .step-line { width: 40px; height: 2px; background: #ffdae3; margin: 0 4px; }

        /* ================================================================
           CARD DETAIL PENGIRIMAN
        ================================================================ */
        .detail-card {
            background: #fff8fa;
            border: 1.5px solid #ffdae3;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 20px;
        }
        .detail-card h3 {
            color: #ff4d7d;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 16px;
        }

        /* Setiap baris label-value di dalam card */
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #ffe4e9;
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { color: #888; font-size: 0.88rem; }
        .detail-value { color: #333; font-size: 0.92rem; font-weight: 600; text-align: right; }

        /* Badge-badge di dalam tabel detail */
        .kurir-badge {
            background: #ffe4e9; color: #ff4d7d;
            padding: 4px 12px; border-radius: 20px;
            font-size: 0.82rem; font-weight: 700;
        }
        .etd-badge {
            background: #f0fff4; color: #16a34a;
            padding: 4px 12px; border-radius: 20px;
            font-size: 0.82rem; font-weight: 600;
        }

        /* ================================================================
           RINGKASAN HARGA
        ================================================================ */
        .price-summary {
            background: linear-gradient(135deg, #fff0f3, #ffe4e9);
            border: 1.5px solid #ffb3cc;
            border-radius: 16px;
            padding: 20px 24px;
            margin-bottom: 20px;
        }
        .price-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            font-size: 0.9rem;
        }
        /* Baris total berbeda styling */
        .price-row.total {
            border-top: 2px solid #ffb3cc;
            margin-top: 10px;
            padding-top: 14px;
            font-size: 1.05rem;
            font-weight: 800;
            color: #ff4d7d;
        }
        .price-label { color: #888; }
        .price-value { font-weight: 600; color: #333; }

        /* ================================================================
           FORM CATATAN OPSIONAL
        ================================================================ */
        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #d63384;
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        textarea {
            width: 100%;
            padding: 12px 14px;
            border: 2px solid #ffdae3;
            border-radius: 12px;
            font-family: inherit;
            font-size: 0.9rem;
            color: #555;
            resize: vertical;
            min-height: 80px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        textarea:focus {
            border-color: #ff85a2;
            box-shadow: 0 0 0 3px rgba(255, 133, 162, 0.2);
        }

        /* Counter karakter textarea */
        .char-count { font-size: 0.75rem; color: #bbb; text-align: right; margin-top: 4px; }
        .char-count.warn { color: #ff4d7d; } /* merah kalau hampir penuh */

        /* ================================================================
           KOTAK INFO
        ================================================================ */
        .info-box {
            background: #f0f4ff;
            border: 1px solid #b3c6ff;
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 0.85rem;
            color: #3355cc;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        /* ================================================================
           TOMBOL AKSI
        ================================================================ */
        .btn-group { display: flex; gap: 12px; }

        /* Tombol kembali (outline) */
        .btn-kembali {
            flex: 1;
            padding: 14px;
            border-radius: 12px;
            border: 2px solid #ffdae3;
            background: white;
            color: #ff85a2;
            font-weight: 700;
            font-size: 0.95rem;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-kembali:hover { background: #fff5f7; border-color: #ff85a2; }

        /* Tombol konfirmasi (solid gradient) */
        .btn-konfirmasi {
            flex: 2;
            padding: 14px;
            border-radius: 12px;
            background: linear-gradient(135deg, #ff85a2, #ff4d7d);
            color: white;
            font-weight: 700;
            font-size: 0.95rem;
            font-family: inherit;
            cursor: pointer;
            border: none;
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-konfirmasi:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(255, 77, 125, 0.35);
        }
        .btn-konfirmasi:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }
        .btn-konfirmasi .spinner {
            display: none;
            width: 18px; height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.4);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }
        .btn-konfirmasi.loading .spinner { display: block; }
        .btn-konfirmasi.loading .btn-text { display: none; }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Responsive */
        @media (max-width: 500px) {
            .container { padding: 24px 18px; }
            .btn-group { flex-direction: column-reverse; }
            .btn-kembali, .btn-konfirmasi { flex: none; width: 100%; }
        }
    </style>
</head>
<body>
<div class="container">

    <!-- Header -->
    <div class="page-header">
        <div class="icon">🛒</div>
        <h2>Konfirmasi Pesanan</h2>
        <p class="subtitle">Periksa detail sebelum memesan</p>
    </div>

    <!-- ====================================================================
         STEP INDICATOR
         Step 1 (Cek Ongkir) sudah selesai → tampilkan centang
         Step 2 (Konfirmasi) sedang aktif → warna solid
         Step 3 (Selesai) belum → abu-abu
    ==================================================================== -->
    <div class="steps">
        <div class="step done">
            <div class="step-num">✓</div>
            <span class="step-label">Cek Ongkir</span>
        </div>
        <div class="step-line"></div>
        <div class="step active">
            <div class="step-num">2</div>
            <span class="step-label">Konfirmasi</span>
        </div>
        <div class="step-line"></div>
        <div class="step pending">
            <div class="step-num">3</div>
            <span class="step-label">Selesai</span>
        </div>
    </div>

    <!-- ====================================================================
         CARD DETAIL PENGIRIMAN
         Menampilkan semua info kurir yang dipilih user
    ==================================================================== -->
    <div class="detail-card">
        <h3>📦 Detail Pengiriman</h3>

        <div class="detail-row">
            <span class="detail-label">Asal Pengiriman</span>
            <span class="detail-value">Surabaya - Genteng</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Kota Tujuan</span>
            <span class="detail-value"><?= esc($kota_tujuan ?: '-') ?></span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Berat Paket</span>
            <span class="detail-value">
                <?= number_format($berat, 0, ',', '.') ?> gram
                (<?= number_format($berat / 1000, 2, ',', '.') ?> kg)
            </span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Kurir & Layanan</span>
            <span class="detail-value">
                <span class="kurir-badge"><?= esc($kurir) ?></span>
                &nbsp;<?= esc($layanan) ?>
            </span>
        </div>

        <?php if (! empty($deskripsi) && $deskripsi !== $layanan): ?>
        <div class="detail-row">
            <span class="detail-label">Keterangan</span>
            <span class="detail-value"><?= esc($deskripsi) ?></span>
        </div>
        <?php endif; ?>

        <div class="detail-row">
            <span class="detail-label">Estimasi Tiba</span>
            <span class="detail-value">
                <span class="etd-badge">⏱ <?= esc($etd) ?></span>
            </span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Metode Pembayaran Kurir</span>
            <span class="detail-value">
                <?php if (! empty($is_cod)): ?>
                    <span style="background:#fff7ed; color:#c2410c; padding:4px 12px;
                                 border-radius:20px; font-size:0.82rem; font-weight:700;
                                 border:1px solid #fed7aa;">
                        💰 COD (Bayar di Tempat)
                    </span>
                <?php else: ?>
                    <span style="background:#f1f5f9; color:#64748b; padding:4px 12px;
                                 border-radius:20px; font-size:0.82rem; font-weight:600;">
                        Non-COD (Prepaid)
                    </span>
                <?php endif; ?>
            </span>
        </div>
    </div>

    <!-- ====================================================================
         RINGKASAN HARGA
    ==================================================================== -->
    <div class="price-summary">
        <div class="price-row">
            <span class="price-label">Harga Buku Memori</span>
            <span class="price-value">
                Rp <?= number_format($harga_desain, 0, ',', '.') ?>
            </span>
        </div>
        <div class="price-row">
            <span class="price-label">
                Ongkos Kirim (<?= esc($kurir) ?> <?= esc($layanan) ?>)
            </span>
            <span class="price-value">
                Rp <?= number_format($biaya, 0, ',', '.') ?>
            </span>
        </div>
        <div class="price-row total">
            <span>Total Pembayaran</span>
            <span>Rp <?= number_format($total_bayar, 0, ',', '.') ?></span>
        </div>
    </div>

    <!-- Kotak informasi untuk user -->
    <div class="info-box">
        ℹ️ Setelah konfirmasi, pesanan masuk dengan status <strong>Pending</strong>.
        Tim kami akan menghubungi kamu untuk proses pembayaran selanjutnya.
    </div>

    <!-- ====================================================================
         FORM KONFIRMASI
         Mengirim data ke Logistik::simpanPesanan() via POST.

         Semua data detail (kurir, harga, dll) dikirim sebagai hidden input
         karena tidak ada input yang bisa diubah user di halaman ini,
         kecuali field catatan.
    ==================================================================== -->
    <form action="<?= base_url('logistik/simpan-pesanan') ?>" method="post" id="formKonfirmasi">
        <?= csrf_field() ?>

        <!-- ================================================================
             HIDDEN INPUTS
             Meneruskan data yang sudah ada dari halaman sebelumnya.
             Kenapa tidak simpan di session?
             Hidden input lebih sederhana untuk alur 1 halaman ke halaman berikutnya.
             Session akan digunakan nanti jika alur lebih kompleks.
        ================================================================ -->
        <input type="hidden" name="biaya"            value="<?= (int) $biaya ?>">
        <input type="hidden" name="biaya_net"        value="<?= (int) ($biaya_net ?? 0) ?>">
        <input type="hidden" name="kurir"            value="<?= esc($kurir) ?>">
        <input type="hidden" name="layanan"          value="<?= esc($layanan) ?>">

        <!-- Kirim nama kota (string) untuk disimpan ke kolom kota_tujuan di DB -->
        <input type="hidden" name="kota_tujuan_nama" value="<?= esc($kota_tujuan) ?>">

        <!-- Kirim berat dalam gram untuk disimpan ke kolom berat di DB -->
        <input type="hidden" name="berat_gram"       value="<?= (int) $berat ?>">

        <input type="hidden" name="etd"              value="<?= esc($etd) ?>">
        <input type="hidden" name="is_cod"           value="<?= ! empty($is_cod) ? '1' : '0' ?>">

        <!-- ================================================================
             TEXTAREA CATATAN (satu-satunya input yang bisa diisi user)
        ================================================================ -->
        <div class="form-group">
            <label for="catatan">Catatan untuk Tim (Opsional)</label>
            <textarea
                name="catatan"
                id="catatan"
                maxlength="500"
                placeholder="Contoh: Tolong dibungkus rapi, ini hadiah ulang tahun...">
            </textarea>
            <div class="char-count" id="charCount">0 / 500 karakter</div>
        </div>

        <!-- Tombol aksi -->
        <div class="btn-group">
            <!-- Tombol kembali: ke halaman cek ongkir (BUKAN tombol back browser) -->
            <a href="<?= base_url('logistik/tesongkir') ?>" class="btn-kembali">
                ← Ganti Pilihan
            </a>

            <!-- Tombol konfirmasi: submit form ke simpanPesanan -->
            <button type="submit" class="btn-konfirmasi" id="btnKonfirmasi">
                <span class="btn-text">✨ Konfirmasi Pesanan</span>
                <span class="spinner"></span>
            </button>
        </div>

    </form>

</div><!-- /container -->


<script>
// ============================================================================
// JavaScript 1: Counter karakter textarea
// Memberi tahu user berapa karakter sudah diisi dari 500 maksimal.
// Saat mendekati batas (>450), warna counter berubah merah sebagai peringatan.
// ============================================================================

const catatan   = document.getElementById('catatan');
const charCount = document.getElementById('charCount');

catatan.addEventListener('input', function() {
    const panjang = this.value.length;
    charCount.textContent = panjang + ' / 500 karakter';
    // Tambah class 'warn' (warna merah) saat sudah >450 karakter
    charCount.classList.toggle('warn', panjang > 450);
});


// ============================================================================
// JavaScript 2: Loading state tombol konfirmasi
// Cegah user double-click yang bisa menghasilkan pesanan duplikat.
// Saat form disubmit, tombol langsung di-disable dan teks diganti spinner.
// ============================================================================

const formKonfirmasi = document.getElementById('formKonfirmasi');
const btnKonfirmasi  = document.getElementById('btnKonfirmasi');

formKonfirmasi.addEventListener('submit', function() {
    btnKonfirmasi.classList.add('loading');
    btnKonfirmasi.disabled = true;
});
</script>

</body>
</html>