<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan Imut 🎀 | @author AULIA</title>
    <!-- Import Font Lucu (Quicksand) -->
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --pink-utama: #ff8fa3;
            --pink-muda: #ffccd5;
            --pink-pucat: #fff0f3;
            --text-color: #555;
        }

        body { 
            font-family: 'Quicksand', sans-serif; 
            background: #fef1f6; /* Warna pink pastel lembut */
            margin: 0;
            padding: 30px;
            color: var(--text-color);
            background-image: radial-gradient(var(--pink-muda) 1px, transparent 1px);
            background-size: 25px 25px; /* Pola polkadot halus */
        }

        .main-container {
            max-width: 800px;
            margin: 0 auto;
        }

        /* Notifikasi Sukses yang Imut */
        .alert-imut {
            background-color: #e0fbf1; /* Hijau pastel sangat muda */
            color: #2d6a4f;
            padding: 15px 20px;
            border-radius: 50px; /* Sangat bulat */
            border: 2px solid #b7e4c7;
            text-align: center;
            font-weight: 700;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(183, 228, 199, 0.4);
            animation: bounceInDown 1s; /* Animasi muncul */
        }

        @keyframes bounceInDown {
            0% { opacity: 0; transform: translateY(-50px); }
            60% { opacity: 1; transform: translateY(10px); }
            80% { transform: translateY(-5px); }
            100% { transform: translateY(0); }
        }

        /* Judul Halaman */
        .judul-halaman {
            text-align: center;
            color: var(--pink-utama);
            font-size: 28px;
            margin-bottom: 30px;
            text-shadow: 1px 1px 2px rgba(255, 143, 163, 0.3);
        }

        /* Container untuk Kartu Pesanan (Pengganti Tabel) */
        .pesanan-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }

        /* Kartu Pesanan Imut */
        .kartu-pesanan {
            background: white;
            border-radius: 25px;
            padding: 25px;
            border: 3px solid var(--pink-muda);
            box-shadow: 0 10px 25px rgba(255, 182, 193, 0.2);
            transition: 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .kartu-pesanan:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 15px 30px rgba(255, 182, 193, 0.4);
            border-color: var(--pink-utama);
        }

        /* Aksen Pita di Kartu */
        .kartu-pesanan::before {
            content: '🎀';
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 20px;
            opacity: 0.7;
        }

        .info-pesanan {
            margin-bottom: 15px;
        }

        .label-info {
            display: block;
            font-size: 12px;
            color: #888;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .data-info {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-color);
        }

        .data-harga {
            color: #d63384; /* Warna pink gelap untuk harga */
            font-size: 18px;
        }

        /* Status Pesanan dengan Label Bulat */
        .status-badge {
            display: inline-block;
            padding: 6px 15px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-paid {
            background-color: #d4edda;
            color: #155724;
        }

        /* Bagian Atas Kartu (Header) */
        .kartu-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px dashed var(--pink-muda);
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .id-order {
            font-weight: 700;
            color: var(--pink-utama);
            font-size: 18px;
        }

        /* Tombol Kembali yang Lucu */
        .btn-kembali-imut {
            display: inline-block;
            margin-top: 30px;
            text-decoration: none;
            color: white;
            background: var(--pink-utama);
            padding: 12px 25px;
            border-radius: 50px;
            font-weight: 700;
            transition: 0.3s;
            box-shadow: 0 5px 15px rgba(255, 143, 163, 0.4);
        }

        .btn-kembali-imut:hover {
            background: #ff758f;
            transform: scale(1.05);
        }

        /* Pesan Jika Belum Ada Pesanan */
        .pesan-kosong {
            grid-column: 1 / -1; /* Penuhi satu baris */
            text-align: center;
            background: white;
            padding: 40px;
            border-radius: 25px;
            border: 3px dashed var(--pink-muda);
            color: #888;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Notifikasi Sukses yang Imut -->
        <?php if(session()->getFlashdata('msg')): ?>
            <div class="alert-imut">
                ✨ <?= session()->getFlashdata('msg') ?> ✨
            </div>
        <?php endif; ?>

        <h2 class="judul-halaman">Riwayat Pesanan Imut Kamu 💖</h2>

        <div class="pesanan-container">
            <?php if(!empty($orders)): ?>
                <?php foreach($orders as $row): ?>
                <div class="kartu-pesanan">
                    <div class="kartu-header">
                        <span class="id-order">#<?= $row['id_order']; ?></span>
                        <span class="status-badge <?= $row['status_pesanan'] == 'pending_payment' ? 'status-pending' : 'status-paid'; ?>">
                            <?= str_replace('_', ' ', $row['status_pesanan']); ?>
                        </span>
                    </div>

                    <div class="info-pesanan">
                        <span class="label-info">JUMLAH</span>
                        <span class="data-info"><?= $row['jumlah']; ?> pcs</span>
                    </div>

                    <div class="info-pesanan">
                        <span class="label-info">TOTAL BAYAR</span>
                        <span class="data-info data-harga">Rp <?= number_format($row['total_bayar'], 0, ',', '.'); ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="pesan-kosong">
                    <span style="font-size: 50px;">🌸</span><br>
                    Belum ada pesanan nih.. Yuk pesan desain lucu!
                </div>
            <?php endif; ?>
        </div>

        <div style="text-align: center;">
            <a href="<?= base_url('katalog'); ?>" class="btn-kembali-imut">← Kembali ke Katalog Lucu</a>
        </div>
    </div>
</body>
</html>