<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Memory Book | @author AULIA</title>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #fff5f8;
            font-family: 'Quicksand', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #5d5d5d;
        }

        .container {
            background-color: white;
            padding: 40px;
            border-radius: 30px;
            box-shadow: 0 10px 25px rgba(255, 182, 193, 0.4);
            text-align: center;
            border: 4px dashed #ffb6c1;
            max-width: 500px;
        }

        h1 {
            color: #ff69b4;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        p {
            font-size: 1.1rem;
            line-height: 1.6;
        }

        .emoji-deco {
            font-size: 50px;
            margin-bottom: 20px;
        }

        .btn-start {
            background-color: #ff69b4;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s, background-color 0.2s;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }

        .btn-start:hover {
            background-color: #ff1493;
            transform: scale(1.05);
        }

        .footer {
            margin-top: 30px;
            font-size: 0.8rem;
            color: #ffb6c1;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="emoji-deco">🌸✨📖✨🌸</div>
    <h1>Halo, Aulia!</h1>
    <p>Selamat datang di <b>Digital Memory Book</b> kamu. <br> Temukan kenangan manis dan cerita seru di sini!</p>
    
   <a href="<?= base_url('katalog'); ?>" class="btn-start">Buka Buku Kenangan 💖</a>

    <div class="footer">
        Created with love by @author AULIA
    </div>
</div>

</body>
</html>