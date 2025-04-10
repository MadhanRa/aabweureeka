<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Bahan di Sablon</title>
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

<h1>Data Bahan di Sablon</h1>
<table>
    <thead>
        <tr>
            <th>Lokasi Asal</th>
            <th>Lokasi Tujuan</th>
            <th>Nota</th>
            <th>Nama Stock</th>
            <th>Satuan</th>
            <th>Qty 1</th>
            <th>Qty 2</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        <?php if (is_array($dtbahansablon)): ?>
            <!-- Jika mencetak semua data -->
            <?php foreach ($dtbahansablon as $value): ?>
                <tr>
                    <td><?= htmlspecialchars($value->lokasi_asal) ?></td>
                    <td><?= htmlspecialchars($value->lokasi_tujuan) ?></td>
                    <td><?= htmlspecialchars($value->nota_pindah) ?></td>
                    <td><?= htmlspecialchars($value->nama_stock) ?></td>
                    <td><?= htmlspecialchars($value->kode_satuan) ?></td>
                    <td><?= htmlspecialchars($value->qty_1) ?></td>
                    <td><?= htmlspecialchars($value->qty_2) ?></td>
                    <td><?= htmlspecialchars($value->tanggal) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Jika mencetak data per ID -->
            <tr>
                <td><?= htmlspecialchars($dtbahansablon->lokasi_asal) ?></td>
                <td><?= htmlspecialchars($dtbahansablon->lokasi_tujuan) ?></td>
                <td><?= htmlspecialchars($dtbahansablon->nota_pindah) ?></td>
                <td><?= htmlspecialchars($dtbahansablon->nama_stock) ?></td>
                <td><?= htmlspecialchars($dtbahansablon->kode_satuan) ?></td>
                <td><?= htmlspecialchars($dtbahansablon->qty_1) ?></td>
                <td><?= htmlspecialchars($dtbahansablon->qty_2) ?></td>
                <td><?= htmlspecialchars($dtbahansablon->tanggal) ?></td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
