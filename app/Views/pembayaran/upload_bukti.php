<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Bukti Pembayaran - Digital Memories</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #fff5f7 0%, #ffe4e9 100%);
            min-height: 100vh; padding: 40px 20px; color: #555;
        }
        .container {
            background: white; border-radius: 24px; padding: 40px;
            max-width: 640px; margin: auto;
            box-shadow: 0 15px 40px rgba(255,77,125,0.15);
        }
        .page-header { text-align: center; margin-bottom: 28px; }
        .page-header .icon { font-size: 3rem; margin-bottom: 10px; }
        h2 { color: #ff4d7d; font-size: 1.6rem; font-weight: 800; margin-bottom: 6px; }
        .subtitle { color: #aaa; font-size: 0.88rem; }

        .alert { padding: 13px 18px; border-radius: 12px; margin-bottom: 20px; font-size: 0.9rem; }
        .alert-error   { background: #fff0f3; color: #cc3366; border: 1px solid #ffb3cc; }
        .alert-success { background: #f0fff4; color: #16a34a; border: 1px solid #86efac; }
        .alert-warning { background: #fffbeb; color: #92400e; border: 1px solid #fcd34d; }

        /* Detail pesanan */
        .order-info {
            background: #fff8fa; border: 1.5px solid #ffdae3; border-radius: 16px;
            padding: 18px 22px; margin-bottom: 24px;
        }
        .order-info h3 { color: #ff4d7d; font-size: 0.82rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 14px; }
        .info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #ffe4e9; font-size: 0.88rem; }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #888; }
        .info-value { font-weight: 600; color: #333; }
        .total-row .info-value { color: #ff4d7d; font-size: 1.05rem; font-weight: 800; }

        /* Form */
        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block; margin-bottom: 7px;
            color: #d63384; font-weight: 700; font-size: 0.78rem;
            text-transform: uppercase; letter-spacing: 0.5px;
        }
        select, textarea {
            width: 100%; padding: 12px 14px;
            border: 2px solid #ffdae3; border-radius: 12px;
            font-size: 0.93rem; font-family: inherit; outline: none; color: #333;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        select:focus, textarea:focus { border-color: #ff85a2; box-shadow: 0 0 0 3px rgba(255,133,162,0.2); }
        textarea { resize: vertical; min-height: 80px; }

        /* Dropzone */
        .dropzone {
            border: 2.5px dashed #ffdae3; border-radius: 16px; padding: 36px 20px;
            text-align: center; cursor: pointer; transition: all 0.2s;
            position: relative;
        }
        .dropzone:hover, .dropzone.drag-over { border-color: #ff85a2; background: #fff5f7; }
        .dropzone input[type=file] {
            position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
        }
        .dropzone .dz-icon { font-size: 2.5rem; margin-bottom: 10px; }
        .dropzone h4 { color: #ff4d7d; font-weight: 700; font-size: 0.95rem; margin-bottom: 6px; }
        .dropzone p { color: #bbb; font-size: 0.8rem; }
        .preview-area { margin-top: 14px; display: none; }
        .preview-area img { max-width: 100%; max-height: 200px; border-radius: 10px; border: 2px solid #ffdae3; }
        .preview-area .pdf-preview {
            background: #fff0f3; border: 1.5px solid #ffdae3; border-radius: 12px;
            padding: 16px; color: #ff4d7d; font-weight: 700; font-size: 0.9rem;
        }
        .file-info { font-size: 0.78rem; color: #888; margin-top: 8px; }

        /* Sudah ada pembayaran */
        .existing-payment {
            background: #fffbeb; border: 1.5px solid #fcd34d; border-radius: 14px;
            padding: 16px 18px; margin-bottom: 20px;
        }
        .existing-payment p { font-size: 0.88rem; color: #92400e; font-weight: 600; }
        .existing-payment small { font-size: 0.78rem; color: #a16207; display: block; margin-top: 4px; }

        /* Buttons */
        .btn-group { display: flex; gap: 12px; margin-top: 8px; }
        .btn-back {
            flex: 1; padding: 13px; border-radius: 12px; text-align: center;
            border: 2px solid #ffdae3; background: white; color: #ff85a2;
            font-weight: 700; font-size: 0.93rem; font-family: inherit; cursor: pointer;
            text-decoration: none; display: flex; align-items: center; justify-content: center;
            transition: all 0.2s;
        }
        .btn-back:hover { background: #fff5f7; }
        .btn-upload {
            flex: 2; padding: 13px; border-radius: 12px;
            background: linear-gradient(135deg, #ff85a2, #ff4d7d);
            color: white; border: none; font-weight: 700; font-size: 0.93rem;
            font-family: inherit; cursor: pointer; display: flex; align-items: center;
            justify-content: center; gap: 8px; transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-upload:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(255,77,125,0.35); }
        .btn-upload:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }

        @media (max-width: 500px) {
            .container { padding: 24px 18px; }
            .btn-group { flex-direction: column; }
        }
    </style>
</head>
<body>
<div class="container">

    <div class="page-header">
        <div class="icon">💳</div>
        <h2>Upload Bukti Pembayaran</h2>
        <p class="subtitle">Pesanan #ORD-<?= $order['id_order'] ?></p>
    </div>

    <?php if ($e = session()->getFlashdata('error')): ?>
        <div class="alert alert-error">❌ <?= esc($e) ?></div>
    <?php endif; ?>
    <?php if ($s = session()->getFlashdata('sukses')): ?>
        <div class="alert alert-success">✅ <?= esc($s) ?></div>
    <?php endif; ?>

    <?php if (! empty($existing_payment) && $existing_payment['status'] === 'menunggu'): ?>
        <div class="existing-payment">
            <p>⏳ Bukti pembayaran sudah diunggah sebelumnya dan sedang menunggu konfirmasi admin.</p>
            <small>Diunggah: <?= date('d M Y H:i', strtotime($existing_payment['created_at'])) ?> via <?= esc($existing_payment['metode_bayar']) ?></small>
        </div>
    <?php endif; ?>

    <!-- Detail Pesanan -->
    <div class="order-info">
        <h3>📦 Ringkasan Pesanan</h3>
        <div class="info-row">
            <span class="info-label">ID Pesanan</span>
            <span class="info-value" style="color:#ff4d7d">#ORD-<?= $order['id_order'] ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Tujuan Kirim</span>
            <span class="info-value"><?= esc($order['kota_tujuan'] ?? '-') ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Kurir</span>
            <span class="info-value"><?= esc(strtoupper($order['kurir'] ?? '-')) ?> <?= esc($order['layanan'] ?? '') ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Ongkir</span>
            <span class="info-value">Rp <?= number_format($order['ongkir'] ?? 0, 0, ',', '.') ?></span>
        </div>
        <div class="info-row total-row">
            <span class="info-label">Total Bayar</span>
            <span class="info-value">Rp <?= number_format($order['total_bayar'], 0, ',', '.') ?></span>
        </div>
    </div>

    <!-- Form Upload -->
    <form action="<?= base_url('pembayaran/upload/' . $order['id_order']) ?>" method="post"
          enctype="multipart/form-data" id="formUpload">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="metode_bayar">Metode Pembayaran</label>
            <select name="metode_bayar" id="metode_bayar">
                <option value="transfer_bank">🏦 Transfer Bank (BCA/BNI/Mandiri)</option>
                <option value="qris">📱 QRIS</option>
                <option value="gopay">🟢 GoPay</option>
                <option value="ovo">💜 OVO</option>
                <option value="dana">🔵 DANA</option>
            </select>
        </div>

        <div class="form-group">
            <label>Bukti Pembayaran <span style="color:#ff4d7d">*</span></label>
            <div class="dropzone" id="dropzone">
                <input type="file" name="bukti_pembayaran" id="fileBukti"
                       accept="image/jpeg,image/jpg,image/png,image/webp,application/pdf" required>
                <div class="dz-icon">📎</div>
                <h4>Klik atau drag file ke sini</h4>
                <p>JPG, PNG, WEBP, PDF — Maks. 5MB</p>
                <div class="preview-area" id="previewArea"></div>
            </div>
            <div class="file-info" id="fileInfo"></div>
        </div>

        <div class="form-group">
            <label for="catatan">Catatan (Opsional)</label>
            <textarea name="catatan" id="catatan" placeholder="Contoh: Sudah transfer jam 14.00 via BCA..." maxlength="500"></textarea>
        </div>

        <div class="btn-group">
            <a href="<?= base_url('logistik/daftar-pesanan') ?>" class="btn-back">← Kembali</a>
            <button type="submit" class="btn-upload" id="btnUpload" disabled>
                📤 Upload Sekarang
            </button>
        </div>
    </form>
</div>

<script>
const dropzone  = document.getElementById('dropzone');
const fileInput = document.getElementById('fileBukti');
const preview   = document.getElementById('previewArea');
const fileInfo  = document.getElementById('fileInfo');
const btnUpload = document.getElementById('btnUpload');

// Drag & drop styling
dropzone.addEventListener('dragover',  (e) => { e.preventDefault(); dropzone.classList.add('drag-over'); });
dropzone.addEventListener('dragleave', ()  => { dropzone.classList.remove('drag-over'); });
dropzone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropzone.classList.remove('drag-over');
    if (e.dataTransfer.files.length) {
        fileInput.files = e.dataTransfer.files;
        processFile(e.dataTransfer.files[0]);
    }
});

fileInput.addEventListener('change', function() {
    if (this.files.length) processFile(this.files[0]);
});

function processFile(file) {
    // Validasi ukuran
    if (file.size > 5 * 1024 * 1024) {
        alert('File terlalu besar! Maksimal 5MB.');
        fileInput.value = '';
        btnUpload.disabled = true;
        return;
    }

    const sizeMB = (file.size / 1024 / 1024).toFixed(2);
    fileInfo.textContent = '📄 ' + file.name + ' (' + sizeMB + ' MB)';
    preview.style.display = 'block';
    preview.innerHTML = '';

    if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = (e) => {
            const img = document.createElement('img');
            img.src = e.target.result;
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    } else if (file.type === 'application/pdf') {
        preview.innerHTML = '<div class="pdf-preview">📄 PDF: ' + file.name + '</div>';
    }

    btnUpload.disabled = false;
}

// Loading state
document.getElementById('formUpload').addEventListener('submit', function() {
    btnUpload.textContent = '⏳ Mengunggah...';
    btnUpload.disabled = true;
});
</script>
</body>
</html>
