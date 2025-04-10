<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Retur Penjualan</title>
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

<h1>Data Retur Penjualan</h1>
<table>
    <thead>
        <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nota</th>
                        <th>Pelanggan</th>
                        <th>Salesman</th>
                        <th>Lokasi</th>
                        <th>Nama Stock</th>
                        <th>Satuan</th>
                        <th>Qty 1</th>
                        <th>Qty 2</th>
                        <th>Harga Satuan</th>
                        <th>Jumlah Harga</th>
                        <th>Disc. 1</th>
                        <th>Disc. 2</th>
                        <th>Total</th>
                        <th>Tgl Penjualan</th>
                        <th>Nota Penjualan</th>
                        <th>Pembayaran</th>
                        <th>Tipe</th>
        </tr>
    </thead>
    <tbody>
        <?php if (is_array($dtreturpenjualan) && !empty($dtreturpenjualan)): ?>
            <!-- Jika mencetak semua data -->
            <?php $no = 1; // Untuk nomor urut ?>
            <?php foreach ($dtreturpenjualan as $value): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($value->tanggal) ?></td>
                    <td><?= htmlspecialchars($value->nota) ?></td>
                    <td><?= htmlspecialchars($value->nama_pelanggan) ?></td>
                    <td><?= htmlspecialchars($value->nama_setupsalesman) ?></td>
                    <td><?= htmlspecialchars($value->lokasi_asal) ?></td>
                    <td><?= htmlspecialchars($value->nama_stock) ?></td>
                    <td><?= htmlspecialchars($value->kode_satuan) ?></td>
                    <td><?= htmlspecialchars($value->qty_1) ?></td>
                    <td><?= htmlspecialchars($value->qty_2) ?></td>
                    <td><?= htmlspecialchars($value->harga_satuan) ?></td>
                    <td><?= htmlspecialchars($value->jml_harga) ?></td>
                    <td><?= htmlspecialchars($value->disc_1) ?></td>
                    <td><?= htmlspecialchars($value->disc_2) ?></td>
                    <td><?= htmlspecialchars($value->total) ?></td>
                    <td><?= htmlspecialchars($value->tgl_penjualan) ?></td>
                    <td><?= htmlspecialchars($value->nota_penjualan) ?></td>
                    <td><?= htmlspecialchars($value->pembayaran) ?></td>
                    <td><?= htmlspecialchars($value->tipe) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php elseif (isset($dtreturpenjualan)): ?>
            <!-- Jika mencetak data berdasarkan ID -->
            <tr>
                <td>1</td>
                    <td><?= htmlspecialchars($dtreturpenjualan->tanggal) ?></td>
                    <td><?= htmlspecialchars($dtreturpenjualan->nota) ?></td>
                    <td><?= htmlspecialchars($dtreturpenjualan->nama_pelanggan) ?></td>
                    <td><?= htmlspecialchars($dtreturpenjualan->nama_setupsalesman) ?></td>
                    <td><?= htmlspecialchars($dtreturpenjualan->lokasi_asal) ?></td>
                    <td><?= htmlspecialchars($dtreturpenjualan->nama_stock) ?></td>
                    <td><?= htmlspecialchars($dtreturpenjualan->kode_satuan) ?></td>
                    <td><?= htmlspecialchars($dtreturpenjualan->qty_1) ?></td>
                    <td><?= htmlspecialchars($dtreturpenjualan->qty_2) ?></td>
                    <td><?= htmlspecialchars($dtreturpenjualan->harga_satuan) ?></td>
                    <td><?= htmlspecialchars($dtreturpenjualan->jml_harga) ?></td>
                    <td><?= htmlspecialchars($dtreturpenjualan->disc_1) ?></td>
                    <td><?= htmlspecialchars($dtreturpenjualan->disc_2) ?></td>
                    <td><?= htmlspecialchars($dtreturpenjualan->total) ?></td>
                    <td><?= htmlspecialchars($dtreturpenjualan->tgl_penjualan) ?></td>
                    <td><?= htmlspecialchars($dtreturpenjualan->nota_penjualan) ?></td>
                    <td><?= htmlspecialchars($dtreturpenjualan->pembayaran) ?></td>
                    <td><?= htmlspecialchars($dtreturpenjualan->tipe) ?></td>
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
