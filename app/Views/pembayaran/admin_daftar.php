<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pembayaran - Admin Digital Memories</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8f9fb; min-height: 100vh; color: #444; }

        .navbar {
            background: linear-gradient(135deg, #ff85a2, #ff4d7d); padding: 0 32px; height: 64px;
            display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 4px 20px rgba(255,77,125,0.25); position: sticky; top: 0; z-index: 100;
        }
        .navbar-brand { font-size: 1.3rem; font-weight: 800; color: white; text-decoration: none; }
        .navbar-right { display: flex; align-items: center; gap: 14px; }
        .btn-back-dash { color: white; text-decoration: none; font-size: 0.82rem; font-weight: 700; border: 1.5px solid rgba(255,255,255,0.5); padding: 6px 14px; border-radius: 8px; transition: background 0.2s; }
        .btn-back-dash:hover { background: rgba(255,255,255,0.15); }

        .main { padding: 32px; max-width: 1200px; margin: auto; }
        h1 { font-size: 1.5rem; font-weight: 800; color: #333; margin-bottom: 4px; }
        .sub { color: #888; font-size: 0.88rem; margin-bottom: 24px; }

        .alert { padding: 13px 18px; border-radius: 12px; margin-bottom: 20px; font-size: 0.9rem; }
        .alert-success { background: #f0fff4; color: #16a34a; border: 1px solid #86efac; }
        .alert-error   { background: #fff0f3; color: #cc3366; border: 1px solid #ffb3cc; }

        /* Tabs */
        .tabs { display: flex; gap: 4px; margin-bottom: 24px; background: white; border-radius: 14px; padding: 6px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); width: fit-content; }
        .tab-btn {
            padding: 9px 20px; border-radius: 10px; border: none; font-family: inherit;
            font-size: 0.85rem; font-weight: 700; cursor: pointer; transition: all 0.2s; color: #888; background: none;
        }
        .tab-btn.active { background: linear-gradient(135deg, #ff85a2, #ff4d7d); color: white; }
        .tab-btn:hover:not(.active) { background: #fff5f7; color: #ff4d7d; }
        .tab-count { background: rgba(255,255,255,0.3); padding: 1px 7px; border-radius: 12px; font-size: 0.72rem; margin-left: 4px; }
        .tab-btn:not(.active) .tab-count { background: #ffe4e9; color: #ff4d7d; }

        /* Section */
        .tab-panel { display: none; }
        .tab-panel.active { display: block; }
        .section { background: white; border-radius: 18px; padding: 24px 28px; box-shadow: 0 4px 16px rgba(0,0,0,0.05); }

        /* Table */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 750px; }
        thead tr { background: #fff5f7; }
        th { padding: 12px 14px; text-align: left; font-size: 0.77rem; color: #ff4d7d; text-transform: uppercase; letter-spacing: 0.4px; font-weight: 700; border-bottom: 2px solid #ffe4e9; }
        th:first-child { border-radius: 10px 0 0 0; }
        th:last-child  { border-radius: 0 10px 0 0; text-align: center; }
        td { padding: 13px 14px; border-bottom: 1px solid #f5f5f5; font-size: 0.87rem; vertical-align: middle; }
        td:last-child { text-align: center; }
        tr:hover td { background: #fff8f9; }
        tr:last-child td { border-bottom: none; }

        /* Bukti image */
        .bukti-thumb {
            width: 48px; height: 48px; border-radius: 8px; object-fit: cover;
            border: 2px solid #ffdae3; cursor: pointer; transition: transform 0.2s;
        }
        .bukti-thumb:hover { transform: scale(1.1); }
        .pdf-icon { background: #fff0f3; color: #ff4d7d; padding: 4px 10px; border-radius: 8px; font-size: 0.75rem; font-weight: 700; }

        /* Status badges */
        .status-menunggu     { background: #fff3cd; color: #856404; }
        .status-dikonfirmasi { background: #d1e7dd; color: #0a3622; }
        .status-ditolak      { background: #f8d7da; color: #842029; }
        .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 0.74rem; font-weight: 700; text-transform: uppercase; }

        /* Metode badge */
        .metode-badge { background: #f0f4ff; color: #3355cc; padding: 3px 10px; border-radius: 12px; font-size: 0.76rem; font-weight: 600; }

        /* Action buttons */
        .btn-konfirmasi, .btn-tolak {
            border: none; padding: 7px 14px; border-radius: 8px; cursor: pointer;
            font-size: 0.78rem; font-weight: 700; font-family: inherit; transition: all 0.15s;
        }
        .btn-konfirmasi { background: #d1e7dd; color: #0a3622; }
        .btn-konfirmasi:hover { background: #10b981; color: white; }
        .btn-tolak { background: #f8d7da; color: #842029; margin-left: 6px; }
        .btn-tolak:hover { background: #dc2626; color: white; }

        /* Empty */
        .empty-state { text-align: center; padding: 50px 20px; color: #ccc; }
        .empty-state .icon { font-size: 3rem; margin-bottom: 12px; }
        .empty-state p { font-size: 0.9rem; color: #bbb; }

        /* Modal */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; }
        .modal-overlay.open { display: flex; }
        .modal {
            background: white; border-radius: 20px; padding: 28px 32px; width: 100%; max-width: 440px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2); animation: modalIn 0.2s ease;
        }
        @keyframes modalIn { from { transform: scale(0.95); opacity: 0; } to { transform: scale(1); opacity: 1; } }
        .modal h3 { color: #333; font-size: 1rem; font-weight: 700; margin-bottom: 16px; }
        .modal textarea {
            width: 100%; padding: 12px; border: 2px solid #ffdae3; border-radius: 12px;
            font-family: inherit; font-size: 0.9rem; resize: vertical; min-height: 80px; outline: none;
            transition: border-color 0.2s;
        }
        .modal textarea:focus { border-color: #ff85a2; }
        .modal-btns { display: flex; gap: 10px; margin-top: 16px; }
        .modal-cancel { flex: 1; padding: 12px; border: 2px solid #ffdae3; background: white; color: #ff85a2; border-radius: 10px; font-family: inherit; font-weight: 700; cursor: pointer; }
        .modal-submit { flex: 2; padding: 12px; background: linear-gradient(135deg, #ff85a2, #ff4d7d); color: white; border: none; border-radius: 10px; font-family: inherit; font-weight: 700; cursor: pointer; }

        @media (max-width: 600px) { .main { padding: 18px; } .navbar { padding: 0 18px; } }
    </style>
</head>
<body>

<nav class="navbar">
    <a href="<?= base_url('dashboard') ?>" class="navbar-brand">📸 Digital Memories</a>
    <div class="navbar-right">
        <a href="<?= base_url('dashboard') ?>" class="btn-back-dash">← Dashboard</a>
    </div>
</nav>

<div class="main">
    <h1>💳 Manajemen Pembayaran</h1>
    <p class="sub">Konfirmasi atau tolak bukti pembayaran dari customer</p>

    <?php if ($s = session()->getFlashdata('sukses')): ?>
        <div class="alert alert-success">✅ <?= esc($s) ?></div>
    <?php endif; ?>
    <?php if ($e = session()->getFlashdata('error')): ?>
        <div class="alert alert-error">❌ <?= esc($e) ?></div>
    <?php endif; ?>

    <!-- Tabs -->
    <div class="tabs">
        <button class="tab-btn active" onclick="switchTab('menunggu', this)">
            ⏳ Menunggu <span class="tab-count"><?= count($menunggu) ?></span>
        </button>
        <button class="tab-btn" onclick="switchTab('dikonfirmasi', this)">
            ✅ Dikonfirmasi <span class="tab-count"><?= count($dikonfirmasi) ?></span>
        </button>
        <button class="tab-btn" onclick="switchTab('ditolak', this)">
            ❌ Ditolak <span class="tab-count"><?= count($ditolak) ?></span>
        </button>
    </div>

    <!-- Panel: Menunggu -->
    <div class="tab-panel active" id="panel-menunggu">
        <div class="section">
            <?php if (empty($menunggu)): ?>
                <div class="empty-state">
                    <div class="icon">✅</div>
                    <p>Tidak ada pembayaran yang menunggu konfirmasi.</p>
                </div>
            <?php else: ?>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th><th>Pesanan</th><th>Nominal</th>
                            <th>Metode</th><th>Tanggal</th><th>Bukti</th><th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($menunggu as $p): ?>
                        <tr>
                            <td><strong style="color:#ff4d7d">#PAY-<?= $p['id_payment'] ?></strong></td>
                            <td>
                                <strong>#ORD-<?= $p['id_order'] ?></strong>
                                <div style="font-size:0.78rem;color:#888"><?= esc($p['kota_tujuan'] ?? '-') ?></div>
                            </td>
                            <td><strong>Rp <?= number_format($p['nominal'], 0, ',', '.') ?></strong></td>
                            <td><span class="metode-badge"><?= esc(str_replace('_', ' ', $p['metode_bayar'])) ?></span></td>
                            <td style="font-size:0.82rem;color:#888"><?= date('d M Y H:i', strtotime($p['created_at'])) ?></td>
                            <td>
                                <?php if (! empty($p['file_bukti'])): ?>
                                    <?php $ext = strtolower(pathinfo($p['file_bukti'], PATHINFO_EXTENSION)); ?>
                                    <?php if (in_array($ext, ['jpg','jpeg','png','webp'])): ?>
                                        <img src="<?= base_url($p['file_bukti']) ?>" class="bukti-thumb"
                                             onclick="window.open('<?= base_url($p['file_bukti']) ?>','_blank')"
                                             alt="Bukti">
                                    <?php else: ?>
                                        <a href="<?= base_url($p['file_bukti']) ?>" target="_blank" class="pdf-icon">📄 PDF</a>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span style="color:#ccc">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn-konfirmasi"
                                    onclick="openModal('konfirmasi', <?= $p['id_payment'] ?>, <?= $p['id_order'] ?>)">
                                    ✅ Konfirmasi
                                </button>
                                <button class="btn-tolak"
                                    onclick="openModal('tolak', <?= $p['id_payment'] ?>, <?= $p['id_order'] ?>)">
                                    ❌ Tolak
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Panel: Dikonfirmasi -->
    <div class="tab-panel" id="panel-dikonfirmasi">
        <div class="section">
            <?php if (empty($dikonfirmasi)): ?>
                <div class="empty-state"><div class="icon">📭</div><p>Belum ada pembayaran yang dikonfirmasi.</p></div>
            <?php else: ?>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>ID</th><th>Pesanan</th><th>Nominal</th><th>Metode</th><th>Dikonfirmasi</th><th>Status</th></tr></thead>
                    <tbody>
                        <?php foreach ($dikonfirmasi as $p): ?>
                        <tr>
                            <td><strong style="color:#ff4d7d">#PAY-<?= $p['id_payment'] ?></strong></td>
                            <td><strong>#ORD-<?= $p['id_order'] ?></strong></td>
                            <td>Rp <?= number_format($p['nominal'], 0, ',', '.') ?></td>
                            <td><span class="metode-badge"><?= esc(str_replace('_', ' ', $p['metode_bayar'])) ?></span></td>
                            <td style="font-size:0.82rem;color:#888"><?= date('d M Y H:i', strtotime($p['dikonfirmasi_at'] ?? $p['created_at'])) ?></td>
                            <td><span class="status-badge status-dikonfirmasi">✅ Dikonfirmasi</span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Panel: Ditolak -->
    <div class="tab-panel" id="panel-ditolak">
        <div class="section">
            <?php if (empty($ditolak)): ?>
                <div class="empty-state"><div class="icon">📭</div><p>Tidak ada pembayaran yang ditolak.</p></div>
            <?php else: ?>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>ID</th><th>Pesanan</th><th>Nominal</th><th>Alasan Tolak</th><th>Tanggal</th><th>Status</th></tr></thead>
                    <tbody>
                        <?php foreach ($ditolak as $p): ?>
                        <tr>
                            <td><strong style="color:#ff4d7d">#PAY-<?= $p['id_payment'] ?></strong></td>
                            <td><strong>#ORD-<?= $p['id_order'] ?></strong></td>
                            <td>Rp <?= number_format($p['nominal'], 0, ',', '.') ?></td>
                            <td style="font-size:0.82rem;color:#666"><?= esc($p['catatan_admin'] ?? '-') ?></td>
                            <td style="font-size:0.82rem;color:#888"><?= date('d M Y H:i', strtotime($p['dikonfirmasi_at'] ?? $p['created_at'])) ?></td>
                            <td><span class="status-badge status-ditolak">❌ Ditolak</span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi/Tolak -->
<div class="modal-overlay" id="modalOverlay">
    <div class="modal">
        <h3 id="modalTitle">Konfirmasi Pembayaran</h3>
        <p style="font-size:0.85rem;color:#888;margin-bottom:14px" id="modalDesc"></p>
        <form id="modalForm" method="post">
            <?= csrf_field() ?>
            <textarea name="catatan_admin" id="modalCatatan" placeholder="Catatan untuk user (opsional untuk konfirmasi, wajib untuk tolak)"></textarea>
            <div class="modal-btns">
                <button type="button" class="modal-cancel" onclick="closeModal()">Batal</button>
                <button type="submit" class="modal-submit" id="modalSubmit">Konfirmasi</button>
            </div>
        </form>
    </div>
</div>

<script>
function switchTab(name, btn) {
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('panel-' + name).classList.add('active');
    btn.classList.add('active');
}

let modalType = '';
function openModal(type, payId, ordId) {
    modalType = type;
    const overlay = document.getElementById('modalOverlay');
    const form    = document.getElementById('modalForm');
    const title   = document.getElementById('modalTitle');
    const desc    = document.getElementById('modalDesc');
    const submit  = document.getElementById('modalSubmit');
    const catatan = document.getElementById('modalCatatan');

    if (type === 'konfirmasi') {
        form.action   = '<?= base_url("admin/pembayaran/konfirmasi/") ?>' + payId;
        title.textContent = '✅ Konfirmasi Pembayaran #ORD-' + ordId;
        desc.textContent  = 'Status pesanan akan berubah menjadi SELESAI dan notifikasi dikirim ke vendor.';
        submit.textContent = '✅ Ya, Konfirmasi';
        submit.style.background = 'linear-gradient(135deg,#10b981,#059669)';
        catatan.required = false;
        catatan.placeholder = 'Catatan untuk user (opsional)';
    } else {
        form.action   = '<?= base_url("admin/pembayaran/tolak/") ?>' + payId;
        title.textContent = '❌ Tolak Pembayaran #ORD-' + ordId;
        desc.textContent  = 'User akan diminta mengupload ulang bukti pembayaran.';
        submit.textContent = '❌ Ya, Tolak';
        submit.style.background = 'linear-gradient(135deg,#f87171,#dc2626)';
        catatan.required = true;
        catatan.placeholder = 'Alasan penolakan (wajib diisi)';
    }

    overlay.classList.add('open');
}

function closeModal() {
    document.getElementById('modalOverlay').classList.remove('open');
    document.getElementById('modalCatatan').value = '';
}

document.getElementById('modalOverlay').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>
</body>
</html>
