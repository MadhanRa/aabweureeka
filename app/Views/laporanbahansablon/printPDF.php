<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        th {
            background-color: #009548;
            color: white;
            text-align: center;
        }

        td {
            text-align: left;
            padding: 5px;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Laporan Bahan Disablon</h2>
    <p><strong>Tanggal: </strong><?= $tglawal ?> - <?= $tglakhir ?></p>

    <table>
        <thead>
            <tr>
                  <th>No</th>
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
            <?php if (!empty($dtbahansablon)) : ?>
                <?php foreach ($dtbahansablon as $key => $value) : ?>
                    <tr>
                    <td><?= $key + 1 ?></td>
                    <td><?= $value->lokasi_asal ?></td>
                    <td><?= $value->lokasi_tujuan ?></td>
                    <td><?= $value->nota_pindah ?></td>
                    <td><?= $value->nama_stock ?></td>
                    <td><?= $value->kode_satuan ?></td>
                    <td><?= $value->qty_1 ?></td>
                    <td><?= $value->qty_2 ?></td>
                    <td><?= $value->tanggal ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="13" style="text-align: center;">Tidak ada data untuk ditampilkan</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
