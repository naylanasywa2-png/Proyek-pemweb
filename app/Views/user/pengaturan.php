<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MBook | Pengaturan Akun ⚙️</title>
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
            align-items: center; gap: 12px;
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

        .settings-card {
            background: white;
            border-radius: 25px;
            padding: 30px;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.02);
            margin-bottom: 30px;
        }

        .form-label { font-weight: 700; color: #4b4b4b; font-size: 0.9rem; }
        .form-control { 
            border-radius: 12px; 
            padding: 12px 20px; 
            border: 1px solid #eee; 
            background: #fcfaff; 
        }

        .btn-save {
            background: var(--purple-primary);
            color: white;
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 700;
            border: none;
            transition: 0.3s;
        }

        .btn-save:hover {
            background: #8e7cf0;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(165, 148, 249, 0.3);
            color: white;
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
            <a href="<?= base_url('katalog') ?>" class="nav-link-custom"><i class="fas fa-house"></i> Beranda</a>
            <a href="<?= base_url('user/history') ?>" class="nav-link-custom"><i class="fas fa-shopping-bag"></i> Pesanan Saya</a>
            <a href="<?= base_url('pembayaran') ?>" class="nav-link-custom"><i class="fas fa-wallet"></i> Pembayaran</a>
            <a href="<?= base_url('pengaturan') ?>" class="nav-link-custom active"><i class="fas fa-user-gear"></i> Pengaturan</a>
        </div>
    </div>

    <div class="main-wrapper">
        <h3 class="fw-bold mb-4">Pengaturan Akun ⚙️</h3>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-8">
                <div class="settings-card">
                    <h5 class="fw-bold mb-4">👤 Data Profil</h5>
                    <form action="#" method="POST">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" name="nama" value="<?= session()->get('nama') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" value="<?= session()->get('username') ?>" readonly style="background-color: #f1f1f1; cursor: not-allowed;">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Alamat Sekolah / Pengiriman</label>
                            <textarea class="form-control" name="alamat" rows="3" placeholder="Contoh: SMA Negeri 1 Surabaya, Jl. Wijaya Kusuma No. 48"></textarea>
                        </div>
                        <button type="submit" class="btn btn-save shadow-sm">Simpan Profil ✨</button>
                    </form>
                </div>

                <div class="settings-card">
                    <h5 class="fw-bold mb-4">🔒 Keamanan</h5>
                    <form action="#" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Kata Sandi Lama</label>
                            <input type="password" class="form-control" name="old_password">
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Kata Sandi Baru</label>
                                <input type="password" class="form-control" name="new_password">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Konfirmasi Kata Sandi</label>
                                <input type="password" class="form-control" name="confirm_password">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-save" style="background: var(--pink-soft);">Update Password</button>
                    </form>
                </div>
            </div>

            <div class="col-md-4">
                <div class="settings-card text-center" style="background: var(--gradient-banner); color: white;">
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode(session()->get('nama')) ?>&background=fff&color=a594f9&size=128" width="100" class="rounded-circle mb-3 shadow">
                    <h6 class="fw-bold mb-1"><?= session()->get('nama') ?></h6>
                    <p class="small opacity-75">Status: Akun <?= ucfirst(session()->get('role')) ?></p>
                    <hr class="my-4">
                    <a href="<?= base_url('logout') ?>" class="btn btn-light btn-sm w-100 rounded-pill fw-bold py-2 text-danger">Keluar Sesi</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>