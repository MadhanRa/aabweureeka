<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Penyesuaian Stock</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h1>Data Penyesuaian Stock</h1>
<table>
    <thead>
        <tr>
        <th>No</th>
        <th>Tanggal</th>
        <th>Nota</th>
        <th>Rekening</th>
        <th>Lokasi</th>
        <th>Group</th>
        <th>Nama Stock</th>
        <th>Satuan</th>
        <th>Saldo (Qty1/Qty2)</th>
        <th>Qty 1(F)</th>
        <th>Qty 2(F)</th>
        <th>Penyesuaian(+/-)</th>
        <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        <?php if (is_array($dtpenyesuaianstock) && !empty($dtpenyesuaianstock)): ?>
            <!-- Jika mencetak semua data -->
            <?php $no = 1; // Untuk nomor urut ?>
            <?php foreach ($dtpenyesuaianstock as $value): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($value->tanggal) ?></td>
                    <td><?= htmlspecialchars($value->nota) ?></td>
                    <td><?= htmlspecialchars($value->nama_setupbank) ?></td>
                    <td><?= htmlspecialchars($value->lokasi_asal) ?></td>
                    <td><?= htmlspecialchars($value->nama_group) ?></td>
                    <td><?= htmlspecialchars($value->nama_stock) ?></td>
                    <td><?= htmlspecialchars($value->kode_satuan) ?></td>
                    <td><?= htmlspecialchars($value->saldo) ?></td>
                    <td><?= htmlspecialchars($value->qty_1) ?></td>
                    <td><?= htmlspecialchars($value->qty_2) ?></td>
                    <td><?= htmlspecialchars($value->penyesuaian) ?></td>
                    <td><?= htmlspecialchars($value->keterangan) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php elseif (isset($dtpenyesuaianstock)): ?>
            <!-- Jika mencetak data berdasarkan ID -->
            <tr>
                <td>1</td>
                <td><?= htmlspecialchars($dtpenyesuaianstock->tanggal) ?></td>
                <td><?= htmlspecialchars($dtpenyesuaianstock->nota) ?></td>
                <td><?= htmlspecialchars($dtpenyesuaianstock->nama_setupbank) ?></td>
                <td><?= htmlspecialchars($dtpenyesuaianstock->lokasi_asal) ?></td>
                <td><?= htmlspecialchars($dtpenyesuaianstock->nama_group) ?></td>
                <td><?= htmlspecialchars($dtpenyesuaianstock->nama_stock) ?></td>
                <td><?= htmlspecialchars($dtpenyesuaianstock->kode_satuan) ?></td>
                <td><?= htmlspecialchars($dtpenyesuaianstock->saldo) ?></td>
                <td><?= htmlspecialchars($dtpenyesuaianstock->qty_1) ?></td>
                <td><?= htmlspecialchars($dtpenyesuaianstock->qty_2) ?></td>
                <td><?= htmlspecialchars($dtpenyesuaianstock->penyesuaian) ?></td>
                <td><?= htmlspecialchars($dtpenyesuaianstock->keterangan) ?></td>
            </tr>
        <?php else: ?>
            <!-- Jika data tidak ditemukan -->
            <tr>
                <td colspan="20">Data tidak tersedia.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
