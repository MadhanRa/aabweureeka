<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
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
    <h1>Laporan Final Periode Tutup Buku</h1>
<br>
    <table>
    <thead>
            <tr>
                <th>Grand Total Pemasukan</th>
                <th>Grand Total Pengeluaran</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="color: red;"><strong>Rp. <?= number_format($grandtotal_pemasukan, 2, ',', '.') ?></strong></td>
                <td style="color: red;"><strong>Rp. <?= number_format($grandtotal_pengeluaran, 2, ',', '.') ?></strong></td>
            </tr>
        </tbody>
    </table>
<br>
    <h3>Laporan Pembelian</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nota</th>
                <th>Tanggal</th>
                <th>Nama</th>
                <th>Qty</th>
                <th>Grand Total</th>
            </tr>
        </thead>
        <tbody>
            <!-- TEMPAT FOREACH -->
            <?php
            $no = 1;
            foreach ($data_pembelian as $value) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $value->nota ?></td>
                    <td><?= $value->tanggal ?></td>
                    <td><?= $value->nama_stock ?></td>
                    <td><?= $value->qty_1 ?></td>
                    <td><?= number_format($value->grand_total, 2, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="5">Total</td>
                <td style="color: red;"><strong>Rp <?= number_format($grandtotal_pembelian, 2, ',', '.') ?></strong></td>
            </tr>
        </tbody>
    </table>




    <br>
    <h3>Laporan Penjualan</h3>
    <!-- data penjualan -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nota</th>
                <th>Tanggal</th>
                <th>Nama</th>
                <th>Qty</th>
                <th>Grand Total</th>
            </tr>
        </thead>
        <tbody>
            <!-- TEMPAT FOREACH -->
            <?php
            $no = 1;
            foreach ($data_penjualan as $value) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $value->nota ?></td>
                    <td><?= $value->tanggal ?></td>
                    <td><?= $value->nama_stock ?></td>
                    <td><?= $value->qty_1 ?></td>
                    <td><?= number_format($value->grand_total, 2, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="5">Total</td>
                <td style="color: red;"><strong>Rp <?= number_format($grandtotal_penjualan, 2, ',', '.') ?></strong></td>
            </tr>
        </tbody>
    </table>
    <br>
    <h3>Laporan Retur Penjualan</h3>
    <!-- data retur penjualan -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nota</th>
                <th>Tanggal</th>
                <th>Nama</th>
                <th>Qty</th>
                <th>Grand Total</th>
            </tr>
        </thead>
        <tbody>
            <!-- TEMPAT FOREACH -->
            <?php
            $no = 1;
            foreach ($retur_penjualan as $value) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $value->nota ?></td>
                    <td><?= $value->tanggal ?></td>
                    <td><?= $value->nama_stock ?></td>
                    <td><?= $value->qty_1 ?></td>
                    <td><?= number_format($value->grand_total, 2, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="5">Total</td>
                <td style="color: red;"><strong>Rp <?= number_format($grandtotal_returpenjualan, 2, ',', '.') ?></strong></td>
            </tr>
        </tbody>
    </table>





    <!-- endpenjualan -->
    <br>
    <h3>Laporan Retur Pembelian</h3>
    <!-- data retur Pembelian -- -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nota</th>
                <th>Tanggal</th>
                <th>Nama</th>
                <th>Qty</th>
                <th>Grand Total</th>
            </tr>
        </thead>
        <tbody>
            <!-- TEMPAT FOREACH -->
            <?php
            $no = 1;
            foreach ($retur_pembelian as $value) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $value->nota ?></td>
                    <td><?= $value->tanggal ?></td>
                    <td><?= $value->nama_stock ?></td>
                    <td><?= $value->qty_1 ?></td>
                    <td><?= number_format($value->grand_total, 2, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="5">Total</td>
                <td style="color: red;"><strong>Rp <?= number_format($grandtotal_returpembelian, 2, ',', '.') ?></strong></td>
            </tr>
        </tbody>
    </table>




    <!-- endpenjualan -->
    <br>
    <h3>Laporan Penyesuaian stok</h3>
    <!-- Penyesuaian Stok -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Stock</th>
                <th>Satuan</th>
                <th>Saldo (Qty 1 / Qty 2)</th>
                <th>Penyesuaian</th>
            </tr>
        </thead>
        <tbody>
            <!-- TEMPAT FOREACH -->
            <?php
            $no = 1;
            foreach ($penyesuian_stok as $value) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $value->tanggal ?></td>
                    <td><?= $value->nama_stock ?></td>
                    <td><?= $value->kode_satuan ?></td>
                    <td><?= $value->saldo ?></td>
                    <td><?= $value->penyesuaian ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>




    <!-- end -->
    <br>
    <h3>Laporan Pindah Lokasi</h3>
    <!-- Pindah Lokasi -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nota</th>
                <th>Tanggal</th>
                <th>Nama Stock</th>
                <th>Satuan</th>
                <th>Qty 1</th>
                <th>Qty 2</th>
                <th>Lokasi Asal</th>
                <th>Lokasi Tujuan</th>
            </tr>
        </thead>
        <tbody>
            <!-- TEMPAT FOREACH -->
            <?php
            $no = 1;
            foreach ($pindah_lokasi as $value) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $value->nota_pindah ?></td>
                    <td><?= $value->tanggal ?></td>
                    <td><?= $value->nama_stock ?></td>
                    <td><?= $value->kode_satuan ?></td>
                    <td><?= $value->qty_1 ?></td>
                    <td><?= $value->qty_2 ?></td>
                    <td><?= $value->lokasi_asal ?></td>
                    <td><?= $value->lokasi_tujuan ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>




    <!-- end -->
    <br>
    <h3>Laporan Bahan Sablon</h3>
    <!-- bahan sablon -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nota</th>
                <th>Tanggal</th>
                <th>Nama Stock</th>
                <th>Satuan</th>
                <th>Qty 1</th>
                <th>Qty 2</th>
                <th>Lokasi Asal</th>
                <th>Lokasi Tujuan</th>
            </tr>
        </thead>
        <tbody>
            <!-- TEMPAT FOREACH -->
            <?php
            $no = 1;
            foreach ($bahan_sablon as $value) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $value->nota_pindah ?></td>
                    <td><?= $value->tanggal ?></td>
                    <td><?= $value->nama_stock ?></td>
                    <td><?= $value->kode_satuan ?></td>
                    <td><?= $value->qty_1 ?></td>
                    <td><?= $value->qty_2 ?></td>
                    <td><?= $value->lokasi_asal ?></td>
                    <td><?= $value->lokasi_tujuan ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>




    <!-- end -->
    <br>
    <h3>Laporan Hasil Sablon</h3>
    <!-- hasil sablon -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nota</th>
                <th>Tanggal</th>
                <th>Nama Stock</th>
                <th>Supplier</th>
                <th>Satuan</th>
                <th>Qty 1</th>
                <th>Qty 2</th>
                <th>Lokasi</th>
                <th>Rp</th>
            </tr>
        </thead>
        <tbody>
            <!-- TEMPAT FOREACH -->
            <?php
            $no = 1;
            foreach ($hasil_sablon as $value) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $value->nota_sablon ?></td>
                    <td><?= $value->tanggal ?></td>
                    <td><?= $value->nama_stock ?></td>
                    <td><?= $value->nama ?></td>
                    <td><?= $value->kode_satuan ?></td>
                    <td><?= $value->qty_1 ?></td>
                    <td><?= $value->qty_2 ?></td>
                    <td><?= $value->lokasi_asal ?></td>
                    <td><?= "Rp " . number_format($value->jml_harga, 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>




    <!-- end -->
    <br>
    <h3>Laporan Pemakaian Bahan</h3>
    <!-- pemakaian bahan -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nota</th>
                <th>Tanggal</th>
                <th>Nama Stock</th>
                <th>Satuan</th>
                <th>Qty 1</th>
                <th>Qty 2</th>
                <th>Lokasi</th>
                <th>Biaya Produk</th>
                <th>Kelompok Produksi</th>
            </tr>
        </thead>
        <tbody>
            <!-- TEMPAT FOREACH -->
            <?php
            $no = 1;
            foreach ($pemakaian_bahan as $value) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $value->nota_bahan ?></td>
                    <td><?= $value->tanggal ?></td>
                    <td><?= $value->nama_stock ?></td>
                    <td><?= $value->kode_satuan ?></td>
                    <td><?= $value->qty_1 ?></td>
                    <td><?= $value->qty_2 ?></td>
                    <td><?= $value->lokasi_asal ?></td>
                    <td><?= $value->nama_kelproduksi ?></td>
                    <td><?= $value->nama_setupbank ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>




    <!-- end -->
    <br>
    <h3>Laporan Hasil Produksi</h3>
    <!-- hasil produksi -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nota</th>
                <th>Tanggal</th>
                <th>Nama Stock</th>
                <th>Satuan</th>
                <th>Qty 1</th>
                <th>Qty 2</th>
                <th>Harga Satuan</th>
                <th>Jumlah Harga</th>
            </tr>
        </thead>
        <tbody>
            <!-- TEMPAT FOREACH -->
            <?php
            $no = 1;
            foreach ($hasil_produksi as $value) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $value->nota_produksi ?></td>
                    <td><?= $value->tanggal ?></td>
                    <td><?= $value->nama_stock ?></td>
                    <td><?= $value->kode_satuan ?></td>
                    <td><?= $value->qty_1 ?></td>
                    <td><?= $value->qty_2 ?></td>
                    <td><?= "Rp " . number_format($value->harga_satuan, 0, ',', '.') ?></td>
                    <td><?= "Rp " . number_format($value->jml_harga, 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>




    <!-- end -->
    <br>
    <h3>Laporan Piutang Salesman</h3>
    <!-- Piutang salesman -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nota</th>
                <th>Tanggal</th>
                <th>Salesman</th>
                <th>Nilai Pelunasan</th>
                <th>Discount</th>
                <th>Pendapatan Lain</th>
            </tr>
        </thead>
        <tbody>
            <!-- TEMPAT FOREACH -->
            <?php
            $no = 1;
            foreach ($piutang_salesman as $value) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $value->nota ?></td>
                    <td><?= $value->tanggal ?></td>
                    <td><?= $value->nama_setupsalesman ?></td>
                    <td><?= "Rp " . number_format($value->nilai_pelunasan, 0, ',', '.') ?></td>
                    <td><?= $value->diskon ?></td>
                    <td><?= $value->pdpt ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>




    <!-- end -->
    <br>
    <h3>Laporan Piutang Usaha</h3>
    <!-- Piutang usaha -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nota</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Nilai Pelunasan</th>
                <th>Discount</th>
                <th>Pendapatan Lain</th>
            </tr>
        </thead>
        <tbody>
            <!-- TEMPAT FOREACH -->
            <?php
            $no = 1;
            foreach ($piutang_usaha as $value) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $value->nota ?></td>
                    <td><?= $value->tanggal ?></td>
                    <td><?= $value->nama_pelanggan ?></td>
                    <td><?= "Rp " . number_format($value->nilai_pelunasan, 0, ',', '.') ?></td>
                    <td><?= $value->diskon ?></td>
                    <td><?= $value->pdpt ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>




    <!-- end -->
    <br>
    <h3>Laporan Pelunasan Hutang</h3>
    <!-- Hutang -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nota</th>
                <th>Tanggal</th>
                <th>Supplier</th>
                <th>Nilai Pelunasan</th>
                <th>Discount</th>
                <th>Pendapatan Lain</th>
            </tr>
        </thead>
        <tbody>
            <!-- TEMPAT FOREACH -->
            <?php
            $no = 1;
            foreach ($hutang as $value) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $value->nota ?></td>
                    <td><?= $value->tanggal ?></td>
                    <td><?= $value->nama ?></td>
                    <td><?= "Rp " . number_format($value->nilai_pelunasan, 0, ',', '.') ?></td>
                    <td><?= $value->diskon ?></td>
                    <td><?= $value->pdpt ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>




    <!-- end -->
    <br>
    <h3>Laporan Mutasi Kas bank</h3>
    <!-- mutasi kasbank -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nota</th>
                <th>Tanggal Nota</th>
                <th>Tanggal</th>
                <th>Rekening kas dan setara</th>
                <th>Nama Rekening</th>
                <th>Nama B. Pembantu</th>
                <th>No ref</th>
                <th>Debet</th>
                <th>Kredit</th>
            </tr>
        </thead>
        <tbody>
            <!-- TEMPAT FOREACH -->
            <?php
            $no = 1;
            foreach ($mutasi_kasbank as $value) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $value->nota ?></td>
                    <td><?= $value->tgl_nota ?></td>
                    <td><?= $value->tanggal ?></td>
                    <td><?= $value->rekening ?></td>
                    <td><?= $value->nama_rekening ?></td>
                    <td><?= $value->nama_bpembantu ?></td>
                    <td><?= $value->no_ref ?></td>
                    <td><?= "Rp " . number_format($value->debet, 0, ',', '.') ?></td>
                    <td><?= "Rp " . number_format($value->kredit, 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>




    <!-- end -->
    <br>
    <h3>Laporan Jurnal Umum</h3>
    <!-- Jurnal Umum -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nota</th>
                <th>Tanggal Nota</th>
                <th>Tanggal</th>
                <th>Rekening</th>
                <th>Nama Rekening</th>
                <th>Nama B. Pembantu</th>
                <th>No ref</th>
                <th>Debet</th>
                <th>Kredit</th>
            </tr>
        </thead>
        <tbody>
            <!-- TEMPAT FOREACH -->
            <?php
            $no = 1;
            foreach ($jurnal_umum as $value) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $value->nota ?></td>
                    <td><?= $value->tgl_nota ?></td>
                    <td><?= $value->tanggal ?></td>
                    <td><?= $value->rekening ?></td>
                    <td><?= $value->nama_rekening ?></td>
                    <td><?= $value->nama_bpembantu ?></td>
                    <td><?= $value->no_ref ?></td>
                    <td><?= "Rp " . number_format($value->debet, 0, ',', '.') ?></td>
                    <td><?= "Rp " . number_format($value->kredit, 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>




    <!-- end -->
    <br>
    <h3>Laporan Kas Kecil</h3>
    <!-- kas kecil -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nota</th>
                <th>Tanggal</th>
                <th>Rekening</th>
                <th>Nama Rekening</th>
                <th>Nama B Pembantu</th>
                <th>Rp</th>
            </tr>
        </thead>
        <tbody>
            <!-- TEMPAT FOREACH -->
            <?php
            $no = 1;
            foreach ($kas_kecil as $value) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $value->nota ?></td>
                    <td><?= $value->tanggal ?></td>
                    <td><?= $value->rekening ?></td>
                    <td><?= $value->nama_rekening ?></td>
                    <td><?= $value->nama_bpembantu ?></td>
                    <td><?= "Rp " . number_format($value->rp, 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>




    <!-- end -->
    <br>
    <h3>Laporan Stock Opname</h3>
    <!-- stock opname -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nota</th>
                <th>Tanggal</th>
                <th>Lokasi Asal</th>
                <th>User</th>
                <th>Nama Stok</th>
                <th>Satuan</th>
                <th>Qty 1</th>
                <th>Qty 2</th>
            </tr>
        </thead>
        <tbody>
            <!-- TEMPAT FOREACH -->
            <?php
            $no = 1;
            foreach ($stock_opname as $value) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $value->nota ?></td>
                    <td><?= $value->tanggal ?></td>
                    <td><?= $value->nama_lokasi ?></td>
                    <td><?= $value->nama_setupuser ?></td>
                    <td><?= $value->nama_stock ?></td>
                    <td><?= $value->kode_satuan ?></td>
                    <td><?= $value->qty_1 ?></td>
                    <td><?= $value->qty_2 ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>




    <!-- end -->

</body>

</html>