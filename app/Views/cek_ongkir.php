<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cek Ongkir - Digital Memories</title>
    <style>
        body { background-color: #fff5f7; font-family: 'Segoe UI', sans-serif; padding: 40px; color: #555; }
        .container { background-color: white; padding: 30px; border-radius: 20px; box-shadow: 0 10px 25px rgba(255, 182, 193, 0.3); max-width: 900px; margin: auto; }
        h2 { color: #ff85a2; text-align: center; }
        
        /* Style untuk Form */
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #d63384; font-weight: bold; }
        select, input { width: 100%; padding: 10px; border: 1px solid #ffdae3; border-radius: 10px; outline: none; }
        input:focus, select:focus { border-color: #ff85a2; }
        .btn-cek { background-color: #ff85a2; color: white; border: none; padding: 12px 20px; border-radius: 10px; cursor: pointer; width: 100%; font-weight: bold; margin-top: 10px; }
        .btn-cek:hover { background-color: #ff4d7d; }

        /* Style Tabel */
        table { width: 100%; border-collapse: collapse; margin-top: 30px; }
        th { background-color: #ffdae3; color: #d63384; padding: 15px; }
        td { padding: 15px; border-bottom: 1px solid #ffe4e9; text-align: center; }
        .kurir-name { font-weight: bold; color: #ff85a2; }
        .price { color: #ff4d7d; font-weight: bold; }
    </style>
</head>
<body>
<div class="container">
    <h2>🌸 Cek Biaya Kirim Buku Memori</h2>
    
    <form action="<?= base_url('logistik/tesongkir') ?>" method="post">
    <?= csrf_field() ?> 
        <div class="form-group">
            <label>Kota Tujuan:</label>
            <select name="tujuan">
                <option value="151">Jakarta Barat</option>
                <option value="152">Jakarta Pusat</option>
                <option value="153">Jakarta Selatan</option>
                <option value="444">Surabaya</option>
                <option value="23">Bandung</option>
            </select>
        </div>
        <div class="form-group">
            <label>Berat Barang (Gram):</label>
            <input type="number" name="berat" value="1000" min="100">
        </div>
        <button type="submit" class="btn-cek">Cek Ongkos Kirim ✨</button>
    </form>

   <?php if (isset($results) && isset($results['data'])): ?>
<table>
    <thead>
        <tr>
            <th>Kurir</th>
            <th>Layanan</th>
            <th>Estimasi</th>
            <th>Biaya</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($results['data']['costs'] as $c): ?>
            <tr>
                <td class="kurir-name"><?= strtoupper($results['data']['courier']) ?></td>
                <td><?= $c['service'] ?></td>
                <td><?= $c['estimated'] ?> Hari</td>
                <td class="price">Rp <?= number_format($c['price'], 0, ',', '.') ?></td>
                <td>
                    <form action="<?= base_url('logistik/simpan-pesanan') ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="biaya" value="<?= $c['price'] ?>">
                        <input type="hidden" name="id_desain" value="1"> 
                        <button type="submit" class="btn-cek" style="padding: 5px 10px; font-size: 12px; width: auto; margin-top: 0;">Pilih ✨</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php elseif (isset($results) && $results['status'] != 200): ?>
    <p style="text-align:center; color:red;">Oops! <?= $results['message'] ?></p>
    <?php endif; ?>
</div>
</body>
</html>