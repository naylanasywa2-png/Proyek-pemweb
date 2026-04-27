<h2>Panel Kendali Admin (Master Dashboard)</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>ID Pesanan</th>
        <th>ID User</th>
        <th>Total Bayar</th>
        <th>Status Pesanan</th>
    </tr>
    <?php foreach($semua_pesanan as $order): ?>
    <tr>
        <td><?= $order['id_order'] ?></td>
        <td><?= $order['id_user'] ?></td>
        <td>Rp <?= number_format($order['total_bayar'], 0, ',', '.') ?></td>
        <td>
            <strong><?= strtoupper($order['status_pesanan']) ?></strong>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<br>
<a href="/logout">Logout</a>