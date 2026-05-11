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

        .nav-menu {
            list-style: none;
            padding: 0;
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

        .filter-container {
            display: flex;
            gap: 12px;
            margin-bottom: 30px;
            overflow-x: auto;
            padding-bottom: 5px;
        }

        .filter-btn {
            background: white;
            border: 1px solid #eee;
            padding: 8px 22px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #6c757d;
            cursor: pointer;
            transition: 0.3s;
            white-space: nowrap;
        }

        .filter-btn:hover, .filter-btn.active {
            background: var(--purple-primary);
            color: white;
            border-color: var(--purple-primary);
        }

        .theme-card {
            background: white;
            border-radius: 22px;
            padding: 15px;
            transition: 0.4s;
            text-align: center;
            border: none;
            height: 100%;
        }

        .theme-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.06);
        }

        .img-holder {
            border-radius: 18px;
            height: 160px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            position: relative;
            overflow: hidden;
        }

        .img-holder img {
            max-width: 80%;
            max-height: 120px;
            object-fit: contain;
            transition: 0.3s;
        }

        .bg-v { background: #ffe9f0; }
        .bg-u { background: #e3f2fd; }
        .bg-a { background: #f1f8e9; }
        .bg-m { background: #fff3e0; }

        .btn-detail {
            background: #f8f7fd;
            color: var(--purple-primary);
            padding: 8px 0;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 700;
            text-decoration: none;
            display: block;
            width: 85%;
            margin: 10px auto 0;
            transition: 0.3s;
            border: 1px solid #f0eeff;
        }

        .btn-detail:hover {
            background: var(--purple-primary);
            color: white;
        }

        .btn-login-header {
            background: var(--purple-primary);
            color: white !important;
            padding: 8px 25px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.9rem;
            text-decoration: none;
            transition: 0.3s;
            box-shadow: 0 4px 10px rgba(165, 148, 249, 0.2);
        }
        
        .btn-login-header:hover {
            background: #8e7cf0;
            transform: scale(1.05);
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
            <a href="#" class="nav-link-custom"><i class="fas fa-user-gear"></i> Pengaturan</a>
        </div>
        
        <div class="mt-auto p-3 text-center" style="background: #fdf2f8; border-radius: 20px;">
            <p class="small text-muted mb-2">Mau fitur lebih?</p>
            <button class="btn btn-sm w-100 fw-bold" style="background: var(--pink-soft); color: white; border-radius: 12px;">Upgrade ✨</button>
        </div>
    </div>

    <div class="main-wrapper">
        
        <div class="header-top">
            <div class="search-box">
                <i class="fas fa-search text-muted"></i>
                <input type="text" placeholder="Cari tema favoritmu...">
            </div>
            
            <div class="d-flex align-items-center gap-4">
                <span class="badge bg-danger rounded-pill px-3">LIVE</span>
                <i class="far fa-bell fs-5 text-muted"></i>

                <?php if (session()->get('logged_in')): ?>
                    <div class="dropdown">
                        <div class="d-flex align-items-center gap-2 bg-white p-1 pe-3 rounded-pill shadow-sm border" style="cursor: pointer;" data-bs-toggle="dropdown">
                            <img src="https://ui-avatars.com/api/?name=<?= session()->get('nama') ?>&background=a594f9&color=fff" width="35" class="rounded-circle">
                            <span class="small fw-bold text-dark"><?= session()->get('nama') ?></span>
                            <i class="fas fa-chevron-down small text-muted"></i>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2" style="border-radius: 15px;">
                            <li><a class="dropdown-item py-2" href="<?= base_url('logout') ?>"><i class="fas fa-sign-out-alt me-2 text-danger"></i> Keluar</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="<?= base_url('login') ?>" class="btn-login-header">
                        Masuk <i class="fas fa-sign-in-alt ms-1"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="welcome-banner">
            <div style="max-width: 60%;">
                <h2 class="fw-bold mb-2">Halo, <?= session()->get('logged_in') ? session()->get('nama') : 'Teman' ?>! 🎀</h2>
                <p class="mb-4 opacity-75">Cari dan pilih tema yearbook estetik untuk kenangan sekolahmu di sini.</p>
                <a href="#" class="btn btn-light rounded-pill px-4 fw-bold text-primary shadow-sm">Lihat Panduan</a>
            </div>
            <img src="https://cdn-icons-png.flaticon.com/512/3429/3429153.png" alt="Books">
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <div class="filter-container">
            <div class="filter-btn active">Semua Tema</div>
            <div class="filter-btn">Vintage</div>
            <div class="filter-btn">Urban Street</div>
            <div class="filter-btn">Modern Academy</div>
            <div class="filter-btn">Classic Mafia</div>
        </div>

        <div class="row g-4">
            <div class="col-md-3">
                <div class="theme-card shadow-sm">
                    <div class="img-holder bg-v">
                        <img src="https://cdn-icons-png.flaticon.com/512/681/681392.png" alt="Vintage">
                        <span class="badge bg-danger position-absolute" style="top: 12px; right: 12px; border-radius: 50px;">Best</span>
                    </div>
                    <h6 class="fw-bold m-0">Vintage Class</h6>
                    <a href="<?= base_url('katalog/vintage') ?>" class="btn-detail">Detail</a>
                </div>
            </div>

            <div class="col-md-3">
                <div class="theme-card shadow-sm">
                    <div class="img-holder bg-u">
                        <img src="https://cdn-icons-png.flaticon.com/512/2742/2742634.png" alt="Urban">
                    </div>
                    <h6 class="fw-bold m-0">Urban Street</h6>
                    <a href="<?= base_url('katalog/streetwear') ?>" class="btn-detail">Detail</a>
                </div>
            </div>

            <div class="col-md-3">
                <div class="theme-card shadow-sm">
                    <div class="img-holder bg-a">
                        <img src="https://cdn-icons-png.flaticon.com/512/201/201108.png" alt="Academy">
                    </div>
                    <h6 class="fw-bold m-0">Grand Academy</h6>
                    <a href="<?= base_url('katalog/grand-academy') ?>" class="btn-detail">Detail</a>
                </div>
            </div>

            <div class="col-md-3">
                <div class="theme-card shadow-sm">
                    <div class="img-holder bg-m">
                        <img src="https://cdn-icons-png.flaticon.com/512/3255/3255160.png" alt="Mafia">
                    </div>
                    <h6 class="fw-bold m-0">Mafia Style</h6>
                    <a href="<?= base_url('katalog/mafia') ?>" class="btn-detail">Detail</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>