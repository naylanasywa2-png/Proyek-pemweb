<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<h2>Dashboard Desainer</h2>
<div class="card card-primary">
    <div class="card-header text-white">Upload Desain Baru (.png)</div>
    <form class="card-body">
        <div class="form-group">
            <label>Nama Katalog</label>
            <input type="text" class="form-control" placeholder="Contoh: Wedding Elegant">
        </div>
        <div class="form-group">
            <label>Pilih File (Wajib Transparan)</label>
            <input type="file" class="form-control-file">
        </div>
        <button class="btn btn-primary">Upload & Ajukan</button>
    </form>
</div>
<div class="card mt-3">
    <div class="card-body bg-success text-white">
        <h5>Total Pendapatan: Rp 1.500.000</h5>
    </div>
</div>
<?= $this->endSection() ?>