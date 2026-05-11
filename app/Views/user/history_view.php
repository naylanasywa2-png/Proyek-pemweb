<!DOCTYPE html>
<html lang="id">
<head>
    <title>Pesanan Saya | MBook ✨</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f3f0ff; font-family: 'Plus Jakarta Sans', sans-serif; }
        .card-order { border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(165, 148, 249, 0.1); margin-bottom: 20px; }
        .badge-status { border-radius: 50px; padding: 8px 20px; }
    </style>
</head>
<body>
    <div class="container py-5">
        <h2 class="fw-bold mb-4" style="color: #6f42c1;">📦 Pesanan Saya</h2>
        
        <?php if (empty($pesanan)): ?>
            <div class="text-center py-5">
                <p class="text-muted">Kamu belum punya pesanan nih. Yuk pilih tema dulu! 🎀</p>
                <a href="<?= base_url('katalog') ?>" class="btn btn-primary rounded-pill">Lihat Katalog</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover bg-white rounded-4 overflow-hidden">
                    <thead class="table-light">
                        <tr>
                            <th>Tema</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pesanan as $p): ?>
                        <tr>
                            <td class="fw-bold"><?= $p['nama_tema'] ?></td>
                            <td><?= $p['tanggal'] ?></td>
                            <td><span class="badge bg-success badge-status"><?= $p['status'] ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>