<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan</title>
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
    <h1>Laporan Penjualan</h1>
    <p>Tanggal: <?= $tglawal ?> - <?= $tglakhir ?></p>
    <p>Salesman: <?= $salesman ?></p>
    <p>Lokasi: <?= $lokasi ?></p>

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
                <th>Harga</th>
                <th>Jml. Harga</th>
                <th>Disc.1</th>
                <th>Disc.2</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($dtpenjualan)): ?>
                <tr>
                    <td colspan="15" style="text-align: center;">Tidak ada data untuk ditampilkan</td>
                </tr>
            <?php else: ?>
                <?php foreach ($dtpenjualan as $key => $value): ?>
                    <tr>
                        <td><?= $key + 1 ?></td>
                        <td><?= $value->tanggal ?></td>
                        <td><?= $value->nota ?></td>
                        <td><?= $value->nama_pelanggan ?></td>
                        <td><?= $value->nama_setupsalesman ?></td>
                        <td><?= $value->lokasi_asal ?></td>
                        <td><?= $value->nama_stock ?></td>
                        <td><?= $value->kode_satuan ?></td>
                        <td><?= $value->qty_1 ?></td>
                        <td><?= $value->qty_2 ?></td>
                        <td><?= "Rp " . number_format($value->harga_satuan, 0, ',', '.') ?></td>
                        <td><?= "Rp " . number_format($value->jml_harga, 0, ',', '.') ?></td>
                        <td><?= $value->disc_1 ?></td>
                        <td><?= $value->disc_2 ?></td>
                        <td><?= "Rp " . number_format($value->total, 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" style="text-align: right;">Jml. Harga:</th>
                <th><?= "Rp " . number_format($jml_harga, 0, ',', '.') ?></th>
                <th colspan="2" style="text-align: right;">Sub Total:</th>
                <th><?= "Rp " . number_format($subtotal, 0, ',', '.') ?></th>
                <th colspan="2" style="text-align: right;">Discount Cash:</th>
                <th><?= "Rp " . number_format($discount_cash, 0, ',', '.') ?></th>
                <th colspan="2" style="text-align: right;">DPP:</th>
                <th><?= "Rp " . number_format($dpp, 0, ',', '.') ?></th>
                <th colspan="2" style="text-align: right;">PPN:</th>
                <th><?= "Rp " . number_format($ppn, 0, ',', '.') ?></th>
            </tr>
            <tr>
                <th colspan="2" style="text-align: right;">Total:</th>
                <th><?= "Rp " . number_format($total, 0, ',', '.') ?></th>
                <th colspan="2" style="text-align: right;">HPP:</th>
                <th><?= "Rp " . number_format($hpp, 0, ',', '.') ?></th>
                <th colspan="2" style="text-align: right;">Laba:</th>
                <th><?= "Rp " . number_format($laba, 0, ',', '.') ?></th>
            </tr>
        </tfoot>
    </table>
</body>
</html>