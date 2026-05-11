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
            max-width: 900px; margin: auto;
        }

        /* Header */
        .page-header { text-align: center; margin-bottom: 30px; }
        h2 { color: #ff4d7d; font-size: 1.8rem; font-weight: 800; margin-bottom: 6px; }
        .subtitle { color: #aaa; font-size: 0.9rem; }
        .subtitle strong { color: #ff4d7d; }

        /* Form */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr auto; gap: 15px; align-items: end; }
        .form-group label {
            display: block; margin-bottom: 6px; color: #d63384;
            font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;
        }
        select, input[type=number] {
            width: 100%; padding: 11px 14px; border: 2px solid #ffdae3;
            border-radius: 12px; outline: none; font-size: 0.95rem;
            font-family: inherit; transition: border-color 0.2s, box-shadow 0.2s;
            appearance: none; background-color: white;
        }
        select { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23ff85a2' stroke-width='2' fill='none' stroke-linecap='round'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 14px center; padding-right: 36px; }
        select:focus, input[type=number]:focus { border-color: #ff85a2; box-shadow: 0 0 0 3px rgba(255,133,162,0.2); }

        .btn-cek {
            background: linear-gradient(135deg, #ff85a2, #ff4d7d); color: white;
            border: none; padding: 11px 24px; border-radius: 12px; cursor: pointer;
            font-weight: 700; font-size: 0.95rem; font-family: inherit;
            white-space: nowrap; transition: transform 0.2s, box-shadow 0.2s;
            display: flex; align-items: center; gap: 8px;
        }
        .btn-cek:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(255, 77, 125, 0.4); }
        .btn-cek:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }
        .btn-cek .spinner { display: none; width: 16px; height: 16px; border: 2px solid rgba(255,255,255,0.4); border-top-color: white; border-radius: 50%; animation: spin 0.7s linear infinite; }
        .btn-cek.loading .spinner { display: block; }
        .btn-cek.loading .btn-text { display: none; }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Alert */
        .alert { padding: 14px 18px; border-radius: 12px; margin-top: 25px; font-size: 0.9rem; line-height: 1.5; }
        .alert-error   { background: #fff0f3; color: #cc3366; border: 1px solid #ffb3cc; }
        .alert-demo    { background: #f0f4ff; color: #3355cc; border: 1px solid #b3c6ff; }
        .alert-demo a  { color: #3355cc; font-weight: 600; }
        .alert-demo code { background: #dde8ff; padding: 1px 6px; border-radius: 4px; font-size: 0.85rem; }
        .alert-success { background: #f0fff4; color: #16a34a; border: 1px solid #86efac; }
        .demo-badge { background: #3355cc; color: white; padding: 2px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; letter-spacing: 1px; }

        /* Divider */
        .divider { border: none; border-top: 1px solid #ffe4e9; margin: 25px 0; }

        /* Result header */
        .result-header { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; flex-wrap: wrap; }
        .result-header h3 { color: #ff4d7d; font-size: 1rem; font-weight: 700; }
        .result-header .count { background: #ffdae3; color: #ff4d7d; padding: 2px 10px; border-radius: 20px; font-size: 0.82rem; font-weight: 700; }

        /* Tabel */
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: linear-gradient(135deg, #ff85a2, #ff4d7d); }
        th { color: white; padding: 13px 16px; text-align: left; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700; }
        th:first-child { border-radius: 12px 0 0 12px; }
        th:last-child  { border-radius: 0 12px 12px 0; text-align: center; }
        td { padding: 14px 16px; border-bottom: 1px solid #ffe4e9; font-size: 0.92rem; vertical-align: middle; }
        td:last-child { text-align: center; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background-color: #fff8f9; }
        .kurir-badge { background: #ffe4e9; color: #ff4d7d; padding: 4px 10px; border-radius: 20px; font-weight: 700; font-size: 0.78rem; }
        .price { color: #ff4d7d; font-weight: 800; font-size: 1rem; }
        .etd-badge { background: #f0fff4; color: #16a34a; padding: 4px 10px; border-radius: 20px; font-size: 0.78rem; font-weight: 600; }
        .btn-pilih {
            background: linear-gradient(135deg, #ff85a2, #ff4d7d); color: white;
            border: none; padding: 8px 18px; border-radius: 8px; cursor: pointer;
            font-size: 0.82rem; font-weight: 700; font-family: inherit;
            transition: transform 0.15s, box-shadow 0.15s;
        }
        .btn-pilih:hover { transform: scale(1.06); box-shadow: 0 4px 12px rgba(255,77,125,0.35); }

        /* Validation error */
        .field-error { color: #cc3366; font-size: 0.78rem; margin-top: 4px; display: none; }
        input.invalid, select.invalid { border-color: #ff4d7d; }
    </style>
</head>
<body>
<div class="container">

    <div class="page-header">
        <h2>🌸 Cek Biaya Kirim Buku Memori</h2>
        <p class="subtitle">Asal pengiriman: <strong>Surabaya</strong></p>
    </div>

    <?php if (session()->getFlashdata('sukses')): ?>
    <div class="alert alert-success">
        ✅ <?= session()->getFlashdata('sukses') ?>
    </div>
    <?php endif; ?>

    <form id="formOngkir" action="<?= base_url('logistik/tesongkir') ?>" method="post" novalidate>
        <?= csrf_field() ?>
        <div class="form-grid">
            <div class="form-group">
                <label>Kota Tujuan</label>
                <select name="tujuan" id="tujuan">
                    <?php foreach ($kota_list as $kota): ?>
                        <option value="<?= $kota['id'] ?>" <?= ($tujuan == $kota['id']) ? 'selected' : '' ?>>
                            <?= esc($kota['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Berat (Gram)</label>
                <input type="number" name="berat" id="berat" value="<?= esc($berat) ?>" min="100" max="30000" step="100">
                <span class="field-error" id="berat-error">Berat minimal 100 gram</span>
            </div>
            <div class="form-group">
                <label>&nbsp;</label>
                <button type="submit" class="btn-cek" id="btnCek">
                    <span class="btn-text">Cek Ongkir ✨</span>
                    <span class="spinner"></span>
                </button>
            </div>
        </div>
    </form>

    <?php if (isset($result)): ?>
        <hr class="divider">

        <?php if ($result['status'] === 'error'): ?>
            <div class="alert alert-error">
                ❌ <strong>Oops!</strong> <?= esc($result['message']) ?>
            </div>

        <?php elseif (!empty($result['data'])): ?>
            <?php if (!empty($result['is_demo'])): ?>
            <div class="alert alert-demo">
                🔧 <strong>Mode Demo:</strong> Data di bawah adalah simulasi harga realistis.
                Aktifkan Shipping Cost API di dashboard
                <a href="https://collaborator.komerce.id" target="_blank">Komerce</a>
                dan pastikan API Key di <code>.env</code> sudah aktif.
            </div>
            <?php endif; ?>

            <div class="result-header">
                <h3>Hasil Pencarian</h3>
                <span class="count"><?= count($result['data']) ?> layanan</span>
                <?php if (!empty($result['is_demo'])): ?>
                    <span class="demo-badge">DEMO</span>
                <?php endif; ?>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Kurir</th>
                        <th>Layanan</th>
                        <th>Deskripsi</th>
                        <th>Estimasi</th>
                        <th>Biaya</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($result['data'] as $item): ?>
                    <tr>
                        <td><span class="kurir-badge"><?= strtoupper(esc($item['courier'])) ?></span></td>
                        <td><strong><?= esc($item['service']) ?></strong></td>
                        <td><?= esc($item['desc']) ?></td>
                        <td><span class="etd-badge">🕐 <?= esc($item['estimated']) ?> Hari</span></td>
                        <td class="price">Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                        <td>
                            <form action="<?= base_url('logistik/simpan-pesanan') ?>" method="post">
                                <?= csrf_field() ?>
                                <input type="hidden" name="biaya"        value="<?= (int) $item['price'] ?>">
                                <input type="hidden" name="kurir"        value="<?= esc($item['courier']) ?>">
                                <input type="hidden" name="layanan"      value="<?= esc($item['service']) ?>">
                                <input type="hidden" name="kota_tujuan"  value="<?= esc($tujuan) ?>">
                                <input type="hidden" name="berat"        value="<?= esc($berat) ?>">
                                <button type="submit" class="btn-pilih">Pilih ✨</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>

</div>

<script>
// Loading spinner saat form disubmit
const form = document.getElementById('formOngkir');
const btn  = document.getElementById('btnCek');
const beratInput = document.getElementById('berat');
const beratError = document.getElementById('berat-error');

form.addEventListener('submit', function(e) {
    const berat = parseInt(beratInput.value);

    // Validasi berat
    if (!berat || berat < 100) {
        e.preventDefault();
        beratInput.classList.add('invalid');
        beratError.style.display = 'block';
        beratInput.focus();
        return;
    }

    beratInput.classList.remove('invalid');
    beratError.style.display = 'none';

    // Tampilkan loading spinner
    btn.classList.add('loading');
    btn.disabled = true;
});

// Clear validasi saat user mengetik
beratInput.addEventListener('input', function() {
    if (this.value >= 100) {
        this.classList.remove('invalid');
        beratError.style.display = 'none';
    }
});
</script>
</body>
</html>