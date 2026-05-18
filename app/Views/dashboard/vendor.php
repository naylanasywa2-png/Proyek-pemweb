<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Vendor - Digital Memories</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8f9fb; min-height: 100vh; color: #444; }
        .navbar {
            background: linear-gradient(135deg, #6366f1, #4f46e5); padding: 0 32px; height: 64px;
            display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 4px 20px rgba(99,102,241,0.25); position: sticky; top: 0; z-index: 100;
        }
        .navbar-brand { font-size: 1.3rem; font-weight: 800; color: white; text-decoration: none; }
        .role-badge { background: rgba(255,255,255,0.2); color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 700; }
        .btn-logout { color: white; text-decoration: none; font-size: 0.82rem; font-weight: 700; border: 1.5px solid rgba(255,255,255,0.4); padding: 6px 14px; border-radius: 8px; transition: background 0.2s; }
        .btn-logout:hover { background: rgba(255,255,255,0.15); }
        .main { padding: 32px; max-width: 1100px; margin: auto; }
        h1 { font-size: 1.5rem; font-weight: 800; color: #333; margin-bottom: 4px; }
        .sub { color: #888; font-size: 0.88rem; margin-bottom: 28px; }
        .alert-success { background: #f0fff4; color: #16a34a; border: 1px solid #86efac; padding: 12px 18px; border-radius: 12px; margin-bottom: 22px; font-size: 0.9rem; }
        .alert-error   { background: #fff0f3; color: #cc3366; border: 1px solid #ffb3cc; padding: 12px 18px; border-radius: 12px; margin-bottom: 22px; font-size: 0.9rem; }
        .section { background: white; border-radius: 18px; padding: 24px 28px; margin-bottom: 24px; box-shadow: 0 4px 16px rgba(0,0,0,0.05); }
        .section h2 { font-size: 1rem; font-weight: 700; color: #333; margin-bottom: 18px; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 600px; }
        thead tr { background: #f0f0ff; }
        th { padding: 11px 14px; text-align: left; font-size: 0.77rem; color: #6366f1; text-transform: uppercase; font-weight: 700; border-bottom: 2px solid #e0e0ff; }
        td { padding: 13px 14px; border-bottom: 1px solid #f5f5f5; font-size: 0.87rem; vertical-align: middle; }
        tr:hover td { background: #f8f8ff; }
        tr:last-child td { border-bottom: none; }
        .status-badge { padding: 3px 10px; border-radius: 20px; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; }
        .status-pending  { background: #fff3cd; color: #856404; }
        .status-diproses { background: #cff4fc; color: #055160; }
        .status-selesai  { background: #d1e7dd; color: #0a3622; }
        .kurir-badge { background: #e0e0ff; color: #4f46e5; padding: 3px 10px; border-radius: 20px; font-weight: 700; font-size: 0.74rem; }
        .empty-state { text-align: center; padding: 40px 20px; color: #ccc; }
        .empty-state .icon { font-size: 2.5rem; margin-bottom: 10px; }
        @media (max-width: 600px) { .main { padding: 18px; } }
    </style>
</head>
<body>
<nav class="navbar">
    <a href="<?= base_url('dashboard') ?>" class="navbar-brand">📸 Digital Memories</a>
    <div style="display:flex;align-items:center;gap:14px;">
        <span class="role-badge">🏭 Vendor</span>
        <span style="color:rgba(255,255,255,0.85);font-size:0.82rem;font-weight:600"><?= esc(session()->get('nama') ?? '') ?></span>
        <a href="<?= base_url('logout') ?>" class="btn-logout">Keluar</a>
    </div>
</nav>

<div class="main">
    <h1>🏭 Dashboard Vendor</h1>
    <p class="sub">Daftar pesanan yang masuk untuk diproses</p>

    <?php if ($s = session()->getFlashdata('sukses')): ?>
        <div class="alert-success">✅ <?= esc($s) ?></div>
    <?php endif; ?>
    <?php if ($e = session()->getFlashdata('error')): ?>
        <div class="alert-error">❌ <?= esc($e) ?></div>
    <?php endif; ?>

    <div class="section">
        <h2>📦 Pesanan Masuk (<?= count($pesanan_masuk ?? []) ?> pesanan)</h2>

        <?php if (empty($pesanan_masuk)): ?>
            <div class="empty-state">
                <div class="icon">📭</div>
                <p>Belum ada pesanan masuk saat ini.</p>
            </div>
        <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Tanggal</th>
                        <th>Tujuan</th>
                        <th>Kurir</th>
                        <th>Berat</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pesanan_masuk as $p): ?>
                    <?php
                    $cls = match($p['status_pesanan']) {
                        'diproses' => 'status-diproses', 'selesai' => 'status-selesai',
                        default => 'status-pending'
                    };
                    ?>
                    <tr>
                        <td><strong style="color:#4f46e5">#ORD-<?= $p['id_order'] ?></strong></td>
                        <td style="font-size:0.82rem;color:#888"><?= date('d M Y', strtotime($p['created_at'])) ?></td>
                        <td style="font-size:0.85rem"><?= esc($p['kota_tujuan'] ?? '-') ?></td>
                        <td>
                            <span class="kurir-badge"><?= esc(strtoupper($p['kurir'] ?? '-')) ?></span>
                            <br><small style="color:#aaa"><?= esc($p['layanan'] ?? '') ?></small>
                        </td>
                        <td><?= number_format($p['berat'] ?? 0, 0, ',', '.') ?> gr</td>
                        <td><strong>Rp <?= number_format($p['total_bayar'], 0, ',', '.') ?></strong></td>
                        <td><span class="status-badge <?= $cls ?>"><?= esc($p['status_pesanan']) ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
