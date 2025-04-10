<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Jurnal Umum</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <h2 class="text-center">Jurnal Umum</h2>

    <table>
        <thead>
            <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Nota</th>
            <th>Rekening</th>
            <th>B.Pembantu</th>
            <th>Nama Rekening</th>
            <th>Nama Buku Pembantu</th>
            <th>No.Ref</th>
            <th>Debet</th>
            <th>Kredit</th>
            <th>Tgl.Nota</th>
            <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
    <?php if (is_array($dtjurnalumum)): ?> 
        <!-- Jika data berupa array, lakukan foreach -->
        <?php $no = 1; ?>
        <?php foreach ($dtjurnalumum as $key => $value): ?>
            <tr>
            <td><?= $key + 1 ?></td>
                    <td><?= $value->tanggal ?></td>
                    <td><?= $value->nota ?></td>
                    <td><?= $value->rekening ?></td>
                    <td><?= $value->b_pembantu ?></td>
                    <td><?= $value->nama_rekening ?></td>
                    <td><?= $value->nama_bpembantu ?></td>
                    <td><?= $value->no_ref ?></td>
                    <td><?= $value->debet ?></td>
                    <td><?= $value->kredit ?></td>
                    <td><?= $value->tgl_nota ?></td>
                    <td><?= $value->keterangan ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?> 
        <!-- Jika data hanya satu objek, cetak langsung -->
        <tr>
            <td><?= $dtjurnalumum->tanggal ?></td>
            <td><?= $dtjurnalumum->nota ?></td>
            <td><?= $dtjurnalumum->rekening ?></td>
            <td><?= $dtjurnalumum->b_pembantu ?></td>
            <td><?= $dtjurnalumum->nama_rekening ?></td>
            <td><?= $dtjurnalumum->nama_bpembantu ?></td>
            <td><?= $dtjurnalumum->no_ref ?></td>
            <td><?= $dtjurnalumum->debet ?></td>
            <td><?= $dtjurnalumum->kredit ?></td>
            <td><?= $dtjurnalumum->tgl_nota ?></td>
            <td><?= $dtjurnalumum->keterangan ?></td>
        </tr>
    <?php endif; ?>
</tbody>

    </table>

    <div class="text-right">
        <p>Tanggal Cetak: <?= date('d-m-Y') ?></p>
    </div>
</body>
</html>
