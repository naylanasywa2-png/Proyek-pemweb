<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Studio Desainer 🎨</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #eef2ff; font-family: 'Quicksand', sans-serif; padding: 50px; }
        .box-kreatif { 
            background: white; border-radius: 20px; border: 4px solid #7494ec; 
            padding: 40px; text-align: center; box-shadow: 15px 15px 0px #7494ec;
        }
        h1 { color: #4e73df; font-weight: bold; }
        .btn-upload { background: #4e73df; color: white; border-radius: 50px; padding: 10px 30px; border: none; }
    </style>
</head>
<body>

<div class="container" style="max-width: 600px;">
    <div class="box-kreatif">
        <h1>🎨 Studio Kreatif</h1>
        <p class="text-muted">Halo Desainer! Yuk upload karya desain terbarumu.</p>
        <hr>
        
        <form action="<?= site_url('desainer/upload_desain') ?>" method="post" enctype="multipart/form-data">
            <div class="mb-4 text-start">
                <label class="form-label fw-bold">Nama Desain</label>
                <input type="text" name="nama_desain" class="form-control rounded-pill" placeholder="Masukkan nama desain..." required>
            </div>
            <div class="mb-4 text-start">
                <label class="form-label fw-bold">File Gambar</label>
                <input type="file" name="gambar" class="form-control" required>
            </div>
            <button type="submit" class="btn-upload">Kirim ke Admin ✨</button>
        </form>
    </div>
</div>

</body>
</html>