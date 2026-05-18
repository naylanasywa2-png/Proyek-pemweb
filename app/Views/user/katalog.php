<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MBook | Eksplor Tema Yearbook 🎀</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --bg-body: #f8f7fd; 
            --purple-primary: #a594f9;
            --pink-soft: #ffabe1;
            --white: #ffffff;
            --gradient-banner: linear-gradient(135deg, #b19ff9 0%, #ffabe1 100%);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            margin: 0;
            display: flex;
        }

        /* --- SIDEBAR --- */
        .sidebar {
            width: 260px;
            background: var(--white);
            height: 100vh;
            position: fixed;
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            border-right: 1px solid rgba(0,0,0,0.03);
            z-index: 1000;
        }

        .brand-logo {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--purple-primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 40px;
        }

        .nav-link-custom {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px 18px;
            text-decoration: none;
            color: #6c757d;
            border-radius: 12px;
            margin-bottom: 10px;
            transition: 0.3s;
            font-weight: 600;
        }

        .nav-link-custom:hover, .nav-link-custom.active {
            background: #f3f0ff;
            color: var(--purple-primary);
        }

        /* --- CONTENT AREA --- */
        .main-wrapper {
            margin-left: 260px;
            width: calc(100% - 260px);
            padding: 30px 40px;
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 35px;
        }

        .search-box {
            background: white;
            border-radius: 50px;
            padding: 10px 25px;
            width: 350px;
            display: flex;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        }

        .search-box input {
            border: none;
            outline: none;
            margin-left: 10px;
            width: 100%;
            font-size: 0.9rem;
        }

        .welcome-banner {
            background: var(--gradient-banner);
            border-radius: 25px;
            padding: 40px 45px;
            color: white;
            position: relative;
            overflow: hidden;
            margin-bottom: 40px;
            box-shadow: 0 10px 25px rgba(165, 148, 249, 0.3);
        }

        .welcome-banner img {
            position: absolute;
            right: 40px;
            bottom: -10px;
            height: 160px;
            filter: drop-shadow(0 10px 15px rgba(0,0,0,0.1));
        }

        /* --- DYNAMIC CARD STYLE --- */
        .theme-card {
            background: white;
            border-radius: 22px;
            padding: 15px;
            transition: 0.4s;
            text-align: center;
            border: none;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .theme-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.06);
        }

        .img-holder {
            border-radius: 18px;
            height: 180px;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            position: relative;
            overflow: hidden;
        }

        .img-holder img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .btn-detail {
            background: #f8f7fd;
            color: var(--purple-primary);
            padding: 8px 0;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 700;
            text-decoration: none;
            display: block;
            width: 100%;
            margin-top: 15px;
            transition: 0.3s;
            border: 1px solid #f0eeff;
        }

        .btn-detail:hover {
            background: var(--purple-primary);
            color: white;
        }

        .price-tag {
            color: var(--pink-soft);
            font-weight: 800;
            font-size: 0.9rem;
            display: block;
            margin-top: 5px;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <a href="<?= base_url('katalog') ?>" class="brand-logo">
            <i class="fas fa-book-open"></i>
            <span>MBook📖</span>
        </a>
        <div class="nav-menu">
            <a href="<?= base_url('katalog') ?>" class="nav-link-custom active"><i class="fas fa-house"></i> Beranda</a>
            <a href="<?= base_url('user/history') ?>" class="nav-link-custom"><i class="fas fa-shopping-bag"></i> Pesanan Saya</a>
            <a href="<?= base_url('pembayaran') ?>" class="nav-link-custom"><i class="fas fa-wallet"></i> Pembayaran</a>
            <a href="<?= base_url('pengaturan') ?>" class="nav-link-custom"><i class="fas fa-user-gear"></i> Pengaturan</a>
        </div>
    </div>

    <div class="main-wrapper">
        <div class="header-top">
            <div class="search-box">
                <i class="fas fa-search text-muted"></i>
                <input type="text" placeholder="Cari tema favoritmu...">
            </div>
            
            <div class="d-flex align-items-center gap-4">
                <?php if (session()->get('logged_in')): ?>
                    <div class="dropdown">
                        <div class="d-flex align-items-center gap-2 bg-white p-1 pe-3 rounded-pill shadow-sm border" style="cursor: pointer;" data-bs-toggle="dropdown">
                            <img src="https://ui-avatars.com/api/?name=<?= session()->get('nama') ?>&background=a594f9&color=fff" width="35" class="rounded-circle">
                            <span class="small fw-bold text-dark"><?= session()->get('nama') ?></span>
                            <i class="fas fa-chevron-down small text-muted"></i>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2" style="border-radius: 15px;">
                            <li><a class="dropdown-item py-2 text-danger" href="<?= base_url('logout') ?>"><i class="fas fa-sign-out-alt me-2"></i> Keluar</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="<?= base_url('login') ?>" class="btn btn-primary rounded-pill px-4 fw-bold">Masuk</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="welcome-banner">
            <div style="max-width: 60%;">
                <h2 class="fw-bold mb-2">Halo, <?= session()->get('nama') ?? 'User' ?>! ✨</h2>
                <p class="mb-4 opacity-75">Pilih desain terbaik untuk mengabadikan momen sekolahmu!</p>
            </div>
            <img src="https://cdn-icons-png.flaticon.com/512/3429/3429153.png" alt="Books">
        </div>

        <h4 class="fw-bold mb-4">Tema Tersedia</h4>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="theme-card shadow-sm">
                    <div>
                        <div class="img-holder">
                            <img src="<?= base_url('uploads/templates/tema-scrapbook/1.jpg'); ?>" alt="Tema Scrapbook">
                        </div>
                        <h6 class="fw-bold m-0 text-dark">Tema Scrapbook</h6>
                        <span class="price-tag">Rp 50.000</span>
                    </div>
                    <a href="<?= base_url('katalog/scrapbook'); ?>" class="btn-detail" style="background: #a594f9; color: white;">Pilih Tema</a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="theme-card shadow-sm">
                    <div>
                        <div class="img-holder">
                            <img src="<?= base_url('uploads/templates/tema-game/1.jpg'); ?>" alt="Tema Game" onerror="this.src='https://via.placeholder.com/300x400?text=Tema+Game'">
                        </div>
                        <h6 class="fw-bold m-0 text-dark">Tema Game Retro</h6>
                        <span class="price-tag">Rp 50.000</span>
                    </div>
                    <a href="<?= base_url('katalog/game'); ?>" class="btn-detail" style="background: #a594f9; color: white;">Pilih Tema</a>
                </div>
            </div>
            <?php if (!empty($portfolios)): ?>
                <?php foreach ($portfolios as $p): ?>
                    <div class="col-md-3">
                        <div class="theme-card shadow-sm">
                            <div>
                                <div class="img-holder">
                                    <img src="<?= base_url('uploads/portfolio/' . ($p['gambar'] ?? 'default.jpg')) ?>" alt="<?= $p['nama_tema'] ?? 'Tema' ?>">
                                </div>
                                <h6 class="fw-bold m-0 text-dark"><?= $p['nama_tema'] ?? 'Tanpa Nama' ?></h6>
                                <span class="price-tag">Rp <?= number_format($p['harga'] ?? 0, 0, ',', '.') ?></span>
                            </div>
                            <a href="<?= base_url('order/create?id=' . ($p['id'] ?? '')) ?>" class="btn-detail">Pilih Tema</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Belum ada tema yang tersedia saat ini. 🎀</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>