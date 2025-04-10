<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Penjualan</title>
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

<h1>Data Penjualan</h1>
<table>
    <thead>
        <tr>
        <th>No</th>
        <th>Tanggal</th>
        <th>Nota</th>
        <th>Pelanggan</th>
        <th>TOP</th>
        <th>Tgl.jthtempo</th>
        <th>Salesman</th>
        <th>Lokasi</th>
        <th>No.FP</th>
        <th>Nama Stock</th>
        <th>Satuan</th>
        <th>Qty 1</th>
        <th>Qty 2</th>
        <th>Harga</th>
        <th>Jml. Harga</th>
        <th>Disc.1</th>
        <th>Disc.2</th>
        <th>Total</th>
        <th>pembayaran</th>
        <th>Tipe</th>
        </tr>
    </thead>
    <tbody>
        <?php if (is_array($dtpenjualan) && !empty($dtpenjualan)): ?>
            <!-- Jika mencetak semua data -->
            <?php $no = 1; // Untuk nomor urut ?>
            <?php foreach ($dtpenjualan as $value): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($value->tanggal) ?></td>
                    <td><?= htmlspecialchars($value->nota) ?></td>
                    <td><?= htmlspecialchars($value->nama_pelanggan) ?></td>
                    <td><?= htmlspecialchars($value->TOP) ?></td>
                    <td><?= htmlspecialchars($value->tgl_jatuhtempo) ?></td>
                    <td><?= htmlspecialchars($value->nama_setupsalesman) ?></td>
                    <td><?= htmlspecialchars($value->lokasi_asal) ?></td>
                    <td><?= htmlspecialchars($value->no_fp) ?></td>
                    <td><?= htmlspecialchars($value->nama_stock) ?></td>
                    <td><?= htmlspecialchars($value->kode_satuan) ?></td>
                    <td><?= htmlspecialchars($value->qty_1) ?></td>
                    <td><?= htmlspecialchars($value->qty_2) ?></td>
                    <td><?= htmlspecialchars($value->harga_satuan) ?></td>
                    <td><?= htmlspecialchars($value->jml_harga) ?></td>
                    <td><?= htmlspecialchars($value->disc_1) ?></td>
                    <td><?= htmlspecialchars($value->disc_2) ?></td>
                    <td><?= htmlspecialchars($value->total) ?></td>
                    <td><?= htmlspecialchars($value->pembayaran) ?></td>
                    <td><?= htmlspecialchars($value->tipe) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php elseif (isset($dtpenjualan)): ?>
            <!-- Jika mencetak data berdasarkan ID -->
            <tr>
                    <td>1</td>
                    <td><?= htmlspecialchars($dtpenjualan->tanggal) ?></td>
                    <td><?= htmlspecialchars($dtpenjualan->nota) ?></td>
                    <td><?= htmlspecialchars($dtpenjualan->nama_pelanggan) ?></td>
                    <td><?= htmlspecialchars($dtpenjualan->TOP) ?></td>
                    <td><?= htmlspecialchars($dtpenjualan->tgl_jatuhtempo) ?></td>
                    <td><?= htmlspecialchars($dtpenjualan->nama_setupsalesman) ?></td>
                    <td><?= htmlspecialchars($dtpenjualan->lokasi_asal) ?></td>
                    <td><?= htmlspecialchars($dtpenjualan->no_fp) ?></td>
                    <td><?= htmlspecialchars($dtpenjualan->nama_stock) ?></td>
                    <td><?= htmlspecialchars($dtpenjualan->kode_satuan) ?></td>
                    <td><?= htmlspecialchars($dtpenjualan->qty_1) ?></td>
                    <td><?= htmlspecialchars($dtpenjualan->qty_2) ?></td>
                    <td><?= htmlspecialchars($dtpenjualan->harga_satuan) ?></td>
                    <td><?= htmlspecialchars($dtpenjualan->jml_harga) ?></td>
                    <td><?= htmlspecialchars($dtpenjualan->disc_1) ?></td>
                    <td><?= htmlspecialchars($dtpenjualan->disc_2) ?></td>
                    <td><?= htmlspecialchars($dtpenjualan->total) ?></td>
                    <td><?= htmlspecialchars($dtpenjualan->pembayaran) ?></td>
                    <td><?= htmlspecialchars($dtpenjualan->tipe) ?></td>
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
