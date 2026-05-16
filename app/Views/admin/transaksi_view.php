<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MBook Master | Manajemen Transaksi 💳</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f7fd; font-family: 'Plus Jakarta Sans', sans-serif; }
        .sidebar { background-color: #111c43; min-height: 100vh; color: #fff; }
        .sidebar .nav-link { color: #a3b1cc; transition: 0.3s; padding: 12px 20px; border-radius: 8px; margin: 4px 15px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background-color: #1a2758; color: #fff; font-weight: 600; }
        .sidebar .nav-link.active { background-color: #007bff; }
        .main-content { padding: 40px; }
        .card-custom { border-radius: 15px; border: none; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
        .badge-waiting { background-color: #fff3cd; color: #856404; }
        .badge-success { background-color: #d4edda; color: #155724; }
        .badge-info { background-color: #e0f2fe; color: #0369a1; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 px-0 sidebar d-flex flex-column justify-content-between">
            <div>
                <div class="p-4">
                    <h5 class="fw-bold m-0 text-white">MBook Master 👑</h5>
                </div>
                <ul class="nav flex-column mt-3">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('admin/dashboard') ?>"><i class="fas fa-chart-pie me-2"></i> Dashboard Utama</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= base_url('admin/transaksi') ?>"><i class="fas fa-wallet me-2"></i> Konfirmasi Bayar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('admin/users') ?>"><i class="fas fa-users me-2"></i> Kelola Pengguna</a>
                    </li>
                </ul>
            </div>
            <div class="p-3">
                <a href="<?= base_url('logout') ?>" class="nav-link text-danger w-100 m-0"><i class="fas fa-sign-out-alt me-2"></i> Keluar Sistem</a>
            </div>
        </div>

        <div class="col-md-10 main-content">
            <h3 class="fw-bold text-dark mb-1">Konfirmasi Pembayaran</h3>
            <p class="text-muted small mb-4">Verifikasi bukti transfer pesanan album kenangan pembeli</p>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card card-custom p-4 bg-white">
                <h5 class="fw-bold mb-4 text-secondary"><i class="fas fa-list-check me-2"></i> Semua Pesanan & Transaksi</h5>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID PESANAN</th>
                                <th>ID USER</th>
                                <th>TOTAL BAYAR</th>
                                <th>STATUS PESANAN</th>
                                <th class="text-center">TINDAKAN ADMIN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($orders)): ?>
                                <?php foreach ($orders as $row): ?>
                                    <?php 
                                        // Deteksi nilai status dari kolom status_pesanan atau status secara fleksibel
                                        $statusAktif = $row['status_pesanan'] ?? $row['status'] ?? 'waiting_verification';
                                    ?>
                                    <tr>
                                        <td class="fw-bold">#<?= $row['id_order'] ?></td>
                                        <td>User #<?= $row['id_user'] ?></td>
                                        <td class="fw-bold text-success">Rp <?= number_format($row['total_harga'] ?? $row['total_bayar'] ?? 150000, 0, ',', '.') ?></td>
                                        <td>
                                            <?php if ($statusAktif == 'waiting_verification'): ?>
                                                <span class="badge badge-waiting px-3 py-2 rounded-pill small">Waiting Verification</span>
                                            <?php elseif ($statusAktif == 'diproses'): ?>
                                                <span class="badge badge-info px-3 py-2 rounded-pill small"><i class="fas fa-spinner fa-spin me-1"></i> Sedang Diproses</span>
                                            <?php else: ?>
                                                <span class="badge badge-success px-3 py-2 rounded-pill small"><?= esc($statusAktif) ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <?php 
                                                    $namaGambar = $row['bukti_transfer'] ?? $row['bukti_bayar'] ?? $row['bukti'] ?? ''; 
                                                ?>
                                                
                                                <?php if (!empty($namaGambar)): ?>
                                                    <a href="<?= base_url('uploads/bukti_bayar/' . $namaGambar) ?>" target="_blank" class="btn btn-sm btn-info text-white rounded-pill px-3">
                                                        <i class="fas fa-eye me-1"></i> Lihat Bukti TF
                                                    </a>
                                                <?php else: ?>
                                                    <button class="btn btn-sm btn-secondary rounded-pill px-3" disabled>Belum Upload</button>
                                                <?php endif; ?>

                                                <?php if ($statusAktif == 'waiting_verification'): ?>
                                                    <a href="<?= base_url('admin/transaksi/setujui/' . $row['id_order']) ?>" class="btn btn-sm btn-success rounded-pill px-3">
                                                        <i class="fas fa-check me-1"></i> Setujui
                                                    </a>
                                                <?php else: ?>
                                                    <button class="btn btn-sm btn-light border rounded-pill px-3" disabled>
                                                        <i class="fas fa-check-double text-muted me-1"></i> Sudah Disetujui
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada data aktivitas transaksi di database.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>