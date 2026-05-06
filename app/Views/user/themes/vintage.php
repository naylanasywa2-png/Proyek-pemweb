<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Classic Vintage Gallery | @author AULIA</title>
    <link href="https://fonts.googleapis.com/css2?family=Special+Elite&family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body { 
            background-color: #2c2c2c; /* Warna gelap ala ruang cuci foto */
            background-image: url('https://www.transparenttextures.com/patterns/stardust.png');
            font-family: 'Special+Elite', serif; 
            color: #dcdcdc; 
            margin: 0;
            padding: 20px;
        }
        .container { max-width: 900px; margin: auto; text-align: center; }
        h1 { color: #f4a261; border-bottom: 2px solid #f4a261; display: inline-block; padding-bottom: 10px; }
        
        .gallery { display: flex; flex-wrap: wrap; justify-content: center; gap: 30px; margin-top: 40px; }
        
        /* Frame Foto Polaroid Jadul */
        .polaroid {
            background: #fdfdfd;
            padding: 15px 15px 60px 15px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.5);
            transform: rotate(-3deg);
            transition: 0.4s;
            border: 1px solid #ddd;
        }
        .polaroid:nth-child(even) { transform: rotate(4deg); }
        .polaroid:hover { transform: rotate(0deg) scale(1.1); z-index: 5; }
        
        .polaroid img { 
            width: 250px; 
            height: 250px; 
            object-fit: cover; 
            filter: sepia(0.5) contrast(1.2); /* Efek kamera film */
        }
        .polaroid p { color: #333; margin-top: 15px; font-size: 18px; }

        .btn-order {
            display: inline-block;
            margin-top: 50px;
            background: #f4a261;
            color: #2c2c2c;
            padding: 15px 40px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            font-family: 'Quicksand', sans-serif;
            box-shadow: 0 5px 15px rgba(244, 162, 97, 0.4);
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="<?= base_url('katalog'); ?>" style="color: #f4a261; text-decoration: none; float: right;">✖ Close</a>
        <h1>Classic Vintage Gallery</h1>
        <p>Nuansa nostalgia tahun 90-an dengan filter kamera film.</p>

        <div class="gallery">
            <div class="polaroid">
                <img src="<?= base_url('uploads/templates/vintage1.jpg'); ?>" alt="Vintage 1">
                <p>Old Memory #1</p>
            </div>
            <div class="polaroid">
                <img src="<?= base_url('uploads/templates/vintage2.jpg'); ?>" alt="Vintage 2">
                <p>Old Memory #2</p>
            </div>
        </div>

        <br>
        <a href="<?= base_url('order/create') ?>" class="btn-order">
            📷 PESAN TEMA VINTAGE INI 📷
        </a>
    </div>
</body>
</html>