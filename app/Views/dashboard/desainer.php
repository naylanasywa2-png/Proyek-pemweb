<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Desainer - Digital Memories</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8f9fb; min-height: 100vh; color: #444; }
        .navbar {
            background: linear-gradient(135deg, #10b981, #059669); padding: 0 32px; height: 64px;
            display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 4px 20px rgba(16,185,129,0.25); position: sticky; top: 0; z-index: 100;
        }
        .navbar-brand { font-size: 1.3rem; font-weight: 800; color: white; text-decoration: none; }
        .role-badge { background: rgba(255,255,255,0.2); color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 700; }
        .btn-logout { color: white; text-decoration: none; font-size: 0.82rem; font-weight: 700; border: 1.5px solid rgba(255,255,255,0.4); padding: 6px 14px; border-radius: 8px; transition: background 0.2s; }
        .btn-logout:hover { background: rgba(255,255,255,0.15); }
        .main { padding: 32px; max-width: 1100px; margin: auto; }
        h1 { font-size: 1.5rem; font-weight: 800; color: #333; margin-bottom: 4px; }
        .sub { color: #888; font-size: 0.88rem; margin-bottom: 28px; }
        .section { background: white; border-radius: 18px; padding: 24px 28px; box-shadow: 0 4px 16px rgba(0,0,0,0.05); }
        .section h2 { font-size: 1rem; font-weight: 700; color: #333; margin-bottom: 20px; }

        .desain-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 18px; }
        .desain-card {
            border: 1.5px solid #d1fae5; border-radius: 16px; padding: 22px 18px;
            text-align: center; transition: all 0.2s;
        }
        .desain-card:hover { border-color: #10b981; background: #f0fdf8; transform: translateY(-3px); box-shadow: 0 6px 20px rgba(16,185,129,0.15); }
        .desain-card .icon { font-size: 2.5rem; margin-bottom: 10px; }
        .desain-card h3 { font-size: 0.9rem; font-weight: 700; color: #333; margin-bottom: 6px; }
        .desain-card .price { font-size: 0.88rem; color: #10b981; font-weight: 700; margin-bottom: 4px; }
        .desain-card .id { font-size: 0.75rem; color: #aaa; }

        .empty-state { text-align: center; padding: 50px 20px; color: #ccc; }
        .empty-state .icon { font-size: 3rem; margin-bottom: 12px; }
        @media (max-width: 600px) { .main { padding: 18px; } }
    </style>
</head>
<body>
<nav class="navbar">
    <a href="<?= base_url('dashboard') ?>" class="navbar-brand">📸 Digital Memories</a>
    <div style="display:flex;align-items:center;gap:14px;">
        <span class="role-badge">🎨 Desainer</span>
        <span style="color:rgba(255,255,255,0.85);font-size:0.82rem;font-weight:600"><?= esc(session()->get('nama') ?? '') ?></span>
        <a href="<?= base_url('logout') ?>" class="btn-logout">Keluar</a>
    </div>
</nav>

<div class="main">
    <h1>🎨 Dashboard Desainer</h1>
    <p class="sub">Kelola karya desain buku memori Anda</p>

    <?php if ($s = session()->getFlashdata('sukses')): ?>
        <div style="background:#f0fff4;color:#16a34a;border:1px solid #86efac;padding:12px 18px;border-radius:12px;margin-bottom:22px;font-size:0.9rem;">✅ <?= esc($s) ?></div>
    <?php endif; ?>

    <div class="section">
        <h2>🖼️ Karya Desain Saya (<?= count($karya_saya ?? []) ?> desain)</h2>

        <?php if (empty($karya_saya)): ?>
            <div class="empty-state">
                <div class="icon">🎨</div>
                <p>Belum ada desain yang ditambahkan.</p>
            </div>
        <?php else: ?>
            <div class="desain-grid">
                <?php foreach ($karya_saya as $d): ?>
                <div class="desain-card">
                    <div class="icon">📖</div>
                    <h3><?= esc($d['nama_desain'] ?? 'Desain #' . $d['id_desain']) ?></h3>
                    <div class="price">Rp <?= number_format($d['harga'] ?? 0, 0, ',', '.') ?></div>
                    <div class="id">#DSN-<?= $d['id_desain'] ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
