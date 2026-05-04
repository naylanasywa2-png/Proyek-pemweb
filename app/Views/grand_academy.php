<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Grand Academy Edition 🏛️ | @author AULIA</title>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Libre+Baskerville', serif; 
            background: #1a237e; /* Navy Biru Akademik */
            background-image: url('https://www.transparenttextures.com/patterns/cream-paper.png');
            margin: 0; padding: 40px; color: #fff;
        }
        .academy-box {
            max-width: 600px; margin: auto; background: #ffffff; color: #1a237e; padding: 50px;
            border: 10px double #c5a059; /* Bingkai emas mewah */
            box-shadow: 0 0 30px rgba(0,0,0,0.5);
        }
        h2 { text-align: center; font-size: 32px; text-transform: uppercase; letter-spacing: 3px; margin-bottom: 10px; }
        .crest { text-align: center; font-size: 50px; margin-bottom: 20px; }
        .price-ribbon {
            background: #c5a059; color: #fff; text-align: center; padding: 10px;
            font-weight: bold; margin-bottom: 30px; font-family: 'Montserrat', sans-serif;
        }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; font-size: 13px; color: #1a237e; }
        input { 
            width: 100%; padding: 12px; border: 1px solid #1a237e; 
            background: #f5f5f5; font-family: 'Libre+Baskerville', serif; box-sizing: border-box;
        }
        input[type="file"] { border: 2px dashed #c5a059; cursor: pointer; }
        .btn-submit {
            background: #1a237e; color: #fff; border: none; padding: 15px;
            width: 100%; font-weight: bold; cursor: pointer; transition: 0.3s;
            text-transform: uppercase; letter-spacing: 2px; margin-top: 15px;
        }
        .btn-submit:hover { background: #c5a059; }
        .footer { text-align: center; font-size: 11px; margin-top: 30px; color: #777; font-style: italic; }
    </style>
</head>
<body>
<div class="academy-box">
    <div class="crest">🏛️</div>
    <h2>Grand Academy</h2>
    <div class="price-ribbon">Academic Investment: Rp 85.000 / unit</div>

    <form action="<?= base_url('order/checkout'); ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id_desain" value="6">
        <input type="hidden" name="id_vendor" value="4">

        <div class="form-group">
            <label>Jumlah Salinan (Quantity) 🖋️</label>
            <input type="number" id="jumlah" name="jumlah" required oninput="calculate()">
        </div>

        <div class="form-group">
            <label>Upload Aset Akademik (ZIP/PDF/JPG) 📂</label>
            <input type="file" name="file_desain" accept=".zip,.pdf,.jpg,.jpeg,.png" required>
        </div>

        <div class="form-group">
            <label>Total Biaya Pendidikan (IDR)</label>
            <input type="number" id="total_bayar" name="total_bayar" readonly>
        </div>

        <button type="submit" class="btn-submit">Daftarkan Pesanan</button>
    </form>
    <p class="footer">Official Publication of @AULIA — Class of 2026</p>
</div>
<script>
    function calculate() {
        const price = 85000;
        const qty = document.getElementById('jumlah').value;
        const total = document.getElementById('total_bayar');
        total.value = qty > 0 ? qty * price : 0;
    }
</script>
</body>
</html>