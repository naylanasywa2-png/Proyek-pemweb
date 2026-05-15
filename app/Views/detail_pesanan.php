<!DOCTYPE html>
<html lang="id">
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
            min-height: 100vh; padding: 40px 20px; color: #555;
        }

        .container {
            background: white; padding: 40px;
            border-radius: 24px;
            box-shadow: 0 15px 40px rgba(255, 107, 149, 0.15);
            max-width: 680px; margin: auto;
        }

        /* Header */
        .page-header { text-align: center; margin-bottom: 32px; }
        .page-header .icon { font-size: 3rem; margin-bottom: 10px; }
        h2 { color: #ff4d7d; font-size: 1.7rem; font-weight: 800; margin-bottom: 6px; }
        .subtitle { color: #aaa; font-size: 0.88rem; }

        /* Step indicator */
        .steps {
            display: flex; align-items: center; justify-content: center;
            gap: 0; margin-bottom: 32px;
        }
        .step {
            display: flex; align-items: center; gap: 8px;
            font-size: 0.8rem; font-weight: 600;
        }
        .step-num {
            width: 28px; height: 28px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; font-weight: 700;
        }
        .step.done .step-num  { background: #ffdae3; color: #ff4d7d; }
        .step.done .step-label { color: #ff4d7d; }
        .step.active .step-num { background: #ff4d7d; color: white; }
        .step.active .step-label { color: #ff4d7d; font-weight: 700; }
        .step.pending .step-num { background: #f0f0f0; color: #bbb; }
        .step.pending .step-label { color: #bbb; }
        .step-line { width: 40px; height: 2px; background: #ffdae3; margin: 0 4px; }

        /* Detail card */
        .detail-card {
            background: #fff8fa;
            border: 1.5px solid #ffdae3;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 20px;
        }
        .detail-card h3 {
            color: #ff4d7d; font-size: 0.85rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.5px;
            margin-bottom: 16px; display: flex; align-items: center; gap: 8px;
        }
        .detail-row {
            display: flex; justify-content: space-between; align-items: center;
            padding: 10px 0; border-bottom: 1px solid #ffe4e9;
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { color: #888; font-size: 0.88rem; }
        .detail-value { color: #333; font-size: 0.92rem; font-weight: 600; text-align: right; }

        /* Badge */
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

        /* Ringkasan harga */
        .price-summary {
            background: linear-gradient(135deg, #fff0f3, #ffe4e9);
            border: 1.5px solid #ffb3cc;
            border-radius: 16px; padding: 20px 24px;
            margin-bottom: 20px;
        }
        .price-row {
            display: flex; justify-content: space-between;
            padding: 6px 0; font-size: 0.9rem;
        }
        .price-row.total {
            border-top: 2px solid #ffb3cc;
            margin-top: 10px; padding-top: 14px;
            font-size: 1.05rem; font-weight: 800; color: #ff4d7d;
        }
        .price-label { color: #888; }
        .price-value { font-weight: 600; color: #333; }

        /* Form catatan */
        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block; margin-bottom: 8px;
            color: #d63384; font-weight: 700;
            font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;
        }
        textarea {
            width: 100%; padding: 12px 14px;
            border: 2px solid #ffdae3; border-radius: 12px;
            font-family: inherit; font-size: 0.9rem; color: #555;
            resize: vertical; min-height: 80px; outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        textarea:focus { border-color: #ff85a2; box-shadow: 0 0 0 3px rgba(255,133,162,0.2); }
        .char-count { font-size: 0.75rem; color: #bbb; text-align: right; margin-top: 4px; }
        .char-count.warn { color: #ff4d7d; }

        /* Buttons */
        .btn-group { display: flex; gap: 12px; }
        .btn-back {
            flex: 1; padding: 14px; border-radius: 12px;
            border: 2px solid #ffdae3; background: white;
            color: #ff85a2; font-weight: 700; font-size: 0.95rem;
            font-family: inherit; cursor: pointer;
            transition: all 0.2s; text-decoration: none;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-back:hover { background: #fff5f7; border-color: #ff85a2; }
        .btn-confirm {
            flex: 2; padding: 14px; border-radius: 12px;
            background: linear-gradient(135deg, #ff85a2, #ff4d7d);
            color: white; font-weight: 700; font-size: 0.95rem;
            font-family: inherit; cursor: pointer; border: none;
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-confirm:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(255,77,125,0.35); }
        .btn-confirm:disabled { opacity: 0.7; cursor: not-allowed; transform: none; box-shadow: none; }
        .btn-confirm .spinner {
            display: none; width: 18px; height: 18px;
            border: 2px solid rgba(255,255,255,0.4); border-top-color: white;
            border-radius: 50%; animation: spin 0.7s linear infinite;
        }
        .btn-confirm.loading .spinner { display: block; }
        .btn-confirm.loading .btn-text { display: none; }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Info box */
        .info-box {
            background: #f0f4ff; border: 1px solid #b3c6ff;
            border-radius: 12px; padding: 14px 18px;
            font-size: 0.85rem; color: #3355cc; margin-bottom: 20px;
            line-height: 1.5;
        }

        /* Responsive */
        @media (max-width: 500px) {
            .container { padding: 24px 18px; }
            .btn-group { flex-direction: column-reverse; }
            .btn-back, .btn-confirm { flex: none; width: 100%; }
        }
    </style>
</head>
<body>
<div class="container">

    <div class="page-header">
        <div class="icon">🛍️</div>
        <h2>Konfirmasi Pesanan</h2>
        <p class="subtitle">Periksa detail sebelum memesan</p>
    </div>

    <!-- Step indicator -->
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

    <!-- Detail Pengiriman -->
    <div class="detail-card">
        <h3>📦 Detail Pengiriman</h3>

        <div class="detail-row">
            <span class="detail-label">Asal</span>
            <span class="detail-value">Surabaya - Genteng</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Tujuan</span>
            <span class="detail-value"><?= esc($kota_tujuan ?: '-') ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Berat</span>
            <span class="detail-value"><?= number_format($berat, 0, ',', '.') ?> gram (<?= number_format($berat / 1000, 2) ?> kg)</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Kurir & Layanan</span>
            <span class="detail-value">
                <span class="kurir-badge"><?= esc($kurir) ?></span>
                &nbsp;<?= esc($layanan) ?>
            </span>
        </div>
        <?php if (!empty($deskripsi)): ?>
        <div class="detail-row">
            <span class="detail-label">Deskripsi</span>
            <span class="detail-value"><?= esc($deskripsi) ?></span>
        </div>
        <?php endif; ?>
        <div class="detail-row">
            <span class="detail-label">Estimasi Tiba</span>
            <span class="detail-value">
                <span class="etd-badge">🕐 <?= esc($etd) ?></span>
            </span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Metode Pembayaran Kurir</span>
            <span class="detail-value">
                <?php if (!empty($is_cod)): ?>
                    <span style="background:#fff7ed;color:#c2410c;padding:4px 12px;border-radius:20px;font-size:0.82rem;font-weight:700;border:1px solid #fed7aa;">💰 COD (Bayar di Tempat)</span>
                <?php else: ?>
                    <span style="background:#f1f5f9;color:#64748b;padding:4px 12px;border-radius:20px;font-size:0.82rem;font-weight:600;">Non-COD (Prepaid)</span>
                <?php endif; ?>
            </span>
        </div>
    </div>

    <!-- Ringkasan Harga -->
    <div class="price-summary">
        <div class="price-row">
            <span class="price-label">Harga Buku Memori</span>
            <span class="price-value">Rp <?= number_format($harga_desain, 0, ',', '.') ?></span>
        </div>
        <div class="price-row">
            <span class="price-label">Ongkos Kirim (<?= esc($kurir) ?> <?= esc($layanan) ?>)</span>
            <span class="price-value">Rp <?= number_format($biaya, 0, ',', '.') ?></span>
        </div>
        <div class="price-row total">
            <span>Total Pembayaran</span>
            <span>Rp <?= number_format($total_bayar, 0, ',', '.') ?></span>
        </div>
    </div>

    <!-- Info -->
    <div class="info-box">
        ℹ️ Setelah konfirmasi, pesanan akan masuk dengan status <strong>Pending</strong>. 
        Tim kami akan menghubungi kamu untuk proses pembayaran selanjutnya.
    </div>

    <!-- Form konfirmasi -->
    <form action="<?= base_url('logistik/simpan-pesanan') ?>" method="post" id="formKonfirmasi">
        <?= csrf_field() ?>

        <!-- Hidden fields -->
        <input type="hidden" name="biaya"            value="<?= (int) $biaya ?>">
        <input type="hidden" name="biaya_net"        value="<?= (int) ($biaya_net ?? 0) ?>">
        <input type="hidden" name="kurir"            value="<?= esc($kurir) ?>">
        <input type="hidden" name="layanan"          value="<?= esc($layanan) ?>">
        <input type="hidden" name="kota_tujuan_id"   value="<?= (int) $kota_tujuan_id ?>">
        <input type="hidden" name="kota_tujuan_nama" value="<?= esc($kota_tujuan) ?>">
        <input type="hidden" name="berat_gram"       value="<?= (int) $berat ?>">
        <input type="hidden" name="etd"              value="<?= esc($etd) ?>">
        <input type="hidden" name="is_cod"           value="<?= !empty($is_cod) ? '1' : '0' ?>">

        <!-- Catatan opsional -->
        <div class="form-group">
            <label for="catatan">Catatan untuk Tim (Opsional)</label>
            <textarea name="catatan" id="catatan" maxlength="500"
                      placeholder="Contoh: Tolong dibungkus rapi, hadiah ulang tahun..."></textarea>
            <div class="char-count" id="charCount">0 / 500 karakter</div>
        </div>

        <div class="btn-group">
            <a href="<?= base_url('logistik/tesongkir') ?>" class="btn-back">
                ← Ganti Pilihan
            </a>
            <button type="submit" class="btn-confirm" id="btnKonfirmasi">
                <span class="btn-text">✨ Konfirmasi Pesanan</span>
                <span class="spinner"></span>
            </button>
        </div>
    </form>

</div>

<script>
// Karakter counter textarea
const catatan   = document.getElementById('catatan');
const charCount = document.getElementById('charCount');
catatan.addEventListener('input', function() {
    const len = this.value.length;
    charCount.textContent = len + ' / 500 karakter';
    charCount.classList.toggle('warn', len > 450);
});

// Loading state saat submit
const form = document.getElementById('formKonfirmasi');
const btn  = document.getElementById('btnKonfirmasi');
form.addEventListener('submit', function() {
    btn.classList.add('loading');
    btn.disabled = true;
});
</script>
</body>
</html>