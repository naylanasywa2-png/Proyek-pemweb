<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Kendali Admin | MBook ✨</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body { 
            background-color: #f4f6f9; 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            margin: 0;
        }
        /* Style Premium untuk Sidebar Admin */
        .sidebar { 
            width: 260px; 
            height: 100vh; 
            position: fixed; 
            background: #1e293b; 
            color: white; 
            padding: 30px 20px; 
            display: flex;
            flex-direction: column;
            z-index: 1000;
        }
        .sidebar-brand {
            font-size: 1.3rem;
            font-weight: 800;
            color: #38bdf8;
            text-decoration: none;
            margin-bottom: 40px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sidebar a { 
            color: #94a3b8; 
            text-decoration: none; 
            padding: 12px 15px; 
            display: flex; 
            align-items: center;
            gap: 12px;
            font-weight: 600; 
            border-radius: 8px;
            margin-bottom: 8px;
            transition: 0.3s;
        }
        .sidebar a:hover, .sidebar a.active { 
            background: #334155; 
            color: white; 
        }
        .sidebar a.active {
            border-left: 4px solid #38bdf8;
        }
        /* Area Konten Utama */
        .main-content { 
            margin-left: 260px; 
            padding: 40px; 
            width: calc(100% - 260px);
        }
        .card-stat { 
            border: none; 
            border-radius: 16px; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.02); 
            transition: 0.3s; 
        }
        .card-stat:hover { 
            transform: translateY(-5px); 
        }
        .icon-box {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <a href="<?= base_url('admin/dashboard') ?>" class="sidebar-brand">
            <i class="fas fa-shield-halved"></i>
            <span>MBook Master 👑</span>
        </a>
        <a href="<?= base_url('admin/dashboard') ?>" class="active">
            <i class="fas fa-chart-pie"></i> Dashboard
        </a>
        <a href="<?= base_url('admin/transaksi') ?>">
            <i class="fas fa-wallet"></i> Konfirmasi Bayar
        </a>
        <a href="<?= base_url('katalog') ?>" target="_blank">
            <i class="fas fa-globe"></i> Lihat Website
        </a>
        
        <a href="<?= base_url('logout') ?>" class="text-danger mt-auto">
            <i class="fas fa-sign-out-alt"></i> Keluar (Logout)
        </a>
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold m-0 text-dark">Panel Kendali Admin</h2>
                <p class="text-muted m-0">Master Dashboard Kelola Data Sistem</p>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card card-stat bg-white p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1 fw-bold small text-uppercase">Total Pengguna</h6>
                            <h3 class="fw-bold m-0 text-dark"><?= $total_user ?? 0 ?></h3>
                        </div>
                        <div class="icon-box bg-primary bg-opacity-10 text-primary">
                            <i class="fas fa-users fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-stat bg-white p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1 fw-bold small text-uppercase">Total Pesanan</h6>
                            <h3 class="fw-bold m-0 text-dark"><?= $total_pesanan ?? 0 ?></h3>
                        </div>
                        <div class="icon-box bg-success bg-opacity-10 text-success">
                            <i class="fas fa-shopping-basket fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-stat bg-white p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1 fw-bold small text-uppercase">Total Omset</h6>
                            <h3 class="fw-bold m-0 text-success">Rp <?= number_format($total_pendapatan ?? 0, 0, ',', '.') ?></h3>
                        </div>
                        <div class="icon-box bg-warning bg-opacity-10 text-warning">
                            <i class="fas fa-sack-dollar fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 p-4 rounded-4 shadow-sm bg-white">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold m-0 text-dark"><i class="fas fa-list-check me-2 text-primary"></i> Aktivitas Transaksi Terkini</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light text-muted small text-uppercase">
                        <tr>
                            <th>ID Pesanan</th>
                            <th>ID User</th>
                            <th>Total Bayar</th>
                            <th>Status Pesanan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($semua_user)): ?>
                            <?php foreach ($semua_user as $key => $u): ?>
                            <tr>
                                <td class="fw-bold text-dark">#<?= $key + 1 ?></td>
                                <td><span class="badge bg-light text-dark px-3 py-2 rounded-pill">User ID: <?= $u['id_user'] ?? 'N/A' ?></span></td>
                                <td class="fw-bold text-dark">Rp <?= number_format(150000, 0, ',', '.') ?></td>
                                <td>
                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill small">Pending</span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Belum ada data pesanan masuk.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>