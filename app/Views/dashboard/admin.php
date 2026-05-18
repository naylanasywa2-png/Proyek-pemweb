<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Digital Memories</title>
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
        .role-badge { background: rgba(255,255,255,0.25); color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 700; }
        .btn-logout { color: white; text-decoration: none; font-size: 0.82rem; font-weight: 700; border: 1.5px solid rgba(255,255,255,0.5); padding: 6px 14px; border-radius: 8px; transition: background 0.2s; }
        .btn-logout:hover { background: rgba(255,255,255,0.15); }

        .main { padding: 32px; max-width: 1200px; margin: auto; }
        h1 { font-size: 1.5rem; font-weight: 800; color: #333; margin-bottom: 6px; }
        .sub { color: #888; font-size: 0.88rem; margin-bottom: 28px; }

        .alert { padding: 12px 18px; border-radius: 12px; margin-bottom: 22px; font-size: 0.9rem; }
        .alert-success { background: #f0fff4; color: #16a34a; border: 1px solid #86efac; }
        .alert-error   { background: #fff0f3; color: #cc3366; border: 1px solid #ffb3cc; }

        /* Stat cards */
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 18px; margin-bottom: 32px; }
        .stat-card {
            background: white; border-radius: 18px; padding: 24px 22px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.06); border-left: 4px solid;
        }
        .stat-card.pink   { border-color: #ff4d7d; }
        .stat-card.blue   { border-color: #3b82f6; }
        .stat-card.green  { border-color: #10b981; }
        .stat-card.orange { border-color: #f59e0b; }
        .stat-card .label { font-size: 0.78rem; color: #888; font-weight: 600; text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 8px; }
        .stat-card .value { font-size: 2rem; font-weight: 800; color: #333; }
        .stat-card .icon  { font-size: 1.4rem; float: right; margin-top: -2px; }

        /* Section */
        .section { background: white; border-radius: 18px; padding: 24px 28px; margin-bottom: 24px; box-shadow: 0 4px 16px rgba(0,0,0,0.05); }
        .section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
        .section-header h2 { font-size: 1rem; font-weight: 700; color: #333; }
        .section-header a { color: #ff4d7d; font-size: 0.82rem; font-weight: 700; text-decoration: none; }

        /* Admin nav links */
        .admin-links { display: flex; gap: 14px; flex-wrap: wrap; margin-bottom: 28px; }
        .admin-link {
            display: inline-flex; align-items: center; gap: 8px;
            background: white; border: 1.5px solid #ffe4e9; border-radius: 12px;
            padding: 10px 18px; text-decoration: none; color: #ff4d7d;
            font-weight: 700; font-size: 0.85rem;
            transition: all 0.2s;
        }
        .admin-link:hover { background: #fff5f7; border-color: #ff85a2; transform: translateY(-2px); }
        .admin-link .badge { background: #ff4d7d; color: white; border-radius: 20px; padding: 1px 8px; font-size: 0.72rem; }

        /* Table */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 600px; }
        thead tr { background: #fff5f7; }
        th { padding: 11px 14px; text-align: left; font-size: 0.77rem; color: #ff4d7d; text-transform: uppercase; letter-spacing: 0.4px; font-weight: 700; border-bottom: 2px solid #ffe4e9; }
        td { padding: 13px 14px; border-bottom: 1px solid #f5f5f5; font-size: 0.87rem; vertical-align: middle; }
        tr:hover td { background: #fff8f9; }
        tr:last-child td { border-bottom: none; }

        .status-badge { padding: 3px 10px; border-radius: 20px; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; }
        .status-pending   { background: #fff3cd; color: #856404; }
        .status-diproses  { background: #cff4fc; color: #055160; }
        .status-selesai   { background: #d1e7dd; color: #0a3622; }
        .status-batal     { background: #f8d7da; color: #842029; }

        .empty-state { text-align: center; padding: 32px 20px; color: #ccc; }
        @media (max-width: 600px) { .main { padding: 18px; } .navbar { padding: 0 18px; } }
    </style>
</head>
<body>

<nav class="navbar">
    <a href="<?= base_url('dashboard') ?>" class="navbar-brand">📸 Digital Memories</a>
    <div style="display:flex;align-items:center;gap:14px;">
        <span class="role-badge">⚙️ Admin</span>
        <span style="color:rgba(255,255,255,0.85);font-size:0.82rem;font-weight:600"><?= esc(session()->get('nama') ?? '') ?></span>
        <a href="<?= base_url('logout') ?>" class="btn-logout">Keluar</a>
    </div>
</nav>

<div class="main">
    <h1>⚙️ Admin Dashboard</h1>
    <p class="sub">Kelola sistem Digital Memories dari sini</p>

    <?php if ($s = session()->getFlashdata('sukses')): ?>
        <div class="alert alert-success">✅ <?= esc($s) ?></div>
    <?php endif; ?>
    <?php if ($e = session()->getFlashdata('error')): ?>
        <div class="alert alert-error">❌ <?= esc($e) ?></div>
    <?php endif; ?>

    <!-- Stats -->
    <div class="stats">
        <div class="stat-card pink">
            <div class="icon">👥</div>
            <div class="label">Total User</div>
            <div class="value"><?= number_format($total_user) ?></div>
        </div>
        <div class="stat-card blue">
            <div class="icon">📦</div>
            <div class="label">Total Pesanan</div>
            <div class="value"><?= number_format($total_pesanan) ?></div>
        </div>
        <div class="stat-card orange">
            <div class="icon">⏳</div>
            <div class="label">Pending</div>
            <div class="value"><?= number_format($pesanan_pending) ?></div>
        </div>
        <div class="stat-card green">
            <div class="icon">🎨</div>
            <div class="label">Total Desain</div>
            <div class="value"><?= number_format($total_desain) ?></div>
        </div>
    </div>

    <!-- Admin Quick Links -->
    <div class="admin-links">
        <a href="<?= base_url('admin/pembayaran') ?>" class="admin-link">
            💳 Konfirmasi Pembayaran
            <?php if (($pesanan_pending ?? 0) > 0): ?>
                <span class="badge"><?= $pesanan_pending ?></span>
            <?php endif; ?>
        </a>
        <a href="<?= base_url('logistik/daftar-pesanan') ?>" class="admin-link">📋 Semua Pesanan</a>
        <a href="<?= base_url('logistik/tesongkir') ?>" class="admin-link">🚚 Cek Ongkir</a>
    </div>

    <!-- Pesanan Terbaru -->
    <div class="section">
        <div class="section-header">
            <h2>📋 5 Pesanan Terbaru</h2>
            <a href="<?= base_url('logistik/daftar-pesanan') ?>">Lihat Semua →</a>
        </div>
        <?php if (empty($pesanan_terbaru)): ?>
            <div class="empty-state">Belum ada pesanan.</div>
        <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th><th>Tanggal</th><th>Tujuan</th>
                        <th>Kurir</th><th>Total</th><th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pesanan_terbaru as $p): ?>
                    <?php
                    $cls = match($p['status_pesanan']) {
                        'diproses' => 'status-diproses', 'selesai' => 'status-selesai',
                        'batal'    => 'status-batal',    default   => 'status-pending'
                    };
                    ?>
                    <tr>
                        <td><strong style="color:#ff4d7d">#ORD-<?= $p['id_order'] ?></strong></td>
                        <td style="font-size:0.82rem;color:#888"><?= date('d M Y', strtotime($p['created_at'])) ?></td>
                        <td><?= esc($p['kota_tujuan'] ?? '-') ?></td>
                        <td><?= esc($p['kurir'] ?? '-') ?> <?= esc($p['layanan'] ?? '') ?></td>
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
