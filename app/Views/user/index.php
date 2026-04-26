<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<h2>Selamat Datang, User!</h2>
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <img src="https://via.placeholder.com/150" class="card-img-top">
            <div class="card-body">
                <h6>Katalog Wisuda</h6>
                <button class="btn btn-primary btn-block btn-sm">Pilih & Upload Foto</button>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">Riwayat Memory Book Kamu</div>
    <div class="card-body">
        <p>Order #ORD-001 - <span class="badge badge-success">Selesai</span> 
        <a href="#" class="btn btn-sm btn-link">Download Softfile</a></p>
    </div>
</div>
<?= $this->endSection() ?>