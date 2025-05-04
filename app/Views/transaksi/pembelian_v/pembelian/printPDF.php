<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Faktur Pembelian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
        }
        .header p {
            margin: 5px 0;
        }
        .flex-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            align-items: flex-start;
            
        }
        .flex-container > div {
            width: 45%; /* Atur lebar elemen agar pas di dalam satu baris */
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
        .table-info, .table-items, .table-summary {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table-info td, .table-items th, .table-items td, .table-summary td {
            padding: 5px;
            border: 1px solid black;
        }
        .table-items th {
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
    </style>
</head>
<body>

<!-- Bagian Header -->
<div class="header">
    <h1>EUREEKA PRODUKSI</h1>
    <p>Jl. Pande No. 46 Junwatu Desa a Junrejo Kec. Junrejo</p>
    <p>Kota Batu - Indonesia, Telp. 0341 464278</p>
</div>

<!-- Bagian Terima Dari dan Faktur Pembelian dengan Flexbox -->
<table style="width: 150%; border: none;">
    <tr>
        <td style="width: 50%; vertical-align: top;">
            <p><strong>Terima Dari:</strong></p>
            <p>Nama Supplier: <?= htmlspecialchars(isset($dtpembelian->nama_supplier) ? $dtpembelian->nama_supplier : '-') ?></p>
            <p>NPWP: <?= htmlspecialchars(isset($dtpembelian->npwp) ? $dtpembelian->npwp : '-') ?></p>
        </td>
        <td style="width: 50%; vertical-align: top;">
            <p><strong>Faktur Pembelian:</strong></p>
            <p>No. Faktur: <?= htmlspecialchars(isset($dtpembelian->nota) ? $dtpembelian->nota : '-') ?></p>
            <p>No. Invoice: <?= htmlspecialchars(isset($dtpembelian->no_invoice) ? $dtpembelian->no_invoice : '-') ?></p>
            <p>Tgl. Invoice: <?= htmlspecialchars(isset($dtpembelian->tgl_invoice) ? $dtpembelian->tgl_invoice : '-') ?></p>
            <p>Tgl. Jatuh Tempo: <?= htmlspecialchars(isset($dtpembelian->tgl_jatuhtempo) ? $dtpembelian->tgl_jatuhtempo : '-') ?></p>
        </td>
    </tr>
</table>

<!-- Tabel items -->
<table class="table-items">
    <thead>
        <tr>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Hrg. Satuan</th>
            <th>Qty1.</th>
            <th>Qty2.</th>
            <th>Total</th>
            <th>Discount 1</th>
            <th>Discount 2</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="text-left"><?= htmlspecialchars(isset($dtpembelian->kode_satuan) ? $dtpembelian->kode_satuan : '-') ?></td>
            <td class="text-left"><?= htmlspecialchars(isset($dtpembelian->nama_satuan) ? $dtpembelian->nama_satuan : '-') ?></td>
            <td><?= number_format(isset($dtpembelian->harga_satuan) ? $dtpembelian->harga_satuan : 0, 0, ',', '.') ?></td>
            <td><?= htmlspecialchars(isset($dtpembelian->qty_1) ? $dtpembelian->qty_1 : 0) ?></td>
            <td><?= htmlspecialchars(isset($dtpembelian->qty_2) ? $dtpembelian->qty_2 : 0) ?></td>
            <td><?= number_format(isset($dtpembelian->total) ? $dtpembelian->total : 0, 0, ',', '.') ?></td>
            <td><?= htmlspecialchars(isset($dtpembelian->disc_1) ? $dtpembelian->disc_1 : 0) ?>%</td>
            <td><?= htmlspecialchars(isset($dtpembelian->disc_2) ? $dtpembelian->disc_2 : 0) ?>%</td>
        </tr>
    </tbody>
</table>

<!-- Tabel summary -->
<table class="table-summary">
    <tr>
        <td rowspan="4" class="text-left">Terbilang: <?= htmlspecialchars(isset($dtpembelian->terbilang) ? $dtpembelian->terbilang : '-') ?></td>
        <td rowspan="4" class="text-center">Mengetahui</td>
        <td rowspan="4" class="text-center">Gudang</td>
        <td>Sub.Total</td>
        <td class="text-right"><?= number_format(isset($dtpembelian->sub_total) ? $dtpembelian->sub_total : 0, 0, ',', '.') ?></td>
    </tr>
    <tr>
        <td>Discount Cash</td>
        <td colspan="6" class="text-right"><?= htmlspecialchars(isset($dtpembelian->disc_cash) ? $dtpembelian->disc_cash : 0) ?>%</td>
    </tr>
    <tr>
        <td>PPN</td>
        <td class="text-right"><?= number_format(isset($dtpembelian->ppn) ? $dtpembelian->ppn : 0, 0, ',', '.') ?>%</td>
    </tr>
    <tr>
        <td class="grand-total">Grand Total</td>
        <td class="grand-total text-right"><?= number_format(isset($dtpembelian->grand_total) ? $dtpembelian->grand_total : 0, 0, ',', '.') ?></td>
    </tr>
</table>


</body>
</html>
