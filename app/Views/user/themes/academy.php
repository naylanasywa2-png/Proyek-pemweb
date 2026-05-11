<!DOCTYPE html>
<html lang="id">
<head>
    <title>Grand Academy | @AULIA</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&family=Fauna+One&display=swap" rel="stylesheet">
    <style>
        body { background: #f4f1ea; color: #2c3e50; font-family: 'Fauna One', serif; margin: 0; }
        .campus { padding: 60px; text-align: center; border: 20px solid #fff; min-height: 100vh; box-sizing: border-box; }
        h1 { font-family: 'Cinzel', serif; font-size: 40px; color: #1a252f; border-bottom: 2px solid #1a252f; display: inline-block; }
        .frame { background: white; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); max-width: 600px; margin: 40px auto; }
        .btn-enroll { 
            background: #1a252f; color: #fff; padding: 15px 50px; text-decoration: none; 
            letter-spacing: 2px; transition: 0.3s;
        }
        .btn-enroll:hover { background: #34495e; }
    </style>
</head>
<body>
    <div class="campus">
        <h1>THE GRAND ACADEMY</h1>
        <p>Excellentia et Honor.</p>
        <div class="frame">
            <img src="<?= base_url('uploads/academy_sample.jpg') ?>" width="100%" style="border-radius: 5px;">
            <h3 style="font-family: 'Cinzel';">UNIFORM & MANNERS</h3>
            <p>Konsep sekolah elit dengan seragam rapi dan latar megah.</p>
        </div>
        <a href="<?= base_url('order/create') ?>" class="btn-enroll">ENROLL NOW</a>
    </div>
</body>
</html>