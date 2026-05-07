<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formal x Scrapbook | Aulia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&family=Bubblegum+Sans&family=Caveat:wght@700&display=swap" rel="stylesheet">
    
    <style>
        body {
            background-color: #fdf2f5;
            background-image: radial-gradient(#ffcad4 0.5px, transparent 0.5px);
            background-size: 20px 20px;
            font-family: 'Quicksand', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .main-paper {
            background: white;
            width: 100%;
            max-width: 900px;
            border-radius: 15px;
            box-shadow: 15px 15px 0px rgba(255, 182, 193, 0.4);
            display: flex;
            overflow: hidden;
            position: relative;
            border: 2px solid #ffcad4;
        }

        .side-accent {
            width: 30px;
            background-color: #ff8fab;
            height: 100%;
        }

        .content-area {
            flex: 1;
            padding: 40px;
            position: relative;
        }

        .close-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            font-family: 'Caveat', cursive;
            color: #ff8fab;
            text-decoration: none;
            font-size: 1.2rem;
        }

        h1 {
            font-family: 'Bubblegum Sans', cursive;
            color: #ff6b95;
            font-size: 3rem;
            margin-bottom: 5px;
        }

        .subtitle {
            font-family: 'Caveat', cursive;
            font-size: 1.5rem;
            color: #ffacbf;
            margin-bottom: 30px;
        }

        .photo-frame {
            background: white;
            padding: 10px;
            border: 1px solid #eee;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            transition: transform 0.3s;
            text-align: center;
            height: 100%;
        }

        .photo-frame:hover {
            transform: rotate(-2deg) scale(1.02);
        }

        .item-price {
            font-size: 0.8rem;
            background: #ffe5ec;
            color: #ff5c8a;
            padding: 2px 10px;
            border-radius: 10px;
            display: inline-block;
            opacity: 0;
            transition: 0.3s;
            transform: translateY(10px);
        }

        .photo-frame:hover .item-price {
            opacity: 1;
            transform: translateY(0);
        }

        .img-wrap {
            background: #fff0f3;
            margin-bottom: 15px;
            padding: 10px;
        }

        .img-wrap img {
            max-height: 150px;
            width: auto;
            max-width: 100%;
        }

        .item-name {
            font-weight: 700;
            font-size: 0.9rem;
            color: #ff6b95;
            margin-bottom: 5px;
        }

        .btn-pesan {
            background-color: #ff8fab;
            color: white;
            border: none;
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: bold;
            margin-top: 40px;
            width: 100%;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            cursor: pointer;
        }

        .btn-pesan:hover {
            background-color: #fb6f92;
            color: white;
            box-shadow: 0 5px 15px rgba(251, 111, 146, 0.3);
        }

        .modal-content {
            border-radius: 30px;
            border: 5px solid #ffcad4;
            background-image: radial-gradient(#ffcad4 0.5px, transparent 0.5px);
            background-size: 15px 15px;
        }
    </style>
</head>
<body>

<div class="main-paper">
    <div class="side-accent"></div>

    <div class="content-area">
        <a href="<?= base_url('katalog') ?>" class="close-btn">✕ Close</a>
        
        <h1>Formal Look ✨</h1>
        <p class="subtitle">Tetep rapi tapi tetep gemes buat kamu...</p>

        <div class="row g-3">
            <div class="col-md-4">
                <div class="photo-frame">
                    <div class="img-wrap">
                        <img src="https://www.pngarts.com/files/3/Men-Suit-PNG-High-Quality-Image.png" alt="Suit">
                    </div>
                    <div class="item-name">Jas Rapi Banget</div>
                    <div class="item-price">Rp 2.499.000</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="photo-frame">
                    <div class="img-wrap">
                        <img src="https://www.pngarts.com/files/1/White-Shirt-PNG-Transparent-Image.png" alt="Shirt">
                    </div>
                    <div class="item-name">Kemeja Putih Bersih</div>
                    <div class="item-price">Rp 599.000</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="photo-frame">
                    <div class="img-wrap">
                        <img src="https://www.pngarts.com/files/11/Leather-Shoes-PNG-Free-Download.png" alt="Shoes">
                    </div>
                    <div class="item-name">Sepatu Kulit Kece</div>
                    <div class="item-price">Rp 1.299.000</div>
                </div>
            </div>
        </div>

        <p class="mt-4 text-center" style="font-family: 'Caveat', cursive; color: #ff8fab;">
            Mau tampil keren seperti ini? Yuk dipesan! ✨
        </p>

        <button type="button" class="btn-pesan" data-bs-toggle="modal" data-bs-target="#orderModal">
            🎀 PESAN KOLEKSI FORMAL SEKARANG 🎀
        </button>
    </div>
</div>

<div class="modal fade" id="orderModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius: 40px; border: 3px solid #ffcad4; padding: 20px;">
      
      <div class="modal-body">
        <div class="text-center mb-4">
            <div style="font-size: 1.5rem;">✨ 🛍️ ✨</div>
            <h2 style="font-family: 'Quicksand', sans-serif; font-weight: 700; color: #ff8fab; margin-top: 10px;">Konfirmasi Pesanan</h2>
        </div>

        <form action="<?= site_url('order/checkout') ?>" method="post">
            <?= csrf_field() ?>
            
            <input type="hidden" name="id_desain" value="formal_pink">
            <input type="hidden" name="id_vendor" value="1">

            <div class="mb-3">
                <label class="form-label small fw-bold" style="color: #ff8fab;">Pilih Tema Estetikmu 🎨</label>
                <select class="form-select" name="tema_pilihan" style="border-radius: 15px; border-color: #ffcad4; color: #666;">
                    <option value="formal" selected>Formal Look (Pink Edition)</option>
                    <option value="casual">Casual Scrapbook</option>
                    <option value="vintage">Vintage Dreams</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold" style="color: #ff8fab;">Mau pesan berapa? 🎀</label>
                <input type="number" name="jumlah" class="form-control" placeholder="Contoh: 10" style="border-radius: 15px; border-color: #ffcad4;" required>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold" style="color: #ff8fab;">Metode Pembayaran 💸</label>
                <select class="form-select" name="metode_pembayaran" style="border-radius: 15px; border-color: #ffcad4; color: #666;">
                    <option value="bank">Transfer Bank</option>
                    <option value="dana">E-Wallet (Dana/OVO)</option>
                    <option value="qris">QRIS</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold" style="color: #ff8fab;">Info Harga 📥</label>
                <input type="text" class="form-control" value="Rp 15.000 / pcs" readonly style="border-radius: 15px; border-color: #ffcad4; background-color: #fff5f7; color: #999;">
            </div>

            <button type="submit" class="btn w-100 shadow-sm" style="background: #ff8fab; color: white; border-radius: 50px; padding: 12px; font-weight: bold; border: none;">
                ❤️ OKE, CHECKOUT! ❤️
            </button>
        </form>
        <div class="text-center mt-3">
            <small style="color: #ffcad4; font-size: 0.7rem; font-family: 'Quicksand', sans-serif;">Created with Love by @AULIA 🎀</small>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>