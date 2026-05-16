<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Kendali Admin | Admin Panel ✨</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body { background-color: #f8fafc; font-family: 'Plus Jakarta Sans', sans-serif; margin: 0; }
        .sidebar { width: 280px; height: 100vh; position: fixed; background: #0f172a; color: white; padding: 30px 20px; display: flex; flex-direction: column; z-index: 1000; box-shadow: 4px 0 10px rgba(0,0,0,0.05); }
        .sidebar-brand { font-size: 1.4rem; font-weight: 800; color: #38bdf8; text-decoration: none; margin-bottom: 40px; display: flex; align-items: center; gap: 12px; }
        .sidebar a { color: #94a3b8; text-decoration: none; padding: 14px 16px; display: flex; align-items: center; gap: 14px; font-weight: 600; border-radius: 10px; margin-bottom: 8px; transition: all 0.3s ease; }
        .sidebar a:hover { background: #1e293b; color: #f8fafc; }
        .sidebar a.active { background: #0284c7; color: white; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3); }
        .main-content { margin-left: 280px; padding: 40px; width: calc(100% - 280px); }
        .stat-card { background: white; border: 1px solid #f1f5f9; border-radius: 20px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.01); }
        .table-container { border: 1px solid #f1f5f9; border-radius: 20px; overflow: hidden; background: white; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.01); }
    </style>
</head>
<body>

    <div class="sidebar">
        <a href="<?= base_url('admin/dashboard') ?>" class="sidebar-brand">
            <i class="fas fa-shield-halved"></i>
            <span>MBook Master 👑</span>
        </a>
        <a href="<?= base_url('admin/dashboard') ?>" class="<?= url_is('admin/dashboard') ? 'active' : '' ?>">
            <i class="fas fa-chart-pie"></i> Dashboard Utama
        </a>
        <a href="<?= base_url('admin/transaksi') ?>" class="<?= url_is('admin/transaksi') ? 'active' : '' ?>">
            <i class="fas fa-receipt"></i> Konfirmasi Bayar
        </a>
        <a href="<?= base_url('admin/users') ?>" class="<?= url_is('admin/users') ? 'active' : '' ?>">
            <i class="fas fa-user-gear"></i> Kelola Pengguna
        </a>
        <a href="<?= base_url('katalog') ?>" target="_blank">
            <i class="fas fa-arrow-up-right-from-square"></i> Lihat Website
        </a>
        
        <a href="<?= base_url('logout') ?>" class="text-danger mt-auto">
            <i class="fas fa-sign-out-alt"></i> Keluar Sistem
        </a>
    </div>

    <div class="main-content">
        <div class="mb-4">
            <h2 class="fw-bold m-0 text-slate-800">Ringkasan Sistem</h2>
            <p class="text-muted m-0">Pantau statistik utama dan aktivitas transaksi terkini</p>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="stat-card d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small text-uppercase fw-bold mb-1">Total Pengguna</p>
                        <h3 class="fw-extrabold m-0 text-dark"><?= esc($total_user ?? 0) ?></h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-3">
                        <i class="fas fa-users fs-4"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small text-uppercase fw-bold mb-1">Total Pesanan</p>
                        <h3 class="fw-extrabold m-0 text-dark"><?= esc($total_pesanan ?? 0) ?></h3>
                    </div>
                    <div class="bg-success bg-opacity-10 text-success p-3 rounded-3">
                        <i class="fas fa-shopping-basket fs-4"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small text-uppercase fw-bold mb-1">Total Omset</p>
                        <h3 class="fw-extrabold m-0 text-success">Rp <?= number_format($total_omset ?? 0, 0, ',', '.') ?></h3>
                    </div>
                    <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-3">
                        <i class="fas fa-wallet fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 p-4 table-container bg-white">
            <h5 class="fw-bold mb-4 text-dark d-flex align-items-center gap-2">
                <i class="fas fa-list-check text-primary"></i> Aktivitas Transaksi Terkini
            </h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle m-0">
                    <thead class="table-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-3">ID Pesanan</th>
                            <th>ID User</th>
                            <th>Total Bayar</th>
                            <th>Status Pesanan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($semua_pesanan)): ?>
                            <?php foreach ($semua_pesanan as $p): ?>
                            <tr>
                                <td class="fw-bold text-dark ps-3">#<?= $p['id_order'] ?></td>
                                <td class="fw-semibold text-slate-700">#<?= $p['id_user'] ?></td>
                                <td class="text-secondary fw-semibold">Rp <?= number_format($p['total_bayar'], 0, ',', '.') ?></td>
                                <td>
                                    <?php if($p['status_pesanan'] == 'success'): ?>
                                        <span class="badge bg-success px-3 py-2 rounded-pill">Selesai</span>
                                    <?php elseif($p['status_pesanan'] == 'pending_payment'): ?>
                                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Menunggu Pembayaran</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary px-3 py-2 rounded-pill"><?= $p['status_pesanan'] ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">Belum ada data aktivitas transaksi di database.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>