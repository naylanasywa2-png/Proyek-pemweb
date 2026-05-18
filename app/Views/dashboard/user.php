<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Digital Memories</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #fff5f7; min-height: 100vh; color: #444; }

        /* Navbar */
        .navbar {
            background: white; padding: 0 32px; height: 64px;
            display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 2px 16px rgba(255,77,125,0.1); position: sticky; top: 0; z-index: 100;
        }
        .navbar-brand { font-size: 1.3rem; font-weight: 800; color: #ff4d7d; text-decoration: none; }
        .navbar-right { display: flex; align-items: center; gap: 16px; }
        .user-chip {
            background: #fff5f7; border: 1.5px solid #ffdae3; border-radius: 24px;
            padding: 6px 14px; font-size: 0.82rem; font-weight: 600; color: #ff4d7d;
        }
        .btn-logout {
            background: none; border: 1.5px solid #ffdae3; color: #ff85a2;
            padding: 7px 16px; border-radius: 10px; font-size: 0.82rem; font-weight: 700;
            font-family: inherit; cursor: pointer; text-decoration: none;
            transition: all 0.2s;
        }
        .btn-logout:hover { background: #fff0f3; border-color: #ff85a2; }

        /* Main */
        .main { padding: 32px; max-width: 1100px; margin: auto; }
        .welcome { margin-bottom: 28px; }
        .welcome h1 { font-size: 1.6rem; font-weight: 800; color: #333; }
        .welcome h1 span { color: #ff4d7d; }
        .welcome p { color: #888; margin-top: 4px; font-size: 0.9rem; }

        /* Alert */
        .alert { padding: 12px 18px; border-radius: 12px; margin-bottom: 24px; font-size: 0.9rem; }
        .alert-success { background: #f0fff4; color: #16a34a; border: 1px solid #86efac; }
        .alert-error   { background: #fff0f3; color: #cc3366; border: 1px solid #ffb3cc; }

        /* Quick actions */
        .quick-actions { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 32px; }
        .action-card {
            background: white; border-radius: 18px; padding: 24px 20px;
            text-decoration: none; color: inherit;
            box-shadow: 0 4px 16px rgba(255,77,125,0.08);
            border: 1.5px solid #ffe4e9;
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex; flex-direction: column; gap: 10px;
        }
        .action-card:hover { transform: translateY(-4px); box-shadow: 0 10px 28px rgba(255,77,125,0.15); }
        .action-card .icon { font-size: 2rem; }
        .action-card h3 { font-size: 0.95rem; font-weight: 700; color: #333; }
        .action-card p { font-size: 0.8rem; color: #aaa; }

        /* Section */
        .section { background: white; border-radius: 18px; padding: 24px 28px; margin-bottom: 24px; box-shadow: 0 4px 16px rgba(255,77,125,0.06); }
        .section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
        .section-header h2 { font-size: 1rem; font-weight: 700; color: #333; }
        .section-header a { color: #ff4d7d; font-size: 0.82rem; font-weight: 700; text-decoration: none; }
        .section-header a:hover { text-decoration: underline; }

        /* Pesanan mini-list */
        .order-item {
            display: flex; align-items: center; justify-content: space-between;
            padding: 12px 0; border-bottom: 1px solid #ffe4e9; gap: 12px; flex-wrap: wrap;
        }
        .order-item:last-child { border-bottom: none; }
        .order-id { font-weight: 700; color: #ff4d7d; font-size: 0.9rem; }
        .order-meta { font-size: 0.8rem; color: #888; }
        .order-total { font-weight: 700; color: #333; font-size: 0.9rem; }
        .status-badge { padding: 3px 10px; border-radius: 20px; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; }
        .status-pending   { background: #fff3cd; color: #856404; }
        .status-diproses  { background: #cff4fc; color: #055160; }
        .status-dikirim   { background: #d1e7dd; color: #0a3622; }
        .status-selesai   { background: #d1e7dd; color: #0a3622; }
        .status-batal     { background: #f8d7da; color: #842029; }

        .empty-state { text-align: center; padding: 40px 20px; color: #ccc; }
        .empty-state .icon { font-size: 2.5rem; margin-bottom: 10px; }
        .empty-state p { font-size: 0.9rem; color: #bbb; }

        /* Katalog */
        .katalog-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 14px; }
        .katalog-item {
            border: 1.5px solid #ffe4e9; border-radius: 14px; padding: 16px;
            text-align: center; transition: all 0.2s;
        }
        .katalog-item:hover { border-color: #ff85a2; background: #fff5f7; }
        .katalog-item .design-icon { font-size: 2rem; margin-bottom: 8px; }
        .katalog-item h4 { font-size: 0.85rem; font-weight: 700; color: #333; margin-bottom: 4px; }
        .katalog-item .price { font-size: 0.8rem; color: #ff4d7d; font-weight: 700; }

        @media (max-width: 600px) { .main { padding: 18px; } .navbar { padding: 0 18px; } }
    </style>
</head>
<body>

<nav class="navbar">
    <a href="<?= base_url('dashboard') ?>" class="navbar-brand">📸 Digital Memories</a>
    <div class="navbar-right">
        <span class="user-chip">👤 <?= esc(session()->get('nama') ?? 'User') ?></span>
        <a href="<?= base_url('logout') ?>" class="btn-logout">Keluar</a>
    </div>
</nav>

<div class="main">

    <div class="welcome">
        <h1>Halo, <span><?= esc(session()->get('nama') ?? 'Pengguna') ?></span>! 👋</h1>
        <p>Selamat datang di dashboard Anda. Pesan buku memori terbaik di sini.</p>
    </div>

    <?php if ($s = session()->getFlashdata('sukses')): ?>
        <div class="alert alert-success">✅ <?= esc($s) ?></div>
    <?php endif; ?>
    <?php if ($e = session()->getFlashdata('error')): ?>
        <div class="alert alert-error">❌ <?= esc($e) ?></div>
    <?php endif; ?>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <a href="<?= base_url('logistik/tesongkir') ?>" class="action-card">
            <div class="icon">🚚</div>
            <h3>Cek Ongkir</h3>
            <p>Cek biaya kirim ke kotamu</p>
        </a>
        <a href="<?= base_url('logistik/daftar-pesanan') ?>" class="action-card">
            <div class="icon">📋</div>
            <h3>Pesanan Saya</h3>
            <p>Lihat riwayat pesanan</p>
        </a>
        <a href="<?= base_url('register') ?>" class="action-card" style="pointer-events:none;opacity:0.5">
            <div class="icon">📂</div>
            <h3>Upload Foto</h3>
            <p>Segera hadir</p>
        </a>
        <a href="<?= base_url('register') ?>" class="action-card" style="pointer-events:none;opacity:0.5">
            <div class="icon">⬇️</div>
            <h3>Unduh Softfile</h3>
            <p>Segera hadir</p>
        </a>
    </div>

    <!-- Pesanan Terbaru -->
    <div class="section">
        <div class="section-header">
            <h2>📋 Pesanan Terbaru Saya</h2>
            <a href="<?= base_url('logistik/daftar-pesanan') ?>">Lihat Semua →</a>
        </div>

        <?php if (empty($pesanan_saya)): ?>
            <div class="empty-state">
                <div class="icon">📭</div>
                <p>Belum ada pesanan. <a href="<?= base_url('logistik/tesongkir') ?>" style="color:#ff4d7d">Pesan sekarang!</a></p>
            </div>
        <?php else: ?>
            <?php foreach (array_slice($pesanan_saya, 0, 5) as $p): ?>
            <div class="order-item">
                <div>
                    <div class="order-id">#ORD-<?= $p['id_order'] ?></div>
                    <div class="order-meta"><?= date('d M Y', strtotime($p['created_at'])) ?> · <?= esc($p['kota_tujuan'] ?? '-') ?></div>
                </div>
                <?php
                $cls = match($p['status_pesanan']) {
                    'diproses' => 'status-diproses', 'dikirim' => 'status-dikirim',
                    'selesai'  => 'status-selesai',  'batal'   => 'status-batal',
                    default    => 'status-pending'
                };
                ?>
                <span class="status-badge <?= $cls ?>"><?= esc($p['status_pesanan']) ?></span>
                <div class="order-total">Rp <?= number_format($p['total_bayar'], 0, ',', '.') ?></div>
                <?php if ($p['status_pesanan'] === 'pending'): ?>
                    <a href="<?= base_url('pembayaran/upload/' . $p['id_order']) ?>"
                       style="background:linear-gradient(135deg,#ff85a2,#ff4d7d);color:white;padding:6px 14px;border-radius:8px;font-size:0.78rem;font-weight:700;text-decoration:none;">
                        Upload Bukti
                    </a>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Katalog Desain -->
    <?php if (! empty($katalog)): ?>
    <div class="section">
        <div class="section-header">
            <h2>🎨 Pilihan Desain</h2>
        </div>
        <div class="katalog-grid">
            <?php foreach ($katalog as $d): ?>
            <div class="katalog-item">
                <div class="design-icon">📖</div>
                <h4><?= esc($d['nama_desain']) ?></h4>
                <div class="price">Rp <?= number_format($d['harga'], 0, ',', '.') ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

</div>
</body>
</html>
