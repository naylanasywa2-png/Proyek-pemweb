<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna | Admin Panel ✨</title>
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
            <h2 class="fw-bold m-0 text-slate-800">Manajemen Pengguna</h2>
            <p class="text-muted m-0">Kontrol, blokir, atau hapus akun pembeli dan vendor dari sistem</p>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center gap-2">
                <i class="fas fa-circle-check fs-5"></i>
                <span class="fw-semibold"><?= session()->getFlashdata('success') ?></span>
            </div>
        <?php endif; ?>

        <div class="card border-0 p-4 table-container bg-white">
            <h5 class="fw-bold mb-4 text-dark d-flex align-items-center gap-2">
                <i class="fas fa-users text-primary"></i> Semua Pengguna Terdaftar
            </h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle m-0">
                    <thead class="table-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-3">ID Pengguna</th>
                            <th>Nama Lengkap</th>
                            <th>Email Resmi</th>
                            <th>Hak Akses (Role)</th>
                            <th class="text-center pe-3">Tindakan Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($semua_user)): ?>
                            <?php foreach ($semua_user as $u): ?>
                            <tr>
                                <td class="fw-bold text-dark ps-3">#<?= $u['id_user'] ?></td>
                                <td class="fw-semibold text-slate-700"><?= $u['nama'] ?? 'Pengguna MBook' ?></td>
                                <td class="text-secondary"><?= $u['email'] ?></td>
                                <td>
                                    <?php if(isset($u['role']) && $u['role'] == 'vendor'): ?>
                                        <span class="badge px-3 py-2 rounded-pill fw-bold" style="color: #6b21a8; background-color: #f3e8ff;">Vendor</span>
                                    <?php else: ?>
                                        <span class="badge px-3 py-2 rounded-pill fw-bold" style="color: #0369a1; background-color: #e0f2fe;">Pembeli (User)</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center pe-3">
                                    <a href="<?= base_url('admin/bannedUser/' . $u['id_user']) ?>" 
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus akun ini permanen? All data transaksi user ini juga akan terpengaruh.')" 
                                       class="btn btn-sm btn-outline-danger px-3 rounded-pill fw-semibold">
                                        <i class="fas fa-user-slash me-1"></i> Banned Account
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">Sistem mendeteksi belum ada database user terdaftar.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>