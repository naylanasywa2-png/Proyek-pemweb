<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daftar Pesanan - Digital Memories</title>
    <style>
        body { background-color: #fff5f7; font-family: 'Segoe UI', sans-serif; padding: 40px; color: #555; }
        .container { background-color: white; padding: 30px; border-radius: 20px; box-shadow: 0 10px 25px rgba(255, 182, 193, 0.3); max-width: 1000px; margin: auto; }
        h2 { color: #ff85a2; text-align: center; }
        
        /* Style Tabel */
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #ffdae3; color: #d63384; padding: 15px; text-align: left; }
        td { padding: 15px; border-bottom: 1px solid #ffe4e9; }
        
        /* Badge & Tombol */
        .status-badge { 
            background: #ffdae3; 
            color: #d63384; 
            padding: 6px 12px; 
            border-radius: 20px; 
            font-size: 11px; 
            letter-spacing: 1px;
            font-weight: bold;
        }
        .btn-back { display: inline-block; margin-bottom: 20px; color: #ff85a2; text-decoration: none; font-weight: bold; }
        tr:hover { background-color: #fff9fa; transition: 0.3s; }

        /* Animasi Notifikasi */
        @keyframes slideDown {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body>
<div class="container">
    <a href="<?= base_url('logistik/tesongkir') ?>" class="btn-back">⬅ Kembali ke Cek Ongkir</a>
    
    <?php if (session()->getFlashdata('sukses')): ?>
        <div style="background: linear-gradient(135deg, #ff85a2, #ffacbd); color: white; padding: 15px; border-radius: 15px; text-align: center; margin-bottom: 25px; box-shadow: 0 5px 15px rgba(255, 133, 162, 0.4); animation: slideDown 0.5s ease;">
            <strong style="font-size: 18px;">🌸 <?= session()->getFlashdata('sukses') ?></strong>
            <p style="margin: 5px 0 0; font-size: 14px;">Silakan cek detail pesananmu di bawah ini ya!</p>
        </div>
    <?php endif; ?>

    <h2>📋 Riwayat Pesanan Buku Memori</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tanggal</th>
                <th>Ongkir</th>
                <th>Total Bayar</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $first = true; ?>
            <?php foreach ($semua_pesanan as $p): ?>
                <tr style="<?= $first && session()->getFlashdata('sukses') ? 'background-color: #fff0f3; border-left: 5px solid #ff85a2;' : '' ?>">
                    <td>#<?= $p['id_order'] ?></td>
                    <td><?= date('d M Y, H:i', strtotime($p['created_at'])) ?></td>
                    <td>Rp <?= number_format($p['ongkir'], 0, ',', '.') ?></td>
                    <td>
                        <strong>Rp <?= number_format($p['total_bayar'], 0, ',', '.') ?></strong>
                        <br>
                        <small style="color: #888;">(Buku + Ongkir)</small>
                    </td>
                    <td><span class="status-badge"><?= strtoupper($p['status_pesanan']) ?></span></td>
                    <td>
                        <a href="<?= base_url('logistik/hapus-pesanan/' . $p['id_order']) ?>" 
                           onclick="return confirm('Yakin mau hapus pesanan ini? 🌸')"
                           style="color: #ff4d7d; text-decoration: none; font-size: 14px; font-weight: bold;">
                           Hapus 🗑️
                        </a>
                    </td>
                </tr>
                <?php $first = false; ?>
            <?php endforeach; ?>
            
            <?php if (empty($semua_pesanan)): ?>
                <tr>
                    <td colspan="6" style="text-align: center;">Belum ada pesanan masuk.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>