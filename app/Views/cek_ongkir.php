<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Ongkir - Digital Memories</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background: linear-gradient(135deg, #fff5f7 0%, #ffe4e9 100%);
            font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
            min-height: 100vh; padding: 40px 20px; color: #555;
        }

        .container {
            background: white; padding: 35px 40px; border-radius: 24px;
            box-shadow: 0 15px 40px rgba(255, 107, 149, 0.15);
            max-width: 950px; margin: auto;
        }

        /* Header */
        .page-header { text-align: center; margin-bottom: 28px; }
        h2 { color: #ff4d7d; font-size: 1.8rem; font-weight: 800; margin-bottom: 6px; }
        .subtitle { color: #aaa; font-size: 0.9rem; }
        .subtitle strong { color: #ff4d7d; }

        /* Form grid */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 130px 200px auto;
            gap: 14px; align-items: end;
        }
        .form-group label {
            display: block; margin-bottom: 6px;
            color: #d63384; font-weight: 700;
            font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.5px;
        }
        select, input[type=number] {
            width: 100%; padding: 11px 14px;
            border: 2px solid #ffdae3; border-radius: 12px;
            outline: none; font-size: 0.92rem; font-family: inherit;
            transition: border-color 0.2s, box-shadow 0.2s;
            appearance: none; background-color: white;
        }
        select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23ff85a2' stroke-width='2' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 12px center; padding-right: 34px;
        }
        select:focus, input[type=number]:focus {
            border-color: #ff85a2; box-shadow: 0 0 0 3px rgba(255,133,162,0.2);
        }
        select.error, input.error { border-color: #ff4d7d !important; }

        /* Error message */
        .field-error { color: #ff4d7d; font-size: 0.76rem; margin-top: 4px; }

        /* Button cek */
        .btn-cek {
            background: linear-gradient(135deg, #ff85a2, #ff4d7d); color: white;
            border: none; padding: 11px 22px; border-radius: 12px; cursor: pointer;
            font-weight: 700; font-size: 0.92rem; font-family: inherit;
            white-space: nowrap; transition: transform 0.2s, box-shadow 0.2s;
            display: flex; align-items: center; gap: 8px; height: 44px;
        }
        .btn-cek:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(255,77,125,0.4); }
        .btn-cek:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }
        .btn-cek .spinner {
            display: none; width: 16px; height: 16px;
            border: 2px solid rgba(255,255,255,0.4); border-top-color: white;
            border-radius: 50%; animation: spin 0.7s linear infinite;
        }
        .btn-cek.loading .spinner { display: block; }
        .btn-cek.loading .btn-text { display: none; }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Alert */
        .alert { padding: 14px 18px; border-radius: 12px; margin-top: 20px; font-size: 0.9rem; line-height: 1.5; }
        .alert-error   { background: #fff0f3; color: #cc3366; border: 1px solid #ffb3cc; }
        .alert-demo    { background: #f0f4ff; color: #3355cc; border: 1px solid #b3c6ff; }
        .alert-success { background: #f0fff4; color: #16a34a; border: 1px solid #86efac; }
        .alert-demo code { background: #dde8ff; padding: 1px 6px; border-radius: 4px; font-size: 0.85rem; }
        .demo-badge {
            background: #3355cc; color: white;
            padding: 2px 10px; border-radius: 20px;
            font-size: 0.72rem; font-weight: 700; letter-spacing: 1px;
        }

        /* Divider */
        .divider { border: none; border-top: 1px solid #ffe4e9; margin: 24px 0; }

        /* Result header + filter */
        .result-bar {
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 12px; margin-bottom: 14px;
        }
        .result-bar-left { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        .result-bar h3 { color: #ff4d7d; font-size: 1rem; font-weight: 700; }
        .count-badge { background: #ffdae3; color: #ff4d7d; padding: 2px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; }

        /* Filter kurir pills */
        .filter-pills { display: flex; gap: 8px; flex-wrap: wrap; }
        .pill {
            padding: 5px 14px; border-radius: 20px; border: 2px solid #ffdae3;
            background: white; color: #ff85a2; font-size: 0.78rem; font-weight: 700;
            cursor: pointer; transition: all 0.15s; font-family: inherit;
        }
        .pill:hover { background: #fff5f7; }
        .pill.active { background: #ff4d7d; color: white; border-color: #ff4d7d; }

        /* Tabel */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 600px; }
        thead tr { background: linear-gradient(135deg, #ff85a2, #ff4d7d); }
        th {
            color: white; padding: 13px 16px; text-align: left;
            font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700;
        }
        th:first-child { border-radius: 12px 0 0 12px; }
        th:last-child  { border-radius: 0 12px 12px 0; text-align: center; }
        td { padding: 14px 16px; border-bottom: 1px solid #ffe4e9; font-size: 0.9rem; vertical-align: middle; }
        td:last-child { text-align: center; }
        tr:last-child td { border-bottom: none; }
        tr.row-highlight { background: #fff8f9; }
        tr:hover td { background: #fff5f7; }

        /* Row animation */
        tbody tr { animation: fadeIn 0.3s ease both; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: none; } }

        .kurir-badge {
            background: #ffe4e9; color: #ff4d7d;
            padding: 4px 10px; border-radius: 20px;
            font-weight: 700; font-size: 0.76rem;
        }
        .price { color: #ff4d7d; font-weight: 800; font-size: 0.98rem; }
        .price-net { color: #aaa; font-size: 0.78rem; font-weight: 500; margin-top: 2px; }
        .etd-badge {
            background: #f0fff4; color: #16a34a;
            padding: 4px 10px; border-radius: 20px;
            font-size: 0.78rem; font-weight: 600;
        }
        .cod-badge {
            background: #fff7ed; color: #c2410c;
            padding: 3px 8px; border-radius: 12px;
            font-size: 0.7rem; font-weight: 700; letter-spacing: 0.3px;
            border: 1px solid #fed7aa;
        }
        .non-cod-badge {
            background: #f1f5f9; color: #94a3b8;
            padding: 3px 8px; border-radius: 12px;
            font-size: 0.7rem; font-weight: 600;
        }
        .btn-detail {
            background: linear-gradient(135deg, #ff85a2, #ff4d7d); color: white;
            border: none; padding: 8px 18px; border-radius: 8px; cursor: pointer;
            font-size: 0.8rem; font-weight: 700; font-family: inherit;
            transition: transform 0.15s, box-shadow 0.15s; white-space: nowrap;
        }
        .btn-detail:hover { transform: scale(1.05); box-shadow: 0 4px 12px rgba(255,77,125,0.35); }

        /* No result */
        .no-result {
            text-align: center; padding: 40px 20px; color: #ccc;
        }
        .no-result .icon { font-size: 2.5rem; margin-bottom: 10px; }
        .no-result p { font-size: 0.9rem; }

        /* Responsive */
        @media (max-width: 700px) {
            .container { padding: 24px 18px; }
            .form-grid { grid-template-columns: 1fr 1fr; }
            .form-grid .btn-group-col { grid-column: 1 / -1; }
        }
        @media (max-width: 480px) {
            .form-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<div class="container">

    <div class="page-header">
        <h2>🌸 Cek Biaya Kirim Buku Memori</h2>
        <p class="subtitle">Asal pengiriman: <strong>Surabaya</strong></p>
    </div>

    <!-- Flash messages -->
    <?php if (session()->getFlashdata('sukses')): ?>
        <div class="alert alert-success">✅ <?= esc(session()->getFlashdata('sukses')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error">❌ <?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <!-- Form -->
    <form id="formOngkir" action="<?= base_url('logistik/tesongkir') ?>" method="post" novalidate>
        <?= csrf_field() ?>

        <div class="form-grid">
            <!-- Kota Tujuan -->
            <div class="form-group">
                <label for="tujuan">Kota Tujuan</label>
                <select name="tujuan" id="tujuan" class="<?= isset($errors['tujuan']) ? 'error' : '' ?>">
                    <?php foreach ($kota_list as $kota): ?>
                        <option value="<?= $kota['id'] ?>" <?= ($tujuan == $kota['id']) ? 'selected' : '' ?>>
                            <?= esc($kota['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['tujuan'])): ?>
                    <div class="field-error">⚠️ <?= esc($errors['tujuan']) ?></div>
                <?php endif; ?>
            </div>

            <!-- Berat -->
            <div class="form-group">
                <label for="berat">Berat (Gram)</label>
                <input type="number" name="berat" id="berat"
                       value="<?= esc($berat) ?>"
                       min="100" max="30000" step="100"
                       class="<?= isset($errors['berat']) ? 'error' : '' ?>"
                       placeholder="1000">
                <?php if (isset($errors['berat'])): ?>
                    <div class="field-error">⚠️ <?= esc($errors['berat']) ?></div>
                <?php endif; ?>
            </div>

            <!-- Filter Kurir -->
            <div class="form-group">
                <label for="filter_kurir">Filter Kurir</label>
                <select name="filter_kurir" id="filter_kurir">
                    <option value="SEMUA"    <?= ($filter_kurir === '' || $filter_kurir === 'SEMUA') ? 'selected' : '' ?>>Semua Kurir</option>
                    <option value="JNT"      <?= ($filter_kurir === 'JNT')      ? 'selected' : '' ?>>J&T Express</option>
                    <option value="JNE"      <?= ($filter_kurir === 'JNE')      ? 'selected' : '' ?>>JNE</option>
                    <option value="SICEPAT"  <?= ($filter_kurir === 'SICEPAT')  ? 'selected' : '' ?>>SiCepat</option>
                    <option value="IDEXPRESS"<?= ($filter_kurir === 'IDEXPRESS')? 'selected' : '' ?>>ID Express</option>
                    <option value="SAP"      <?= ($filter_kurir === 'SAP')      ? 'selected' : '' ?>>SAP Express</option>
                    <option value="ANTERAJA" <?= ($filter_kurir === 'ANTERAJA') ? 'selected' : '' ?>>AnterAja</option>
                    <option value="TIKI"     <?= ($filter_kurir === 'TIKI')     ? 'selected' : '' ?>>TIKI</option>
                    <option value="POS"      <?= ($filter_kurir === 'POS')      ? 'selected' : '' ?>>POS Indonesia</option>
                </select>
            </div>

            <!-- Tombol Cek -->
            <div class="form-group btn-group-col">
                <label>&nbsp;</label>
                <button type="submit" class="btn-cek" id="btnCek">
                    <span class="btn-text">Cek Ongkir ✨</span>
                    <span class="spinner"></span>
                </button>
            </div>
        </div>
    </form>

    <?php if ($is_post && !empty($errors)): ?>
        <div class="alert alert-error">
            ⚠️ <strong>Mohon perbaiki form di atas:</strong>
            <ul style="margin: 8px 0 0 18px;">
                <?php foreach ($errors as $err): ?>
                    <li><?= esc($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (isset($result)): ?>
        <hr class="divider">

        <?php if ($result['status'] === 'error'): ?>
            <div class="alert alert-error">
                ❌ <strong>Gagal mendapatkan data ongkir:</strong> <?= esc($result['message']) ?>
            </div>

        <?php elseif (!empty($result['data'])): ?>

            <?php if (!empty($result['is_demo'])): ?>
                <div class="alert alert-demo">
                    🔧 <strong>Mode Demo</strong> — Data ini adalah simulasi harga realistis.
                    Aktifkan API Key Komerce di dashboard
                    <a href="https://collaborator.komerce.id" target="_blank">collaborator.komerce.id</a>
                    dan pastikan variabel <code>KOMERCE_SHIPPING_API_KEY</code> di <code>.env</code> sudah aktif.
                </div>
            <?php endif; ?>

            <div class="result-bar">
                <div class="result-bar-left">
                    <h3>Hasil Pencarian</h3>
                    <span class="count-badge" id="countBadge"><?= count($result['data']) ?> layanan</span>
                    <?php if (!empty($result['is_demo'])): ?>
                        <span class="demo-badge">DEMO</span>
                    <?php endif; ?>
                </div>
                <!-- Filter pills (client-side cepat) -->
                <div class="filter-pills" id="filterPills">
                    <button class="pill active" data-filter="SEMUA">Semua</button>
                    <?php
                    $kurirList = array_unique(array_map(fn($i) => strtoupper($i['courier']), $result['data']));
                    foreach ($kurirList as $k): ?>
                        <button class="pill" data-filter="<?= esc($k) ?>"><?= esc($k) ?></button>
                    <?php endforeach; ?>
                </div>
            </div>

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
                            // Bersihkan format ETD dari API ("9-12 day" → "9-12")
                            $etdClean = preg_replace('/\s*days?\s*/i', '', $item['estimated'] ?? '-');
                            $etdClean = trim($etdClean, '- ');
                            $etdDisplay = ($etdClean === '' || $etdClean === '-') ? 'N/A' : $etdClean . ' Hari';
                            $isCod = !empty($item['is_cod']);
                            $priceNet = (int)($item['price_net'] ?? 0);
                        ?>
                        <tr data-kurir="<?= strtoupper(esc($item['courier'])) ?>"
                            style="animation-delay: <?= $i * 0.05 ?>s">
                            <td>
                                <span class="kurir-badge"><?= strtoupper(esc($item['courier'])) ?></span>
                            </td>
                            <td>
                                <strong><?= esc($item['service']) ?></strong>
                                <div style="color:#aaa; font-size:0.78rem; margin-top:2px"><?= esc($item['desc']) ?></div>
                            </td>
                            <td>
                                <span class="etd-badge">🕐 <?= esc($etdDisplay) ?></span>
                            </td>
                            <td>
                                <?php if ($isCod): ?>
                                    <span class="cod-badge">💰 COD</span>
                                <?php else: ?>
                                    <span class="non-cod-badge">Non-COD</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="price">Rp <?= number_format($item['price'], 0, ',', '.') ?></div>
                                <?php if ($priceNet > 0 && $priceNet < $item['price']): ?>
                                    <div class="price-net">Net: Rp <?= number_format($priceNet, 0, ',', '.') ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <form action="<?= base_url('logistik/detail-pesanan') ?>" method="post">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="biaya"       value="<?= (int) $item['price'] ?>">
                                    <input type="hidden" name="biaya_net"   value="<?= $priceNet ?>">
                                    <input type="hidden" name="kurir"       value="<?= esc($item['courier']) ?>">
                                    <input type="hidden" name="layanan"     value="<?= esc($item['service']) ?>">
                                    <input type="hidden" name="deskripsi"   value="<?= esc($item['desc']) ?>">
                                    <input type="hidden" name="etd"         value="<?= esc($etdDisplay) ?>">
                                    <input type="hidden" name="is_cod"      value="<?= $isCod ? '1' : '0' ?>">
                                    <input type="hidden" name="kota_tujuan" value="<?= (int) $tujuan ?>">
                                    <input type="hidden" name="berat"       value="<?= (int) $berat ?>">
                                    <button type="submit" class="btn-detail">Pilih →</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="no-result" id="noResult" style="display:none">
                    <div class="icon">🔍</div>
                    <p>Tidak ada layanan untuk kurir ini.</p>
                </div>
            </div>

        <?php elseif ($result['status'] === 'success' && empty($result['data'])): ?>
            <div class="alert alert-error">
                ⚠️ Tidak ada layanan pengiriman yang tersedia untuk rute ini.
                Coba ganti kota tujuan atau berat.
            </div>
        <?php endif; ?>
    <?php endif; ?>

</div>

<script>
// ---- Validasi form client-side ----
const form      = document.getElementById('formOngkir');
const btnCek    = document.getElementById('btnCek');
const beratInput = document.getElementById('berat');

form.addEventListener('submit', function(e) {
    let valid = true;

    // Validasi berat
    const berat = parseInt(beratInput.value);
    const existingErr = beratInput.parentNode.querySelector('.field-error');
    if (existingErr && !existingErr.dataset.server) existingErr.remove();

    if (!berat || berat < 100) {
        const err = document.createElement('div');
        err.className = 'field-error';
        err.textContent = '⚠️ Berat minimal 100 gram.';
        beratInput.parentNode.appendChild(err);
        beratInput.classList.add('error');
        beratInput.focus();
        valid = false;
    } else if (berat > 30000) {
        const err = document.createElement('div');
        err.className = 'field-error';
        err.textContent = '⚠️ Berat maksimal 30.000 gram.';
        beratInput.parentNode.appendChild(err);
        beratInput.classList.add('error');
        beratInput.focus();
        valid = false;
    } else {
        beratInput.classList.remove('error');
    }

    if (!valid) { e.preventDefault(); return; }

    // Loading state
    btnCek.classList.add('loading');
    btnCek.disabled = true;
});

beratInput.addEventListener('input', function() {
    const v = parseInt(this.value);
    if (v >= 100 && v <= 30000) {
        this.classList.remove('error');
        const err = this.parentNode.querySelector('.field-error:not([data-server])');
        if (err) err.remove();
    }
});

// ---- Filter pills client-side ----
const pills     = document.querySelectorAll('.pill');
const rows      = document.querySelectorAll('#tableBody tr');
const noResult  = document.getElementById('noResult');
const countBadge = document.getElementById('countBadge');

pills.forEach(pill => {
    pill.addEventListener('click', function() {
        pills.forEach(p => p.classList.remove('active'));
        this.classList.add('active');

        const filter = this.dataset.filter;
        let visible = 0;

        rows.forEach(row => {
            const kurir = row.dataset.kurir;
            const show  = (filter === 'SEMUA' || kurir === filter);
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        countBadge.textContent = visible + ' layanan';
        noResult.style.display = (visible === 0) ? 'block' : 'none';
    });
});
</script>
</body>
</html>