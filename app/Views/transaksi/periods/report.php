<?= $this->extend("layout/backend") ?>;

<?= $this->section("content") ?>
<title>Akuntansi Eureeka &mdash; Report Tutup Buku</title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<section class="section">
    <div class="section-header">
        <h1>Report Tutup Buku Per Periode</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item">
                <a href="<?= base_url('close-period/print/' . $id) ?>" class="btn btn-danger btn-small" target="_blank">
                    <i class="fas fa-print"> Print PDF</i>
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                <h6>Grand Total Pemasukan : <span style="color: red;">Rp. <?= number_format($grandtotal_pemasukan, 2, ',', '.') ?></span></h6>
                </div>
                <div class="col-md-6">
                <h6>Grand Total Pengeluaran : <span style="color: red;">Rp. <?= number_format($grandtotal_pengeluaran, 2, ',', '.') ?></span></h6>
                </div>
            </div>
            
        </div>
    </div>

    <div class="section-body">
        <!-- HALAMAN DINAMIS -->
        <div class="card">
            <div class="card-header">
                <h4>Laporan Pembelian</h4>


            </div>
            <div class="section-body">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-md">
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
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td class="text-center"><?= $value->nota ?></td>
                                        <td class="text-center"><?= $value->tanggal ?></td>
                                        <td class="text-center"><?= $value->nama_stock ?></td>
                                        <td class="text-center"><?= $value->qty_1 ?></td>
                                        <td class="text-center"><?= number_format($value->grand_total, 2, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td colspan="5" class="text-center">Total</td>
                                    <td class="text-center" style="color: red;"><strong>Rp <?= number_format($grandtotal_pembelian, 2, ',', '.') ?></strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- data penjualan -->

        <div class="card">
            <div class="card-header">
                <h4>Laporan Penjualan</h4>


            </div>
            <div class="section-body">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-md">
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
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td class="text-center"><?= $value->nota ?></td>
                                        <td class="text-center"><?= $value->tanggal ?></td>
                                        <td class="text-center"><?= $value->nama_stock ?></td>
                                        <td class="text-center"><?= $value->qty_1 ?></td>
                                        <td class="text-center"><?= number_format($value->grand_total, 2, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td colspan="5" class="text-center">Total</td>
                                    <td class="text-center" style="color: red;"><strong>Rp <?= number_format($grandtotal_penjualan, 2, ',', '.') ?></strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- endpenjualan -->

        <!-- data retur penjualan -->

        <div class="card">
            <div class="card-header">
                <h4>Laporan Retur Penjualan</h4>


            </div>
            <div class="section-body">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-md">
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
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td class="text-center"><?= $value->nota ?></td>
                                        <td class="text-center"><?= $value->tanggal ?></td>
                                        <td class="text-center"><?= $value->nama_stock ?></td>
                                        <td class="text-center"><?= $value->qty_1 ?></td>
                                        <td class="text-center"><?= number_format($value->grand_total, 2, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td colspan="5" class="text-center">Total</td>
                                    <td class="text-center" style="color: red;"><strong>Rp <?= number_format($grandtotal_returpenjualan, 2, ',', '.') ?></strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- endpenjualan -->

        <!-- data retur Pembelian -->
        <div class="card">
            <div class="card-header">
                <h4>Laporan Retur Pembelian</h4>


            </div>
            <div class="section-body">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-md">
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
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td class="text-center"><?= $value->nota ?></td>
                                        <td class="text-center"><?= $value->tanggal ?></td>
                                        <td class="text-center"><?= $value->nama_stock ?></td>
                                        <td class="text-center"><?= $value->qty_1 ?></td>
                                        <td class="text-center"><?= number_format($value->grand_total, 2, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td colspan="5" class="text-center">Total</td>
                                    <td class="text-center" style="color: red;"><strong>Rp <?= number_format($grandtotal_returpembelian, 2, ',', '.') ?></strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- endpenjualan -->
        <!-- Penyesuaian Stok -->
        <div class="card">
            <div class="card-header">
                <h4>Laporan Penyesuaian Stok</h4>
            </div>
            <div class="section-body">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-md">
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
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td class="text-center"><?= $value->tanggal ?></td>
                                        <td class="text-center"><?= $value->nama_stock ?></td>
                                        <td class="text-center"><?= $value->kode_satuan ?></td>
                                        <td class="text-center"><?= $value->saldo ?></td>
                                        <td class="text-center"><?= $value->penyesuaian ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- end -->
        <!-- Pindah Lokasi -->
        <div class="card">
            <div class="card-header">
                <h4>Laporan Pindah Lokasi</h4>
            </div>
            <div class="section-body">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-md">
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
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td class="text-center"><?= $value->nota_pindah ?></td>
                                        <td class="text-center"><?= $value->tanggal ?></td>
                                        <td class="text-center"><?= $value->nama_stock ?></td>
                                        <td class="text-center"><?= $value->kode_satuan ?></td>
                                        <td class="text-center"><?= $value->qty_1 ?></td>
                                        <td class="text-center"><?= $value->qty_2 ?></td>
                                        <td class="text-center"><?= $value->lokasi_asal ?></td>
                                        <td class="text-center"><?= $value->lokasi_tujuan ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- end -->
        <!-- bahan sablon -->
        <div class="card">
            <div class="card-header">
                <h4>Laporan Bahan Sablon</h4>
            </div>
            <div class="section-body">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-md">
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
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td class="text-center"><?= $value->nota_pindah ?></td>
                                        <td class="text-center"><?= $value->tanggal ?></td>
                                        <td class="text-center"><?= $value->nama_stock ?></td>
                                        <td class="text-center"><?= $value->kode_satuan ?></td>
                                        <td class="text-center"><?= $value->qty_1 ?></td>
                                        <td class="text-center"><?= $value->qty_2 ?></td>
                                        <td class="text-center"><?= $value->lokasi_asal ?></td>
                                        <td class="text-center"><?= $value->lokasi_tujuan ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- end -->
        <!-- hasil sablon -->
        <div class="card">
            <div class="card-header">
                <h4>Laporan Hasil Sablon</h4>
            </div>
            <div class="section-body">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-md">
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
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td class="text-center"><?= $value->nota_sablon ?></td>
                                        <td class="text-center"><?= $value->tanggal ?></td>
                                        <td class="text-center"><?= $value->nama_stock ?></td>
                                        <td class="text-center"><?= $value->nama ?></td>
                                        <td class="text-center"><?= $value->kode_satuan ?></td>
                                        <td class="text-center"><?= $value->qty_1 ?></td>
                                        <td class="text-center"><?= $value->qty_2 ?></td>
                                        <td class="text-center"><?= $value->lokasi_asal ?></td>
                                        <td><?= "Rp " . number_format($value->jml_harga, 0, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- end -->
        <!-- pemakaian bahan -->
        <div class="card">
            <div class="card-header">
                <h4>Laporan Pemakaian bahan</h4>
            </div>
            <div class="section-body">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-md">
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
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td class="text-center"><?= $value->nota_bahan ?></td>
                                        <td class="text-center"><?= $value->tanggal ?></td>
                                        <td class="text-center"><?= $value->nama_stock ?></td>
                                        <td class="text-center"><?= $value->kode_satuan ?></td>
                                        <td class="text-center"><?= $value->qty_1 ?></td>
                                        <td class="text-center"><?= $value->qty_2 ?></td>
                                        <td class="text-center"><?= $value->lokasi_asal ?></td>
                                        <td class="text-center"><?= $value->nama_kelproduksi ?></td>
                                        <td class="text-center"><?= $value->nama_setupbank ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- end -->
        <!-- hasil produksi -->
        <div class="card">
            <div class="card-header">
                <h4>Laporan Hasil Produksi</h4>
            </div>
            <div class="section-body">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-md">
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
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td class="text-center"><?= $value->nota_produksi ?></td>
                                        <td class="text-center"><?= $value->tanggal ?></td>
                                        <td class="text-center"><?= $value->nama_stock ?></td>
                                        <td class="text-center"><?= $value->kode_satuan ?></td>
                                        <td class="text-center"><?= $value->qty_1 ?></td>
                                        <td class="text-center"><?= $value->qty_2 ?></td>
                                        <td class="text-center"><?= "Rp " . number_format($value->harga_satuan, 0, ',', '.') ?></td>
                                        <td class="text-center"><?= "Rp " . number_format($value->jml_harga, 0, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- end -->
        <!-- Piutang salesman -->
        <div class="card">
            <div class="card-header">
                <h4>Laporan Piutang Salesman</h4>
            </div>
            <div class="section-body">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-md">
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
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td class="text-center"><?= $value->nota ?></td>
                                        <td class="text-center"><?= $value->tanggal ?></td>
                                        <td class="text-center"><?= $value->nama_setupsalesman ?></td>
                                        <td class="text-center"><?= "Rp " . number_format($value->nilai_pelunasan, 0, ',', '.') ?></td>
                                        <td class="text-center"><?= $value->diskon ?></td>
                                        <td class="text-center"><?= $value->pdpt ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- end -->
        <!-- Piutang usaha -->
        <div class="card">
            <div class="card-header">
                <h4>Laporan Piutang usaha</h4>
            </div>
            <div class="section-body">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-md">
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
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td class="text-center"><?= $value->nota ?></td>
                                        <td class="text-center"><?= $value->tanggal ?></td>
                                        <td class="text-center"><?= $value->nama_pelanggan ?></td>
                                        <td class="text-center"><?= "Rp " . number_format($value->nilai_pelunasan, 0, ',', '.') ?></td>
                                        <td class="text-center"><?= $value->diskon ?></td>
                                        <td class="text-center"><?= $value->pdpt ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- end -->
        <!-- Hutang -->
        <div class="card">
            <div class="card-header">
                <h4>Laporan Pelunasan Hutang</h4>
            </div>
            <div class="section-body">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-md">
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
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td class="text-center"><?= $value->nota ?></td>
                                        <td class="text-center"><?= $value->tanggal ?></td>
                                        <td class="text-center"><?= $value->nama ?></td>
                                        <td class="text-center"><?= "Rp " . number_format($value->nilai_pelunasan, 0, ',', '.') ?></td>
                                        <td class="text-center"><?= $value->diskon ?></td>
                                        <td class="text-center"><?= $value->pdpt ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- end -->
        <!-- mutasi kasbank -->
        <div class="card">
            <div class="card-header">
                <h4>Laporan Mutasi Kas Bank</h4>
            </div>
            <div class="section-body">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-md">
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
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td class="text-center"><?= $value->nota ?></td>
                                        <td class="text-center"><?= $value->tgl_nota ?></td>
                                        <td class="text-center"><?= $value->tanggal ?></td>
                                        <td class="text-center"><?= $value->rekening ?></td>
                                        <td class="text-center"><?= $value->nama_rekening ?></td>
                                        <td class="text-center"><?= $value->nama_bpembantu ?></td>
                                        <td class="text-center"><?= $value->no_ref ?></td>
                                        <td class="text-center"><?= "Rp " . number_format($value->debet, 0, ',', '.') ?></td>
                                        <td class="text-center"><?= "Rp " . number_format($value->kredit, 0, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- end -->
        <!-- Jurnal Umum -->
        <div class="card">
            <div class="card-header">
                <h4>Laporan Jurnal Umum</h4>
            </div>
            <div class="section-body">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-md">
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
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td class="text-center"><?= $value->nota ?></td>
                                        <td class="text-center"><?= $value->tgl_nota ?></td>
                                        <td class="text-center"><?= $value->tanggal ?></td>
                                        <td class="text-center"><?= $value->rekening ?></td>
                                        <td class="text-center"><?= $value->nama_rekening ?></td>
                                        <td class="text-center"><?= $value->nama_bpembantu ?></td>
                                        <td class="text-center"><?= $value->no_ref ?></td>
                                        <td class="text-center"><?= "Rp " . number_format($value->debet, 0, ',', '.') ?></td>
                                        <td class="text-center"><?= "Rp " . number_format($value->kredit, 0, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- end -->
        <!-- kas kecil -->
        <div class="card">
            <div class="card-header">
                <h4>Laporan Kas kecil</h4>
            </div>
            <div class="section-body">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-md">
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
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td class="text-center"><?= $value->nota ?></td>
                                        <td class="text-center"><?= $value->tanggal ?></td>
                                        <td class="text-center"><?= $value->rekening ?></td>
                                        <td class="text-center"><?= $value->nama_rekening ?></td>
                                        <td class="text-center"><?= $value->nama_bpembantu ?></td>
                                        <td class="text-center"><?= "Rp " . number_format($value->rp, 0, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- end -->
        <!-- stock opname -->
        <div class="card">
            <div class="card-header">
                <h4>Laporan Stock Opname</h4>
            </div>
            <div class="section-body">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-md">
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
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td class="text-center"><?= $value->nota ?></td>
                                        <td class="text-center"><?= $value->tanggal ?></td>
                                        <td class="text-center"><?= $value->nama_lokasi ?></td>
                                        <td class="text-center"><?= $value->nama_setupuser ?></td>
                                        <td class="text-center"><?= $value->nama_stock ?></td>
                                        <td class="text-center"><?= $value->kode_satuan ?></td>
                                        <td class="text-center"><?= $value->qty_1 ?></td>
                                        <td class="text-center"><?= $value->qty_2 ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- end -->

    </div>
</section>

<?= $this->endSection(); ?>