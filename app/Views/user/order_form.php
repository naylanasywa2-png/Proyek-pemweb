<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Lucu | @author AULIA</title>
    <!-- Import Font Aesthetic -->
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Quicksand', sans-serif; 
            background: #fef1f6; /* Warna pink pastel lembut */
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0;
            background-image: radial-gradient(#ffc2d1 1px, transparent 1px);
            background-size: 20px 20px; /* Pola polkadot halus */
        }
        .card { 
            background: white; 
            padding: 40px; 
            border-radius: 30px; 
            box-shadow: 0 15px 35px rgba(255, 182, 193, 0.4); 
            width: 100%; 
            max-width: 380px; 
            text-align: center; 
            position: relative;
            overflow: hidden;
            border: 4px solid #ffccd5;
        }
        .card::before {
            content: '🌸';
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
        }
        h2 { 
            color: #ff758f; 
            margin-bottom: 20px; 
            font-size: 24px;
        }
        .form-group { text-align: left; margin-bottom: 15px; }
        label { display: block; font-weight: 700; margin-bottom: 8px; color: #ff8fa3; font-size: 14px; }
        input { 
            width: 100%; 
            padding: 12px 15px; 
            border: 2px solid #ffe5ec; 
            border-radius: 15px; 
            box-sizing: border-box; 
            font-family: 'Quicksand', sans-serif;
            font-size: 15px;
            color: #555;
            outline: none;
            transition: 0.3s;
        }
        input:focus { border-color: #ffb3c1; background: #fffafb; }
        input[readonly] { background-color: #fff0f3; cursor: not-allowed; border-style: dashed; }
        
        .btn-submit { 
            background: #ff758f; 
            color: white; 
            border: none; 
            padding: 15px; 
            width: 100%; 
            border-radius: 50px; 
            font-weight: 700; 
            font-size: 16px;
            cursor: pointer; 
            transition: 0.3s; 
            margin-top: 20px;
            box-shadow: 0 5px 15px rgba(255, 117, 143, 0.3);
        }
        .btn-submit:hover { 
            background: #ff4d6d; 
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(255, 117, 143, 0.5);
        }
        .info { font-size: 11px; color: #ffb3c1; margin-top: 20px; font-weight: bold; }
        .emoji-deco { font-size: 30px; margin-bottom: 10px; display: block; }
    </style>
</head>
<body>
    <div class="card">
        <span class="emoji-deco">✨ 🛍️ ✨</span>
        <h2>Konfirmasi Pesanan</h2>
        <form action="<?= base_url('order/checkout'); ?>" method="post">
            <div class="form-group">
                <label>Mau pesan berapa? 🎀</label>
                <input type="number" id="jumlah" name="jumlah" placeholder="Contoh: 10" required oninput="hitungTotal()">
            </div>
            
            <div class="form-group">
                <label>Harga Satuan (IDR) 💸</label>
                <input type="text" id="harga_satuan" value="15000" readonly>
            </div>

            <div class="form-group">
                <label>Total yang dibayar 🍰</label>
                <input type="number" id="total_bayar" name="total_bayar" readonly placeholder="Akan muncul otomatis~">
            </div>

            <input type="hidden" name="id_desain" value="1">
            <input type="hidden" name="id_vendor" value="1">
            
            <button type="submit" class="btn-submit">💖 OKE, CHECKOUT! 💖</button>
        </form>
        <p class="info">Created with Love by @AULIA 🎀</p>
    </div>

    <script>
        function hitungTotal() {
            const jumlah = document.getElementById('jumlah').value;
            const harga = document.getElementById('harga_satuan').value;
            const totalField = document.getElementById('total_bayar');
            
            if (jumlah > 0) {
                totalField.value = jumlah * harga;
            } else {
                totalField.value = 0;
            }
        }
    </script>
</body>
</html>