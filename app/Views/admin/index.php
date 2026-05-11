<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<h2>Dashboard Admin</h2>
<div class="row">
    <div class="col-md-4">
        <div class="small-box bg-info">
            <div class="inner"><h3>150</h3><p>Total User</p></div>
            <div class="icon"><i class="fas fa-users"></i></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-warning">Konfirmasi Desain Baru</div>
    <div class="card-body p-0">
        <table class="table">
            <thead><tr><th>Nama Desainer</th><th>Preview</th><th>Aksi</th></tr></thead>
            <tbody>
                <tr>
                    <td>Aulia Design</td>
                    <td><button class="btn btn-sm btn-secondary">Lihat File</button></td>
                    <td>
                        <button class="btn btn-success btn-sm">Approve</button>
                        <button class="btn btn-danger btn-sm">Reject</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>