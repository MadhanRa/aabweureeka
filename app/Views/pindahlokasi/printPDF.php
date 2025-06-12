<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pindah Lokasi</title>
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

<h1>Data Pindah Lokasi</h1>
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
        <?php if (is_array($dtpindahlokasi)): ?>
            <!-- Jika mencetak semua data -->
            <?php foreach ($dtpindahlokasi as $value): ?>
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
                <td><?= htmlspecialchars($dtpindahlokasi->lokasi_asal) ?></td>
                <td><?= htmlspecialchars($dtpindahlokasi->lokasi_tujuan) ?></td>
                <td><?= htmlspecialchars($dtpindahlokasi->nota_pindah) ?></td>
                <td><?= htmlspecialchars($dtpindahlokasi->nama_stock) ?></td>
                <td><?= htmlspecialchars($dtpindahlokasi->kode_satuan) ?></td>
                <td><?= htmlspecialchars($dtpindahlokasi->qty_1) ?></td>
                <td><?= htmlspecialchars($dtpindahlokasi->qty_2) ?></td>
                <td><?= htmlspecialchars($dtpindahlokasi->tanggal) ?></td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
