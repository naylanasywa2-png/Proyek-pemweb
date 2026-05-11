<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <h3 class="mb-4">Pilih Desain Album Kamu</h3>
    </div>
    <?php for($i=1; $i<=4; $i++): ?>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <img src="https://via.placeholder.com/300x400" class="card-img-top" alt="Desain Template">
            <div class="card-body text-center">
                <h5 class="card-title w-100">Template Wisuda Elegan <?= $i ?></h5>
                <p class="card-text text-muted">Oleh: Desainer Pro</p>
                <a href="/user/custom/<?= $i ?>" class="btn btn-primary btn-sm">Gunakan Desain Ini</a>
            </div>
        </div>
    </div>
    <?php endfor; ?>
</div>
<?= $this->endSection() ?>