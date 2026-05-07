<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan Imut 🎀 | @AULIA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Quicksand', sans-serif; 
            background: #fef1f6; 
            padding: 30px;
            background-image: radial-gradient(#ffccd5 1px, transparent 1px);
            background-size: 25px 25px;
        }
        .main-container { max-width: 900px; margin: 0 auto; }
        .alert-imut {
            background-color: #e0fbf1; color: #2d6a4f; padding: 15px;
            border-radius: 50px; border: 2px solid #b7e4c7;
            text-align: center; font-weight: 700; margin-bottom: 20px;
        }
        .card-history { 
            background: white; border-radius: 30px; border: 3px solid #ffccd5; 
            padding: 30px; box-shadow: 10px 10px 0px #ffccd5; 
        }
        h2 { color: #ff8fa3; font-weight: 700; text-align: center; margin-bottom: 30px; }
        .table { border-radius: 15px; overflow: hidden; }
        .thead-pink { background-color: #ff8fa3; color: white; }
        .status-badge {
            padding: 5px 12px; border-radius: 50px; font-size: 11px; font-weight: 700;
        }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .btn-kembali {
            display: inline-block; background: #ff8fa3; color: white;
            padding: 10px 25px; border-radius: 50px; text-decoration: none; font-weight: 700;
        }
    </style>
</head>
<body>

<div class="main-container">
    <?php if(session()->getFlashdata('msg')): ?>
        <div class="alert-imut">✨ <?= session()->getFlashdata('msg') ?> ✨</div>
    <?php endif; ?>

    <div class="card-history">
        <h2>✨ Keranjang Kenangan Aulia ✨</h2>
        
        <?php if (!empty($orders)): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center">
                    <thead class="thead-pink">
                        <tr>
                            <th>ID</th>
                            <th>Desain</th>
                            <th>Jumlah</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $row): ?>
                        <tr>
                            <td class="fw-bold">#<?= $row['id_order'] ?></td>
                            <td><span class="badge bg-light text-dark border"><?= $row['id_desain'] ?></span></td>
                            <td><?= $row['jumlah'] ?> pcs</td>
                            <td class="text-danger fw-bold">Rp <?= number_format($row['total_bayar'], 0, ',', '.') ?></td>
                            <td>
                                <span class="status-badge status-pending">
                                    <?= str_replace('_', ' ', $row['status_pesanan']); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-4">
                <p>Belum ada pesanan nih.. Yuk belanja! 🌸</p>
            </div>
        <?php endif; ?>

     <div class="text-center mt-4">
    <a href="<?= site_url('katalog'); ?>" class="btn-kembali">← Kembali ke Katalog</a>
</div>
</div>

</body>
</html>