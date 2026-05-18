<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pesanan - Digital Memories</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: linear-gradient(135deg, #fff5f7 0%, #ffe4e9 100%);
            font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
            min-height: 100vh;
            padding: 40px 20px;
            color: #555;
        }

        .container {
            background: white;
            padding: 35px 40px;
            border-radius: 24px;
            box-shadow: 0 15px 40px rgba(255, 107, 149, 0.15);
            max-width: 1050px;
            margin: auto;
        }

        /* Header */
        .page-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 28px; flex-wrap: wrap; gap: 12px;
        }
        h2 { color: #ff4d7d; font-size: 1.5rem; font-weight: 800; }
        .btn-cek-ongkir {
            display: inline-flex; align-items: center; gap: 6px;
            background: linear-gradient(135deg, #ff85a2, #ff4d7d);
            color: white; text-decoration: none; font-weight: 700;
            font-size: 0.85rem; padding: 9px 18px;
            border-radius: 10px; transition: transform 0.2s;
        }
        .btn-cek-ongkir:hover { transform: translateY(-2px); }

        /* Alerts */
        .alert { padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; font-size: 0.9rem; animation: slideDown 0.4s ease; }
        .alert-success { background: #f0fff4; color: #16a34a; border: 1px solid #86efac; }
        .alert-error   { background: #fff0f3; color: #cc3366; border: 1px solid #ffb3cc; }
        @keyframes slideDown { from { transform: translateY(-12px); opacity: 0; } to { transform: none; opacity: 1; } }

        /* Tabel */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 700px; }
        thead tr { background: linear-gradient(135deg, #ff85a2, #ff4d7d); }
        th {
            color: white; padding: 13px 16px; text-align: left;
            font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700;
        }
        th:first-child { border-radius: 12px 0 0 12px; }
        th:last-child  { border-radius: 0 12px 12px 0; text-align: center; }
        td { padding: 14px 16px; border-bottom: 1px solid #ffe4e9; font-size: 0.88rem; vertical-align: middle; }
        td:last-child { text-align: center; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #fff8f9; }

        /* Badges */
        .kurir-badge { background: #ffe4e9; color: #ff4d7d; padding: 3px 10px; border-radius: 20px; font-weight: 700; font-size: 0.74rem; }
        .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 0.74rem; font-weight: 700; text-transform: uppercase; }
        .status-pending   { background: #fff3cd; color: #856404; }
        .status-dibayar   { background: #d1ecf1; color: #0c5460; }
        .status-diproses  { background: #cff4fc; color: #055160; }
        .status-cetak     { background: #e2d9f3; color: #5a4e8e; }
        .status-dikirim   { background: #d1e7dd; color: #0a3622; }
        .status-selesai   { background: #d1e7dd; color: #0a3622; }
        .status-batal     { background: #f8d7da; color: #842029; }

        /* Tombol hapus */
        .btn-hapus {
            background: none; border: 2px solid #ffdae3; color: #ff4d7d;
            padding: 6px 14px; border-radius: 8px; cursor: pointer;
            font-size: 0.78rem; font-weight: 700; font-family: inherit;
            transition: all 0.15s;
        }
        .btn-hapus:hover    { background: #fff0f3; border-color: #ff85a2; }
        .btn-hapus:disabled { opacity: 0.4; cursor: not-allowed; }

        /* Empty state */
        .empty-state { text-align: center; padding: 60px 20px; color: #ddd; }
        .empty-state .icon { font-size: 3.5rem; margin-bottom: 16px; }
        .empty-state p { font-size: 0.95rem; color: #bbb; }
        .empty-state a {
            display: inline-block; margin-top: 16px;
            background: linear-gradient(135deg, #ff85a2, #ff4d7d);
            color: white; padding: 10px 24px; border-radius: 10px;
            text-decoration: none; font-weight: 700; font-size: 0.9rem;
            transition: transform 0.2s;
        }
        .empty-state a:hover { transform: translateY(-2px); }

        @media (max-width: 500px) { .container { padding: 24px 16px; } }
    </style>
</head>
<body>
<div class="container">

    <div class="page-header">
        <h2>📋 Riwayat Pesanan Buku Memori</h2>
        <a href="<?= base_url('logistik/tesongkir') ?>" class="btn-cek-ongkir">+ Pesan Baru</a>
    </div>

    <?php if ($flash = session()->getFlashdata('sukses')): ?>
        <div class="alert alert-success">✅ <?= esc($flash) ?></div>
    <?php endif; ?>
    <?php if ($flash = session()->getFlashdata('error')): ?>
        <div class="alert alert-error">❌ <?= esc($flash) ?></div>
    <?php endif; ?>

    <?php if (empty($semua_pesanan)): ?>
        <div class="empty-state">
            <div class="icon">📭</div>
            <p>Belum ada pesanan. Yuk mulai pesan buku memorimu!</p>
            <a href="<?= base_url('logistik/tesongkir') ?>">Cek Ongkir Sekarang →</a>
        </div>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>Tujuan</th>
                        <th>Kurir</th>
                        <th>Ongkir</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($semua_pesanan as $p): ?>
                    <tr>
                        <td><strong style="color:#ff4d7d">#ORD-<?= $p['id_order'] ?></strong></td>
                        <td style="font-size:0.82rem;color:#888">
                            <?= date('d M Y', strtotime($p['created_at'])) ?><br>
                            <span><?= date('H:i', strtotime($p['created_at'])) ?></span>
                        </td>
                        <td style="font-size:0.85rem">
                            <?= ! empty($p['kota_tujuan']) ? esc($p['kota_tujuan']) : '<span style="color:#ccc">-</span>' ?>
                        </td>
                        <td>
                            <?php if (! empty($p['kurir'])): ?>
                                <span class="kurir-badge"><?= esc(strtoupper($p['kurir'])) ?></span><br>
                                <small style="color:#aaa;font-size:0.75rem"><?= esc($p['layanan'] ?? '') ?></small>
                            <?php else: ?>
                                <span style="color:#ccc;font-size:0.82rem">-</span>
                            <?php endif; ?>
                        </td>
                        <td>Rp <?= number_format($p['ongkir'], 0, ',', '.') ?></td>
                        <td><strong>Rp <?= number_format($p['total_bayar'], 0, ',', '.') ?></strong></td>
                        <td>
                            <?php
                            $statusMap = [
                                'pending'         => 'status-pending',
                                'dibayar'         => 'status-dibayar',
                                'proses_desain'   => 'status-diproses',
                                'cetak'           => 'status-cetak',
                                'dikirim'         => 'status-dikirim',
                                'selesai'         => 'status-selesai',
                                'batal'           => 'status-batal',
                            ];
                            $cls = $statusMap[$p['status_pesanan']] ?? 'status-pending';
                            ?>
                            <span class="status-badge <?= $cls ?>"><?= esc($p['status_pesanan']) ?></span>
                        </td>
                        <td>
                            <?php if ($p['status_pesanan'] === 'pending'): ?>
                                <div style="display:flex;flex-direction:column;gap:6px;align-items:center">
                                    <a href="<?= base_url('pembayaran/upload/' . $p['id_order']) ?>"
                                       style="background:linear-gradient(135deg,#ff85a2,#ff4d7d);color:white;padding:6px 14px;border-radius:8px;font-size:0.75rem;font-weight:700;text-decoration:none;white-space:nowrap;">
                                        💳 Upload Bukti
                                    </a>
                                    <form action="<?= base_url('logistik/hapus-pesanan/' . $p['id_order']) ?>"
                                          method="post"
                                          onsubmit="return konfirmasiHapus(this, <?= $p['id_order'] ?>)">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn-hapus">Hapus 🗑️</button>
                                    </form>
                                </div>
                            <?php elseif ($p['status_pesanan'] === 'dibayar'): ?>
                                <a href="<?= base_url('pembayaran/status/' . $p['id_order']) ?>"
                                   style="background:#f0f4ff;color:#3355cc;padding:6px 14px;border-radius:8px;font-size:0.75rem;font-weight:700;text-decoration:none;display:inline-block">
                                    ⏳ Cek Status
                                </a>
                            <?php else: ?>
                                <span style="color:#ddd;font-size:0.78rem">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

</div>

<script>
function konfirmasiHapus(form, id) {
    if (! confirm('Yakin ingin menghapus pesanan #ORD-' + id + '?')) {
        return false;
    }
    form.querySelector('button').disabled = true;
    return true;
}
</script>
</body>
</html>