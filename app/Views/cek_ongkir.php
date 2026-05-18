<!DOCTYPE html>
<html lang="id">
<!--
=================================================================================
FILE: app/Views/cek_ongkir.php
=================================================================================

FUNGSI FILE INI:
  Halaman utama modul ongkir. Menampilkan dua hal sekaligus:
    1. Form input (kota tujuan, berat, filter kurir)
    2. Tabel hasil ongkir (muncul setelah form disubmit)

VARIABEL YANG DITERIMA DARI CONTROLLER (Logistik::tesOngkir):
  $result       → array hasil cek ongkir, null jika belum submit
  $kota_list    → array daftar kota untuk dropdown
  $tujuan       → int ID kota yang terpilih (untuk set selected di dropdown)
  $tujuan_nama  → string nama kota yang terpilih
  $berat        → int berat dalam gram (untuk isi ulang form)
  $is_post      → bool apakah halaman ini hasil dari submit form?
  $errors       → array error validasi dari server ['field' => 'pesan']
  $filter_kurir → string kurir yang dipilih di filter

ALUR TAMPILAN:
  - $is_post = false → hanya tampilkan form
  - $is_post = true, $errors tidak kosong → tampilkan error
  - $is_post = true, $result ada data → tampilkan tabel hasil
  - $is_post = true, $result is_demo = true → tampilkan banner demo

FITUR JAVASCRIPT:
  1. Validasi form client-side (sebelum submit ke server)
  2. Filter kurir client-side (tanpa reload halaman, pakai toggle baris tabel)
  3. Loading state tombol submit (cegah double-click)
=================================================================================
-->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Ongkir - Digital Memories</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* ================================================================
           RESET & BASE
           Menghilangkan margin/padding bawaan browser agar tampilan
           konsisten di semua browser (Chrome, Firefox, Safari, Edge)
        ================================================================ */
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: linear-gradient(135deg, #fff5f7 0%, #ffe4e9 100%);
            font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
            min-height: 100vh;
            padding: 40px 20px;
            color: #555;
        }

        /* ================================================================
           CARD CONTAINER UTAMA
           Semua konten halaman ada di dalam kotak putih ini
        ================================================================ */
        .container {
            background: white;
            padding: 35px 40px;
            border-radius: 24px;
            box-shadow: 0 15px 40px rgba(255, 107, 149, 0.15);
            max-width: 960px;
            margin: auto;
        }

        /* ================================================================
           HEADER HALAMAN
        ================================================================ */
        .page-header { text-align: center; margin-bottom: 28px; }
        h2 { color: #ff4d7d; font-size: 1.8rem; font-weight: 800; margin-bottom: 6px; }
        .subtitle { color: #aaa; font-size: 0.9rem; }
        .subtitle strong { color: #ff4d7d; }

        /* ================================================================
           FORM GRID
           4 kolom: [Kota Tujuan] [Berat] [Filter Kurir] [Tombol]
           Di mobile otomatis jadi 1 kolom (via @media query)
        ================================================================ */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 140px 200px auto;
            gap: 14px;
            align-items: end; /* semua elemen sejajar di bagian bawah */
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            color: #d63384;
            font-weight: 700;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Style untuk select dan input number */
        select, input[type=number] {
            width: 100%;
            padding: 11px 14px;
            border: 2px solid #ffdae3;
            border-radius: 12px;
            outline: none;
            font-size: 0.92rem;
            font-family: inherit;
            transition: border-color 0.2s, box-shadow 0.2s;
            appearance: none;         /* hilangkan panah bawaan browser */
            background-color: white;
        }

        /* Panah custom untuk select */
        select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23ff85a2' stroke-width='2' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 34px;
        }

        /* Highlight input saat diklik */
        select:focus, input[type=number]:focus {
            border-color: #ff85a2;
            box-shadow: 0 0 0 3px rgba(255, 133, 162, 0.2);
        }

        /* Warna merah untuk field yang error */
        select.error, input.error { border-color: #ff4d7d !important; }

        /* Pesan error di bawah field */
        .field-error { color: #ff4d7d; font-size: 0.76rem; margin-top: 4px; }

        /* ================================================================
           TOMBOL CEK ONGKIR
        ================================================================ */
        .btn-cek {
            background: linear-gradient(135deg, #ff85a2, #ff4d7d);
            color: white;
            border: none;
            padding: 11px 22px;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 700;
            font-size: 0.92rem;
            font-family: inherit;
            white-space: nowrap;
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
            height: 46px;
        }
        .btn-cek:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(255, 77, 125, 0.4); }
        .btn-cek:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }

        /* Spinner loading yang muncul saat form disubmit */
        .spinner {
            display: none;
            width: 16px; height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.4);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }
        .btn-cek.loading .spinner { display: block; }
        .btn-cek.loading .btn-text { display: none; }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ================================================================
           ALERT / NOTIFIKASI
        ================================================================ */
        .alert {
            padding: 14px 18px;
            border-radius: 12px;
            margin-top: 16px;
            font-size: 0.9rem;
            line-height: 1.5;
        }
        .alert-error   { background: #fff0f3; color: #cc3366; border: 1px solid #ffb3cc; }
        .alert-demo    { background: #f0f4ff; color: #3355cc; border: 1px solid #b3c6ff; }
        .alert-success { background: #f0fff4; color: #16a34a; border: 1px solid #86efac; }
        .alert-demo code {
            background: #dde8ff; padding: 1px 6px;
            border-radius: 4px; font-size: 0.85rem;
        }

        /* Badge "DEMO" kecil */
        .demo-badge {
            background: #3355cc; color: white;
            padding: 2px 10px; border-radius: 20px;
            font-size: 0.72rem; font-weight: 700; letter-spacing: 1px;
        }

        /* ================================================================
           DIVIDER
        ================================================================ */
        .divider { border: none; border-top: 1px solid #ffe4e9; margin: 24px 0; }

        /* ================================================================
           AREA HASIL (result bar + tabel)
        ================================================================ */

        /* Bar di atas tabel: judul + jumlah layanan + filter pills */
        .result-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 14px;
        }
        .result-bar-left { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        .result-bar h3 { color: #ff4d7d; font-size: 1rem; font-weight: 700; }

        /* Badge jumlah layanan */
        .count-badge {
            background: #ffdae3; color: #ff4d7d;
            padding: 2px 10px; border-radius: 20px;
            font-size: 0.8rem; font-weight: 700;
        }

        /* ================================================================
           FILTER PILLS (tombol filter kurir di atas tabel)
           Bekerja secara client-side via JavaScript
        ================================================================ */
        .filter-pills { display: flex; gap: 8px; flex-wrap: wrap; }
        .pill {
            padding: 5px 14px;
            border-radius: 20px;
            border: 2px solid #ffdae3;
            background: white;
            color: #ff85a2;
            font-size: 0.78rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.15s;
            font-family: inherit;
        }
        .pill:hover { background: #fff5f7; }
        .pill.active { background: #ff4d7d; color: white; border-color: #ff4d7d; }

        /* ================================================================
           TABEL HASIL ONGKIR
        ================================================================ */
        .table-wrap { overflow-x: auto; } /* scroll horizontal di mobile */

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px; /* cegah tabel terlalu sempit */
        }

        /* Header tabel */
        thead tr { background: linear-gradient(135deg, #ff85a2, #ff4d7d); }
        th {
            color: white;
            padding: 13px 16px;
            text-align: left;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
        }
        /* Radius sudut header tabel */
        th:first-child { border-radius: 12px 0 0 12px; }
        th:last-child  { border-radius: 0 12px 12px 0; text-align: center; }

        /* Baris tabel */
        td {
            padding: 14px 16px;
            border-bottom: 1px solid #ffe4e9;
            font-size: 0.9rem;
            vertical-align: middle;
        }
        td:last-child { text-align: center; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #fff5f7; }

        /* Animasi tiap baris muncul secara berurutan */
        tbody tr { animation: fadeIn 0.3s ease both; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to   { opacity: 1; transform: none; }
        }

        /* ================================================================
           BADGE-BADGE DI DALAM TABEL
        ================================================================ */

        /* Badge nama kurir (merah muda) */
        .kurir-badge {
            background: #ffe4e9; color: #ff4d7d;
            padding: 4px 10px; border-radius: 20px;
            font-weight: 700; font-size: 0.76rem;
        }

        /* Harga utama */
        .price { color: #ff4d7d; font-weight: 800; font-size: 0.98rem; }

        /* Harga net (lebih kecil dan abu-abu) */
        .price-net { color: #aaa; font-size: 0.78rem; font-weight: 500; margin-top: 2px; }

        /* Badge estimasi waktu */
        .etd-badge {
            background: #f0fff4; color: #16a34a;
            padding: 4px 10px; border-radius: 20px;
            font-size: 0.78rem; font-weight: 600;
        }

        /* Badge COD */
        .cod-badge {
            background: #fff7ed; color: #c2410c;
            padding: 3px 8px; border-radius: 12px;
            font-size: 0.7rem; font-weight: 700;
            border: 1px solid #fed7aa;
        }

        /* Badge Non-COD */
        .non-cod-badge {
            background: #f1f5f9; color: #94a3b8;
            padding: 3px 8px; border-radius: 12px;
            font-size: 0.7rem; font-weight: 600;
        }

        /* ================================================================
           TOMBOL PILIH (di kolom terakhir tabel)
        ================================================================ */
        .btn-pilih {
            background: linear-gradient(135deg, #ff85a2, #ff4d7d);
            color: white;
            border: none;
            padding: 8px 18px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.8rem;
            font-weight: 700;
            font-family: inherit;
            transition: transform 0.15s, box-shadow 0.15s;
            white-space: nowrap;
        }
        .btn-pilih:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(255, 77, 125, 0.35);
        }

        /* ================================================================
           STATE KOSONG (ketika filter tidak ada hasil)
        ================================================================ */
        .no-result {
            text-align: center;
            padding: 40px 20px;
            color: #ccc;
        }
        .no-result .icon { font-size: 2.5rem; margin-bottom: 10px; }
        .no-result p { font-size: 0.9rem; }

        /* ================================================================
           RESPONSIVE - Di layar kecil, form jadi 2 kolom lalu 1 kolom
        ================================================================ */
        @media (max-width: 700px) {
            .container { padding: 24px 18px; }
            .form-grid { grid-template-columns: 1fr 1fr; }
            .form-grid .btn-group-col { grid-column: 1 / -1; } /* tombol full width */
        }
        @media (max-width: 480px) {
            .form-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<div class="container">

    <!-- ====================================================================
         HEADER
    ==================================================================== -->
    <div class="page-header">
        <h2>🌸 Cek Biaya Kirim Buku Memori</h2>
        <p class="subtitle">Asal pengiriman: <strong>Surabaya</strong></p>
    </div>

    <!-- ====================================================================
         FLASH MESSAGES
         session()->setFlashdata() di Controller akan muncul di sini.
         getFlashdata() hanya bisa dibaca sekali, setelah itu otomatis hilang.
    ==================================================================== -->
    <?php if ($sukses = session()->getFlashdata('sukses')): ?>
        <div class="alert alert-success">✅ <?= esc($sukses) ?></div>
    <?php endif; ?>

    <?php if ($error = session()->getFlashdata('error')): ?>
        <div class="alert alert-error">❌ <?= esc($error) ?></div>
    <?php endif; ?>

    <!-- ====================================================================
         FORM CEK ONGKIR
         action → URL tujuan submit (POST ke logistik/tesongkir)
         method → HTTP method POST
         novalidate → matikan validasi HTML5 bawaan, kita pakai JS sendiri
    ==================================================================== -->
    <form id="formOngkir" action="<?= base_url('logistik/tesongkir') ?>" method="post" novalidate>
        <?= csrf_field() ?>
        <!-- csrf_field() = input hidden berisi token CSRF bawaan CI4
             Fungsinya: membuktikan form ini dikirim dari halaman kita,
             bukan dari situs lain (proteksi CSRF attack) -->

        <div class="form-grid">

            <!-- KOLOM 1: Dropdown Kota Tujuan -->
            <div class="form-group">
                <label for="tujuan">Kota Tujuan</label>
                <select name="tujuan" id="tujuan" class="<?= isset($errors['tujuan']) ? 'error' : '' ?>">
                    <?php foreach ($kota_list as $kota): ?>
                        <!-- Tandai 'selected' untuk kota yang dipilih sebelumnya
                             Ini membuat form tidak reset setelah submit -->
                        <option value="<?= $kota['id'] ?>"
                            <?= ($tujuan == $kota['id']) ? 'selected' : '' ?>>
                            <?= esc($kota['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['tujuan'])): ?>
                    <div class="field-error" data-server="1">⚠️ <?= esc($errors['tujuan']) ?></div>
                <?php endif; ?>
            </div>

            <!-- KOLOM 2: Input Berat (dalam gram) -->
            <div class="form-group">
                <label for="berat">Berat (Gram)</label>
                <input type="number"
                       name="berat"
                       id="berat"
                       value="<?= esc($berat) ?>"
                       min="100"
                       max="30000"
                       step="100"
                       class="<?= isset($errors['berat']) ? 'error' : '' ?>"
                       placeholder="1000">
                <?php if (isset($errors['berat'])): ?>
                    <div class="field-error" data-server="1">⚠️ <?= esc($errors['berat']) ?></div>
                <?php endif; ?>
            </div>

            <!-- KOLOM 3: Filter Kurir (Server-side) -->
            <div class="form-group">
                <label for="filter_kurir">Filter Kurir</label>
                <select name="filter_kurir" id="filter_kurir">
                    <?php
                    // Daftar opsi filter dengan penandaan 'selected' sesuai pilihan sebelumnya
                    $filterOptions = [
                        'SEMUA'     => 'Semua Kurir',
                        'JNT'       => 'J&T Express',
                        'JNE'       => 'JNE',
                        'SICEPAT'   => 'SiCepat',
                        'IDEXPRESS' => 'ID Express',
                        'SAP'       => 'SAP Express',
                        'ANTERAJA'  => 'AnterAja',
                        'TIKI'      => 'TIKI',
                        'POS'       => 'POS Indonesia',
                    ];
                    foreach ($filterOptions as $val => $label):
                        $currentFilter = ($filter_kurir === '' || $filter_kurir === 'SEMUA')
                            ? 'SEMUA'
                            : $filter_kurir;
                        $selected = ($currentFilter === $val) ? 'selected' : '';
                    ?>
                        <option value="<?= $val ?>" <?= $selected ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- KOLOM 4: Tombol Submit -->
            <div class="form-group btn-group-col">
                <label>&nbsp;</label>
                <!-- Label kosong agar tombol sejajar dengan input di atas -->
                <button type="submit" class="btn-cek" id="btnCek">
                    <span class="btn-text">Cek Ongkir ✨</span>
                    <span class="spinner"></span>
                    <!-- Spinner hanya muncul saat class 'loading' ditambahkan via JS -->
                </button>
            </div>

        </div><!-- /form-grid -->
    </form>

    <!-- ====================================================================
         TAMPILKAN ERROR VALIDASI DARI SERVER
         Muncul hanya jika: sudah submit form ($is_post = true)
         DAN ada error validasi ($errors tidak kosong)
    ==================================================================== -->
    <?php if ($is_post && ! empty($errors)): ?>
        <div class="alert alert-error">
            ⚠️ <strong>Mohon perbaiki form di atas:</strong>
            <ul style="margin: 8px 0 0 18px;">
                <?php foreach ($errors as $err): ?>
                    <li><?= esc($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>


    <!-- ====================================================================
         AREA HASIL CEK ONGKIR
         Hanya muncul jika $result sudah ada (form sudah disubmit & valid)
    ==================================================================== -->
    <?php if (isset($result)): ?>

        <hr class="divider">

        <!-- Kasus: API / Service mengembalikan error -->
        <?php if ($result['status'] === 'error'): ?>
            <div class="alert alert-error">
                ❌ <strong>Gagal mendapatkan data ongkir:</strong>
                <?= esc($result['message']) ?>
            </div>

        <!-- Kasus: Ada data untuk ditampilkan -->
        <?php elseif (! empty($result['data'])): ?>

            <!-- Banner DEMO: muncul jika data bukan dari API asli -->
            <?php if (! empty($result['is_demo'])): ?>
                <div class="alert alert-demo">
                    🔧 <strong>Mode Demo</strong> — Data ini adalah simulasi harga realistis,
                    bukan dari API sesungguhnya. Aktifkan
                    <code>KOMERCE_SHIPPING_API_KEY</code> di file <code>.env</code>
                    untuk data ongkir yang akurat.
                </div>
            <?php endif; ?>

            <!-- Bar hasil: judul + jumlah + badge demo + pills filter -->
            <div class="result-bar">
                <div class="result-bar-left">
                    <h3>Hasil Pencarian</h3>
                    <!-- count-badge diupdate oleh JavaScript saat filter diklik -->
                    <span class="count-badge" id="countBadge">
                        <?= count($result['data']) ?> layanan
                    </span>
                    <?php if (! empty($result['is_demo'])): ?>
                        <span class="demo-badge">DEMO</span>
                    <?php endif; ?>
                </div>

                <!-- Pills filter kurir (client-side, tanpa reload halaman) -->
                <div class="filter-pills" id="filterPills">
                    <button class="pill active" data-filter="SEMUA">Semua</button>
                    <?php
                    // Kumpulkan nama kurir unik dari hasil untuk buat pill-nya
                    $kurirUnik = array_unique(
                        array_map(fn($i) => strtoupper($i['courier']), $result['data'])
                    );
                    foreach ($kurirUnik as $namaKurir):
                    ?>
                        <button class="pill" data-filter="<?= esc($namaKurir) ?>">
                            <?= esc($namaKurir) ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div><!-- /result-bar -->

            <!-- Tabel hasil ongkir -->
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Kurir</th>
                            <th>Layanan</th>
                            <th>Estimasi</th>
                            <th>COD</th>
                            <th>Biaya Kirim</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <?php foreach ($result['data'] as $i => $item):
                            // Bersihkan format ETD dari API
                            // Contoh: "3-5 days" → "3-5" → "3-5 Hari"
                            $etdBersih  = preg_replace('/\s*days?\s*/i', '', $item['estimated'] ?? '-');
                            $etdBersih  = trim($etdBersih, '- ');
                            $etdTampil  = ($etdBersih === '' || $etdBersih === '-')
                                ? 'N/A'
                                : $etdBersih . ' Hari';

                            $isCod    = ! empty($item['is_cod']);
                            $priceNet = (int) ($item['price_net'] ?? 0);
                        ?>
                        <tr
                            data-kurir="<?= strtoupper(esc($item['courier'])) ?>"
                            style="animation-delay: <?= $i * 0.04 ?>s">

                            <!-- Kolom Kurir -->
                            <td>
                                <span class="kurir-badge">
                                    <?= strtoupper(esc($item['courier'])) ?>
                                </span>
                            </td>

                            <!-- Kolom Layanan + Deskripsi -->
                            <td>
                                <strong><?= esc($item['service']) ?></strong>
                                <div style="color:#aaa; font-size:0.78rem; margin-top:2px">
                                    <?= esc($item['desc']) ?>
                                </div>
                            </td>

                            <!-- Kolom Estimasi -->
                            <td>
                                <span class="etd-badge">⏱ <?= esc($etdTampil) ?></span>
                            </td>

                            <!-- Kolom COD / Non-COD -->
                            <td>
                                <?php if ($isCod): ?>
                                    <span class="cod-badge">💰 COD</span>
                                <?php else: ?>
                                    <span class="non-cod-badge">Non-COD</span>
                                <?php endif; ?>
                            </td>

                            <!-- Kolom Harga -->
                            <td>
                                <div class="price">
                                    Rp <?= number_format($item['price'], 0, ',', '.') ?>
                                </div>
                                <!-- Tampilkan harga net hanya jika ada dan lebih kecil dari harga normal -->
                                <?php if ($priceNet > 0 && $priceNet < $item['price']): ?>
                                    <div class="price-net">
                                        Net: Rp <?= number_format($priceNet, 0, ',', '.') ?>
                                    </div>
                                <?php endif; ?>
                            </td>

                            <!-- Kolom Aksi: form POST ke detail-pesanan
                                 Kenapa pakai form, bukan link href?
                                 Karena data tarif dikirim via POST (bukan URL/GET)
                                 agar tidak bisa dimanipulasi lewat URL -->
                            <td>
                                <form action="<?= base_url('logistik/detail-pesanan') ?>" method="post">
                                    <?= csrf_field() ?>
                                    <!-- Data tarif yang dipilih dikirim sebagai hidden input -->
                                    <input type="hidden" name="biaya"       value="<?= (int) $item['price'] ?>">
                                    <input type="hidden" name="biaya_net"   value="<?= $priceNet ?>">
                                    <input type="hidden" name="kurir"       value="<?= esc($item['courier']) ?>">
                                    <input type="hidden" name="layanan"     value="<?= esc($item['service']) ?>">
                                    <input type="hidden" name="deskripsi"   value="<?= esc($item['desc']) ?>">
                                    <input type="hidden" name="etd"         value="<?= esc($etdTampil) ?>">
                                    <input type="hidden" name="is_cod"      value="<?= $isCod ? '1' : '0' ?>">
                                    <input type="hidden" name="kota_tujuan" value="<?= (int) $tujuan ?>">
                                    <input type="hidden" name="berat"       value="<?= (int) $berat ?>">
                                    <button type="submit" class="btn-pilih">Pilih →</button>
                                </form>
                            </td>

                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Pesan ini muncul via JS jika semua baris tersembunyi oleh filter -->
                <div class="no-result" id="noResult" style="display:none">
                    <div class="icon">🔍</div>
                    <p>Tidak ada layanan untuk kurir yang dipilih.</p>
                </div>
            </div><!-- /table-wrap -->

        <!-- Kasus: Sukses tapi tidak ada data (misal kota tidak terlayani) -->
        <?php elseif ($result['status'] === 'success' && empty($result['data'])): ?>
            <div class="alert alert-error">
                ⚠️ Tidak ada layanan pengiriman tersedia untuk rute ini.
                Coba pilih kota tujuan atau berat yang berbeda.
            </div>

        <?php endif; ?>

    <?php endif; ?>
    <!-- /isset($result) -->

</div><!-- /container -->


<script>
// ============================================================================
// JAVASCRIPT 1: Validasi Form Client-Side
// Tujuan: beri feedback cepat ke user SEBELUM request dikirim ke server.
// Server tetap memvalidasi ulang (di Controller) sebagai lapisan kedua.
// ============================================================================

const form       = document.getElementById('formOngkir');
const btnCek     = document.getElementById('btnCek');
const beratInput = document.getElementById('berat');

form.addEventListener('submit', function(e) {
    let valid = true;

    // Hapus error client-side yang mungkin masih ada dari submit sebelumnya
    // Tapi JANGAN hapus error yang berasal dari server (data-server="1")
    const errLama = beratInput.parentNode.querySelector('.field-error:not([data-server])');
    if (errLama) errLama.remove();

    const berat = parseInt(beratInput.value);

    if (! berat || berat < 100) {
        tampilkanError(beratInput, 'Berat minimal 100 gram.');
        valid = false;
    } else if (berat > 30000) {
        tampilkanError(beratInput, 'Berat maksimal 30.000 gram.');
        valid = false;
    } else {
        beratInput.classList.remove('error');
    }

    if (! valid) {
        e.preventDefault(); // batalkan submit
        return;
    }

    // Jika valid: tampilkan loading state agar user tahu request sedang diproses
    btnCek.classList.add('loading');
    btnCek.disabled = true;
});

// Helper: tampilkan pesan error di bawah field
function tampilkanError(input, pesan) {
    input.classList.add('error');
    input.focus();
    const div = document.createElement('div');
    div.className = 'field-error';
    div.textContent = '⚠️ ' + pesan;
    input.parentNode.appendChild(div);
}

// Hapus error saat user mulai mengetik di field berat
beratInput.addEventListener('input', function() {
    const v = parseInt(this.value);
    if (v >= 100 && v <= 30000) {
        this.classList.remove('error');
        const err = this.parentNode.querySelector('.field-error:not([data-server])');
        if (err) err.remove();
    }
});


// ============================================================================
// JAVASCRIPT 2: Filter Kurir Client-Side (via Pills)
// Menyembunyikan/menampilkan baris tabel tanpa reload halaman.
// Lebih cepat dari filter server-side karena tidak perlu request baru.
// ============================================================================

const pills      = document.querySelectorAll('.pill');
const barisTabel = document.querySelectorAll('#tableBody tr');
const noResult   = document.getElementById('noResult');
const countBadge = document.getElementById('countBadge');

// Jalankan hanya jika elemen-elemen di atas ditemukan (halaman sudah ada hasil)
if (pills.length > 0) {
    pills.forEach(function(pill) {
        pill.addEventListener('click', function() {
            // Nonaktifkan semua pill, aktifkan yang diklik
            pills.forEach(function(p) { p.classList.remove('active'); });
            this.classList.add('active');

            const filter = this.dataset.filter; // Ambil nilai data-filter
            let jumlahTampil = 0;

            // Toggle setiap baris berdasarkan nama kurir
            barisTabel.forEach(function(baris) {
                const kurirBaris = baris.dataset.kurir;
                const tampilkan  = (filter === 'SEMUA' || kurirBaris === filter);
                baris.style.display = tampilkan ? '' : 'none';
                if (tampilkan) jumlahTampil++;
            });

            // Update angka di count badge
            if (countBadge) {
                countBadge.textContent = jumlahTampil + ' layanan';
            }

            // Tampilkan pesan "tidak ada" jika semua baris tersembunyi
            if (noResult) {
                noResult.style.display = (jumlahTampil === 0) ? 'block' : 'none';
            }
        });
    });
}
</script>

</body>
</html>