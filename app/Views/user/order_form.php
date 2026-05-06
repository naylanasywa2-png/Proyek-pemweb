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
            background: #fef1f6; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
            margin: 0;
            padding: 20px 0;
            background-image: radial-gradient(#ffc2d1 1px, transparent 1px);
            background-size: 20px 20px;
        }
        .card { 
            background: white; 
            padding: 40px; 
            border-radius: 30px; 
            box-shadow: 0 15px 35px rgba(255, 182, 193, 0.4); 
            width: 100%; 
            max-width: 380px; 
            text-align: center; 
            border: 4px solid #ffccd5;
        }
        h2 { color: #ff758f; margin-bottom: 20px; font-size: 24px; }
        .form-group { text-align: left; margin-bottom: 15px; }
        label { display: block; font-weight: 700; margin-bottom: 8px; color: #ff8fa3; font-size: 14px; }
        input, select { 
            width: 100%; 
            padding: 12px 15px; 
            border: 2px solid #ffe5ec; 
            border-radius: 15px; 
            font-family: 'Quicksand', sans-serif;
            font-size: 15px;
            outline: none;
            transition: 0.3s;
            box-sizing: border-box; 
        }
        input:focus, select:focus { border-color: #ffb3c1; background: #fffafb; }
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
        }
        .info { font-size: 11px; color: #ffb3c1; margin-top: 20px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="card">
        <span>✨ 🛍️ ✨</span>
        <h2>Konfirmasi Pesanan</h2>
        <form action="<?= base_url('order/checkout'); ?>" method="post">
            
            <div class="form-group">
                <label>Pilih Tema Estetikmu 🎨</label>
                <select name="id_desain" id="id_desain" onchange="hitungTotal()" required>
                    <option value="" disabled selected>Pilih tema dulu ya~</option>
                    <option value="1" data-harga="15000">✂️ DIY Scrapbook (Rp 15.000)</option>
                    <option value="2" data-harga="20000">🕹️ Level Up: Game (Rp 20.000)</option>
                    <option value="3" data-harga="18000">📷 Classic Vintage (Rp 18.000)</option>
                    
                    <!-- Tambahan Tema Baru -->
                    <option value="4" data-harga="25000">🎩 The Mafia World (Rp 25.000)</option>
                    <option value="5" data-harga="17000">🛹 Urban Streetwear (Rp 17.000)</option>
                    <option value="6" data-harga="22000">🏛️ Grand Academy (Rp 22.000)</option>
                </select>
            </div>

            <div class="form-group">
                <label>Mau pesan berapa? 🎀</label>
                <input type="number" id="jumlah" name="jumlah" placeholder="Contoh: 10" required oninput="hitungTotal()">
            </div>

            <div class="form-group">
                <label>Metode Pembayaran 💸</label>
                <select name="metode_pembayaran" required>
                    <option value="" disabled selected>Pilih cara bayar~</option>
                    <option value="qris">📱 QRIS (OVO/Gopay/Dana)</option>
                    <option value="transfer_bank">🏦 Transfer Bank (BCA/Mandiri)</option>
                    <option value="cod">🤝 Cash on Delivery (COD)</option>
                </select>
            </div>

            <div class="form-group">
                <label>Total yang dibayar 🍰</label>
                <input type="text" id="total_display" readonly placeholder="Akan muncul otomatis~">
                <input type="hidden" id="total_bayar" name="total_bayar">
            </div>

            <input type="hidden" name="id_vendor" value="1">
            <button type="submit" class="btn-submit">💖 OKE, CHECKOUT! 💖</button>
        </form>
        <p class="info">Created with Love by @AULIA 🎀</p>
    </div>

    <script>
        function hitungTotal() {
            const select = document.getElementById('id_desain');
            if (select.selectedIndex <= 0) return;

            const harga = select.options[select.selectedIndex].getAttribute('data-harga');
            const jumlah = document.getElementById('jumlah').value;
            const display = document.getElementById('total_display');
            const hiddenInput = document.getElementById('total_bayar');
            
            if (jumlah > 0) {
                const total = jumlah * harga;
                display.value = "Rp " + total.toLocaleString('id-ID');
                hiddenInput.value = total;
            } else {
                display.value = "";
                hiddenInput.value = 0;
            }
        }
    </script>
</body>
</html>