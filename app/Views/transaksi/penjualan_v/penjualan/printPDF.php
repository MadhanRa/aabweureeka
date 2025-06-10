<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Faktur Penjualan</title>
    <style>
        .header {
            text-align: center;
        }

        .title {
            color: #009548;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .table-info,
        .table-items,
        .table-summary {
            width: 100%;
            border-collapse: collapse;
        }

        .table-items th,
        .table-items td,
        .table-summary td {
            padding: 5px;
            border: 1px solid black;
        }

        .table-items thead tr>th {
            background-color: #f2f2f2;
            text-align: center;
        }

        .table-items td {
            text-align: right;
        }

        .grand-total {
            font-size: 18px;
            font-weight: bold;
            text-align: right;
        }

        .vertical-bottom {
            vertical-align: bottom;
        }

        .text-bold {
            font-weight: bold;
        }
    </style>
</head>

<body>

    <!-- Bagian Header -->
    <div class="header">
        <h1>EUREEKA PRODUKSI<br><span class="title">Faktur Penjualan</span></h1>
        <p>Jl. Pande No. 46 Junwatu Desa a Junrejo Kec. Junrejo<br>Kota Batu - Indonesia, Telp. 0341 464278 </p>
    </div>

    <div class="table-info">
        <table style="width: 150%; border: none;">
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    Nama Pelanggan: <?= htmlspecialchars(isset($dtpenjualan->nama_pelanggan) ? $dtpenjualan->nama_pelanggan : '-') ?>
                    <br>
                    No Telpon: <?= htmlspecialchars(isset($dtpenjualan->telp_pelanggan) ? $dtpenjualan->telp_pelanggan : '-') ?>
                    <br>
                    Alamat: <?= htmlspecialchars(isset($dtpenjualan->alamat_pelanggan) ? $dtpenjualan->alamat_pelanggan : '-') ?>
                </td>
                <td style="width: 50%; vertical-align: top;">
                    Salesman: <?= htmlspecialchars(isset($dtpenjualan->nama_salesman) ? $dtpenjualan->nama_salesman : '-') ?><br>
                    Tanggal: <?= htmlspecialchars(isset($dtpenjualan->tanggal) ? $dtpenjualan->tanggal : '-') ?>
                    <br>
                    No. Faktur: <?= htmlspecialchars(isset($dtpenjualan->nota) ? $dtpenjualan->nota : '-') ?>
                    <br>
                    Tgl. Jatuh Tempo: <?= htmlspecialchars(isset($dtpenjualan->tgl_jatuhtempo) ? $dtpenjualan->tgl_jatuhtempo : '-') ?>
                </td>
            </tr>
        </table>
    </div>
    <!-- Tabel items -->
    <div>
        <table class="table-items">
            <thead>
                <tr>
                    <th class="text-bold">Kode Barang</th>
                    <th class="text-bold">Nama Barang</th>
                    <th class="text-bold">Satuan</th>
                    <th class="text-bold">Qty1.</th>
                    <th class="text-bold">Qty2.</th>
                    <th class="text-bold">Hrg. Satuan</th>
                    <th class="text-bold">Total</th>
                    <th class="text-bold">Discount 1</th>
                    <th class="text-bold">Discount 2</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($dtdetail) && !empty($dtdetail)) : ?>
                    <?php foreach ($dtdetail as $item) : ?>
                        <tr>
                            <td class="text-left"><?= htmlspecialchars(isset($item->kode) ? $item->kode : '-') ?></td>
                            <td class="text-left"><?= htmlspecialchars(isset($item->nama_barang) ? $item->nama_barang : '-') ?></td>
                            <td class="text-left"><?= htmlspecialchars(isset($item->satuan) ? $item->satuan : '-') ?></td>
                            <td><?= htmlspecialchars(isset($item->qty1) ? $item->qty1 : 0) ?></td>
                            <td><?= htmlspecialchars(isset($item->qty2) ? $item->qty2 : 0) ?></td>
                            <td>Rp <?= number_format(isset($item->harga_satuan) ? $item->harga_satuan : 0, 0, ',', '.') ?></td>
                            <td>Rp <?= number_format(isset($item->total) ? $item->total : 0, 0, ',', '.') ?></td>
                            <td><?= htmlspecialchars(($item->disc_1_perc == 0) ? $item->disc_1_rp : $item->disc_1_perc . '%') ?></td>
                            <td><?= htmlspecialchars($item->disc_2_perc == 0 ? $item->disc_2_rp : $item->disc_2_perc . '%') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Tabel summary -->
    <div>
        <table class="table-summary">
            <tr>
                <td rowspan="4" class="text-left">Terbilang: <?= htmlspecialchars(isset($dtpenjualan->terbilang) ? $dtpenjualan->terbilang : '-') ?></td>
                <td rowspan="4" class="text-center">Mengetahui</td>
                <td rowspan="4" class="text-center">Gudang: <?= htmlspecialchars(isset($dtpenjualan->nama_lokasi) ? $dtpenjualan->nama_lokasi : '-') ?></td>
                <td>Sub.Total</td>
                <td class="text-right"><?= number_format(isset($dtpenjualan->sub_total) ? $dtpenjualan->sub_total : 0, 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td>Discount Cash</td>
                <td colspan="6" class="text-right"><?= htmlspecialchars(isset($dtpenjualan->disc_cash) ? $dtpenjualan->disc_cash : 0) ?>%</td>
            </tr>
            <tr>
                <td>PPN</td>
                <td class="text-right"><?= number_format(isset($dtpenjualan->ppn) ? $dtpenjualan->ppn : 0, 0, ',', '.') ?>%</td>
            </tr>
            <tr>
                <td class="grand-total">Grand Total</td>
                <td class="grand-total text-right"><?= number_format(isset($dtpenjualan->grand_total) ? $dtpenjualan->grand_total : 0, 0, ',', '.') ?></td>
            </tr>
        </table>
    </div>


</body>

</html>