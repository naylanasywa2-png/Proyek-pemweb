<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Urban Street Theme 🛹 | @author AULIA</title>
    <!-- Import Font Modern & Bold -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@800&family=Oswald:wght@500;700&family=Poppins:wght@300;400&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background: #121212; /* Warna aspal gelap */
            background-image: url('https://www.transparenttextures.com/patterns/stardust.png');
            margin: 0; padding: 40px; color: #ffffff;
        }
        .urban-container {
            max-width: 550px; margin: auto; background: #1e1e1e; padding: 40px;
            border-left: 10px solid #ffff00; /* Aksen kuning jalanan */
            box-shadow: 15px 15px 0px #333;
            position: relative;
        }
        h2 { 
            font-family: 'Montserrat', sans-serif; font-size: 40px; 
            color: #ffff00; text-align: left; margin-bottom: 0;
            text-transform: uppercase; transform: skewX(-10deg);
        }
        .tagline { font-family: 'Oswald', sans-serif; font-size: 14px; color: #888; letter-spacing: 2px; margin-bottom: 35px; }
        
        .price-tag {
            background: #ffff00; color: #000; padding: 10px 20px; 
            display: inline-block; font-weight: 800; font-family: 'Oswald', sans-serif;
            margin-bottom: 30px; transform: rotate(-2deg);
        }
        .form-group { margin-bottom: 25px; }
        label { display: block; font-weight: 700; margin-bottom: 8px; font-size: 12px; text-transform: uppercase; color: #ffff00; }
        input { 
            width: 100%; padding: 15px; border: 2px solid #333; 
            background: #252525; color: #fff; outline: none;
            font-family: 'Poppins', sans-serif; font-weight: bold;
        }
        input:focus { border-color: #ffff00; }
        input[type="file"] { border: 2px dashed #ffff00; background: #2a2a00; cursor: pointer; }
        input[readonly] { background: #333; color: #ffff00; border: none; font-size: 20px; }
        
        .btn-urban {
            background: #ffff00; color: #000; border: none; padding: 20px;
            width: 100%; font-weight: 800; cursor: pointer;
            transition: 0.2s; font-size: 18px; margin-top: 10px;
            text-transform: uppercase; font-family: 'Oswald', sans-serif;
        }
        .btn-urban:hover { background: #fff; transform: scale(1.02); }
        .footer-text { font-size: 10px; margin-top: 40px; text-align: left; color: #555; font-weight: bold; }
    </style>
</head>
<body>

<div class="urban-container">
    <h2>URBAN VIBES</h2>
    <p class="tagline">CITY LIMITS / NO BOUNDARIES</p>
    
    <div class="price-tag">
        RATE: Rp 60.000 / UNIT
    </div>

    <form action="<?= base_url('order/checkout'); ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id_desain" value="5"> <!-- ID Tema Urban -->
        <input type="hidden" name="id_vendor" value="1">

        <div class="form-group">
            <label>How Many Copies? ⛓️</label>
            <input type="number" id="jumlah" name="jumlah" placeholder="QTY" required oninput="urbanCalc()">
        </div>

        <div class="form-group">
            <label>Drop Your Design Files Here 🎨</label>
            <input type="file" name="file_desain" accept=".zip,.jpg,.jpeg,.png" required>
            <small style="font-size: 10px; color: #888; display: block; margin-top: 5px;">*ZIP, JPG, or PNG only.</small>
        </div>

        <div class="form-group">
            <label>Total Payment (IDR)</label>
            <input type="number" id="total_bayar" name="total_bayar" readonly>
        </div>

        <button type="submit" class="btn-urban">Checkout Now ⚡</button>
    </form>
    
    <p class="footer-text">DESIGNED BY AULIA — STREET DIVISION 2026</p>
</div>

<script>
    function urbanCalc() {
        const price = 60000; 
        const qty = document.getElementById('jumlah').value;
        const display = document.getElementById('total_bayar');
        display.value = qty > 0 ? qty * price : 0;
    }
</script>

</body>
</html>