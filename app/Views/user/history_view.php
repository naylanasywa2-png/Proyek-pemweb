<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya | MBook ✨</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { 
            background-color: #f3f0ff; 
            font-family: 'Plus Jakarta Sans', sans-serif; 
        }
        .container {
            max-width: 950px;
        }
        .table-container {
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 10px 30px rgba(165, 148, 249, 0.1);
        }
        .badge-status { 
            border-radius: 50px; 
            padding: 8px 15px; 
            font-size: 0.8rem;
            text-transform: capitalize;
            font-weight: 600;
        }
        .btn-download {
            font-size: 0.85rem;
            padding: 6px 15px;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold m-0" style="color: #6f42c1;">📦 Pesanan Saya</h2>
            <a href="<?= base_url('katalog') ?>" class="btn btn-outline-primary btn-sm rounded-pill">Kembali ke Katalog</a>
        </div>
        
        <?php if (empty($pesanan)): ?>
            <div class="text-center py-5 bg-white rounded-4 shadow-sm">
                <img src="https://cdn-icons-png.flaticon.com/512/11329/11329061.png" alt="Empty" width="120" class="mb-3 opacity-50">
                <p class="text-muted">Kamu belum punya pesanan nih. Yuk pilih tema dulu! 🎀</p>
                <a href="<?= base_url('katalog') ?>" class="btn btn-primary rounded-pill px-4">Lihat Katalog</a>
            </div>
        <?php else: ?>
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr class="text-muted small text-uppercase">
                                <th class="border-0">Tema</th>
                                <th class="border-0">Tanggal Pesan</th>
                                <th class="border-0">Status</th>
                                <th class="border-0 text-center">Aksi</th> </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pesanan as $p): ?>
                            <tr>
                                <td class="fw-bold text-dark">
                                    <?= esc($p['nama_tema'] ?? 'Tema tidak ditemukan') ?>
                                </td>
                                
                                <td class="text-muted small">
                                    <?= date('d M Y, H:i', strtotime($p['created_at'])) ?>
                                </td>
                                
                                <td>
                                    <?php 
                                        $status = $p['status_pesanan'] ?? 'pending';
                                        $badgeClass = ($status == 'selesai') ? 'bg-success' : (($status == 'diproses') ? 'bg-primary' : 'bg-warning text-dark');
                                    ?>
                                    <span class="badge <?= $badgeClass ?> badge-status">
                                        <?= $status ?>
                                    </span>
                                </td>

                                <td class="text-center">
                                    <?php if ($status == 'selesai' && !empty($p['file_desain'])): ?>
                                        <a href="<?= base_url('uploads/results/' . $p['file_desain']) ?>" class="btn btn-sm btn-success rounded-pill btn-download shadow-sm" download>
                                            <i class="fa-solid fa-cloud-arrow-down me-1"></i> Unduh Album
                                        </a>
                                    <?php elseif ($status == 'diproses'): ?>
                                        <span class="text-muted small italic"><i class="fa-solid fa-spinner fa-spin me-1"></i> Sedang didesain...</span>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>