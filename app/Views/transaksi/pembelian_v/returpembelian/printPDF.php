<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Retur Pembelian</title>
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

<h1>Data Retur Pembelian</h1>
<table>
    <thead>
        <tr>
        <th>No</th>
        <th>Tanggal</th>
        <th>Nota</th>
        <th>Supplier</th>
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
        <th>Tgl Pembelian</th>
        <th>Nota Pembelian</th>
        <th>Pembayaran</th>
        <th>Tipe</th>
        </tr>
    </thead>
    <tbody>
        <?php if (is_array($dtreturpembelian) && !empty($dtreturpembelian)): ?>
            <!-- Jika mencetak semua data -->
            <?php $no = 1; // Untuk nomor urut ?>
            <?php foreach ($dtreturpembelian as $value): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($value->tanggal) ?></td>
                    <td><?= htmlspecialchars($value->nota) ?></td>
                    <td><?= htmlspecialchars($value->nama_supplier) ?></td>
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
                    <td><?= htmlspecialchars($value->tgl_pembelian) ?></td>
                    <td><?= htmlspecialchars($value->nota_pembelian) ?></td>
                    <td><?= htmlspecialchars($value->pembayaran) ?></td>
                    <td><?= htmlspecialchars($value->tipe) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php elseif (isset($dtreturpembelian)): ?>
            <!-- Jika mencetak data berdasarkan ID -->
            <tr>
                <td>1</td>
                    <td><?= htmlspecialchars($dtreturpembelian->tanggal) ?></td>
                    <td><?= htmlspecialchars($dtreturpembelian->nota) ?></td>
                    <td><?= htmlspecialchars($dtreturpembelian->nama_supplier) ?></td>
                    <td><?= htmlspecialchars($dtreturpembelian->lokasi_asal) ?></td>
                    <td><?= htmlspecialchars($dtreturpembelian->nama_stock) ?></td>
                    <td><?= htmlspecialchars($dtreturpembelian->kode_satuan) ?></td>
                    <td><?= htmlspecialchars($dtreturpembelian->qty_1) ?></td>
                    <td><?= htmlspecialchars($dtreturpembelian->qty_2) ?></td>
                    <td><?= htmlspecialchars($dtreturpembelian->harga_satuan) ?></td>
                    <td><?= htmlspecialchars($dtreturpembelian->jml_harga) ?></td>
                    <td><?= htmlspecialchars($dtreturpembelian->disc_1) ?></td>
                    <td><?= htmlspecialchars($dtreturpembelian->disc_2) ?></td>
                    <td><?= htmlspecialchars($dtreturpembelian->total) ?></td>
                    <td><?= htmlspecialchars($dtreturpembelian->tgl_pembelian) ?></td>
                    <td><?= htmlspecialchars($dtreturpembelian->nota_pembelian) ?></td>
                    <td><?= htmlspecialchars($dtreturpembelian->pembayaran) ?></td>
                    <td><?= htmlspecialchars($dtreturpembelian->tipe) ?></td>
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
