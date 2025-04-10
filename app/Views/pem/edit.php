<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>

<section class="section">
    <div class="section-header">
        <a href="<?= site_url('pembelian') ?>" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    
    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>Edit Transaksi Pembelian</h4>
            </div>
            <div class="card-body">
                <form method="post" action="<?= site_url('pembelian/' . $dtpembelian->id_pembelian) ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="PUT">

                    
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" value="<?= old('tanggal', $dtpembelian->tanggal) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Nota</label>
                        <input type="text" class="form-control" name="nota" value="<?= old('nota', $dtpembelian->nota) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Supplier</label>
                        <select class="form-control" name="id_setupsupplier" required>
                            <option value="" hidden>-- Pilih Supplier --</option>
                            <?php foreach ($dtsetupsupplier as $key => $value) : ?>
                                <option value="<?= esc($value->id_setupsupplier) ?>" <?= old('id_setupsupplier', $dtpembelian->id_setupsupplier) == $value->id_setupsupplier ? 'selected' : '' ?>>
                                    <?= esc($value->nama) ?>
                                </option>
                            <?php endforeach; ?>    
                        </select>    
                    </div>

                    <div class="form-group">
                        <label>TOP</label>
                        <input type="text" class="form-control" name="TOP" value="<?= old('TOP', $dtpembelian->TOP) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Jatuh Tempo</label>
                        <input type="date" class="form-control" name="tgl_jatuhtempo" value="<?= old('tgl_jatuhtempo', $dtpembelian->tgl_jatuhtempo) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Invoice</label>
                        <input type="date" class="form-control" name="tgl_invoice" value="<?= old('tgl_invoice', $dtpembelian->tgl_invoice) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>No Invoice</label>
                        <input type="number" class="form-control" name="no_invoice" value="<?= old('no_invoice', $dtpembelian->no_invoice) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Lokasi</label>
                        <select class="form-control" name="id_lokasi" required>
                            <option value="" hidden>-- Pilih Lokasi --</option>
                            <?php foreach ($dtlokasi as $key => $value) : ?>
                                <option value="<?= esc($value->id_lokasi) ?>" <?= old('id_lokasi', $dtpembelian->id_lokasi) == $value->id_lokasi ? 'selected' : '' ?>>
                                    <?= esc($value->nama_lokasi) ?>
                                </option>
                            <?php endforeach; ?>    
                        </select>    
                    </div>

                    <div class="form-group">
                        <label>Nama Stock</label>
                        <input type="text" class="form-control" name="nama_stock" value="<?= old('nama_stock', $dtpembelian->nama_stock) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Satuan</label>
                        <select class="form-control" name="id_satuan" required>
                            <option value="" hidden>-- Pilih Satuan --</option>
                            <?php foreach ($dtsatuan as $key => $value) : ?>
                                <option value="<?= esc($value->id_satuan) ?>" <?= old('id_satuan', $dtpembelian->id_satuan) == $value->id_satuan ? 'selected' : '' ?>>
                                    <?= esc($value->kode_satuan) ?>
                                </option>
                            <?php endforeach; ?>    
                        </select>    
                    </div>

                    <div class="form-group">
                        <label>Qty 1</label>
                        <input type="number" id="qty_1" class="form-control" name="qty_1" value="<?= old('qty_1', $dtpembelian->qty_1) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Qty 2</label>
                        <input type="number" id="qty_2" class="form-control" name="qty_2" value="<?= old('qty_2', $dtpembelian->qty_2) ?>">
                    </div>

                    <div class="form-group">
                        <label>Harga Satuan</label>
                        <input type="number" id="harga_satuan" class="form-control" name="harga_satuan" value="<?= old('harga_satuan', $dtpembelian->harga_satuan) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Jumlah Harga</label>
                        <input type="text" id="jml_harga" class="form-control" name="jml_harga" value="<?= number_format(old('jml_harga', $dtpembelian->jml_harga) ?: 0, 0, ',', '.') ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Disc 1 (%)</label>
                        <input type="number" id="disc_1" class="form-control" name="disc_1" value="<?= old('disc_1', $dtpembelian->disc_1) ?>">
                    </div>

                    <div class="form-group">
                        <label>Disc 2 (%)</label>
                        <input type="number" id="disc_2" class="form-control" name="disc_2" value="<?= old('disc_2', $dtpembelian->disc_2) ?>">
                    </div>

                    <div class="form-group">
                        <label>Total</label>
                        <input type="text" id="total" class="form-control" name="total" value="<?= number_format(old('total', $dtpembelian->total) ?: 0, 0, ',', '.') ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Rekening</label>
                        <select class="form-control" name="id_setupbank" required>
                            <option value="" hidden>-- Pilih Rekening --</option>
                            <?php foreach ($dtsetupbank as $key => $value) : ?>
                                <option value="<?= esc($value->id_setupbank) ?>" <?= old('id_setupbank', $dtpembelian->id_setupbank) == $value->id_setupbank ? 'selected' : '' ?>>
                                    <?= esc($value->nama_setupbank) ?>
                                </option>
                            <?php endforeach; ?>    
                        </select>    
                    </div>

                    <div class="form-group">
                        <label for="tipe">Tipe</label>
                        <select class="form-control" name="tipe" id="tipe" required>
                            <option value="" disabled selected>Pilih Tipe</option>
                            <option value="exclude" <?= old('tipe', $dtpembelian->tipe) == 'exclude' ? 'selected' : '' ?>>Exclude</option>
                            <option value="include" <?= old('tipe', $dtpembelian->tipe) == 'include' ? 'selected' : '' ?>>Include</option>
                            <option value="non_ppn" <?= old('tipe', $dtpembelian->tipe) == 'non_ppn' ? 'selected' : '' ?>>Non PPN</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Sub Total</label>
                        <input type="text" id="sub_total" class="form-control" name="sub_total" value="<?= number_format(old('sub_total', $dtpembelian->sub_total) ?: 0, 0, ',', '.') ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Discount Cash</label>
                        <input type="number" id="disc_cash" class="form-control" name="disc_cash" value="<?= old('disc_cash', $dtpembelian->disc_cash) ?>">
                    </div>

                    <div class="form-group">
                        <label>PPN (%)</label>
                        <input type="number" id="ppn" class="form-control" name="ppn" value="<?= old('ppn', $dtpembelian->ppn) ?>">
                    </div>

                    <div class="form-group">
                        <label>Grand Total</label>
                        <input type="text" id="grand_total" class="form-control" name="grand_total" value="<?= number_format(old('grand_total', $dtpembelian->grand_total) ?: 0, 0, ',', '.') ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>NPWP</label>
                        <input type="text" id="npwp" class="form-control" name="npwp" value="<?= old('npwp', $dtpembelian->npwp) ?>">
                    </div>

                    <div class="form-group">
                        <label>Terbilang</label>
                        <input type="text" id="terbilang" class="form-control" name="terbilang" value="<?= old('terbilang', $dtpembelian->terbilang) ?>">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Update Data</button>
                        <button type="reset" class="btn btn-danger">Reset</button>
                    </div>
                    
                </form>          
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener("input", function() {
    const qty1 = parseFloat(document.getElementById("qty_1").value) || 0;
    const qty2 = parseFloat(document.getElementById("qty_2").value) || 0;
    const hargaSatuan = parseFloat(document.getElementById("harga_satuan").value) || 0;
    const disc1 = parseFloat(document.getElementById("disc_1").value) || 0;
    const disc2 = parseFloat(document.getElementById("disc_2").value) || 0;
    const discCash = parseFloat(document.getElementById("disc_cash").value) || 0;
    const ppn = parseFloat(document.getElementById("ppn").value) || 0;

    // Kalkulasi Jumlah Harga
    const jmlHarga = hargaSatuan * (qty1 + qty2);
    document.getElementById("jml_harga").value = formatRupiah(jmlHarga);

    // Kalkulasi Total setelah diskon bertingkat
    let totalAfterDisc1 = jmlHarga - (jmlHarga * disc1 / 100); // Diskon pertama
    let totalAfterDisc2 = totalAfterDisc1 - (totalAfterDisc1 * disc2 / 100); // Diskon kedua

    // Update total setelah diskon bertingkat
    const total = totalAfterDisc2; // Total setelah diskon bertingkat
    document.getElementById("total").value = formatRupiah(total);

    // Kalkulasi Sub Total setelah diskon cash
    const subTotal = total - (total * discCash / 100); // Sub total setelah diskon cash
    document.getElementById("sub_total").value = formatRupiah(subTotal);

    // Kalkulasi Grand Total setelah PPN
    const grandTotal = subTotal + (subTotal * ppn / 100); // Grand total dengan PPN
    document.getElementById("grand_total").value = formatRupiah(grandTotal);
});

// Fungsi untuk format angka ke Rupiah
function formatRupiah(angka) {
    return new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR" }).format(angka);
}


</script>

<?= $this->endSection(); ?>
