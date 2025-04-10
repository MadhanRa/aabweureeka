<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>

<section class="section">
    <div class="section-header">
        <a href="<?= site_url('returpembelian') ?>" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    
    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>Edit Transaksi Retur Pembelian</h4>
            </div>
            <div class="card-body">
                <form method="post" action="<?= site_url('returpembelian/' . $dtreturpembelian->id_returpembelian) ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="PUT"> <!-- For PUT request -->
                    
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" value="<?= esc($dtreturpembelian->tanggal) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Nota</label>
                        <input type="text" class="form-control" name="nota" value="<?= esc($dtreturpembelian->nota) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Supplier</label>
                        <select class="form-control" name="id_setupsupplier" required>
                          <option value="" hidden>-- Pilih Supplier --</option>
                          <?php foreach ($dtsetupsupplier as $key => $value) : ?>
                            <option value="<?= esc($value->id_setupsupplier) ?>" <?= $dtreturpembelian->id_setupsupplier == $value->id_setupsupplier ? 'selected' : '' ?>>
                              <?= esc($value->nama) ?>
                            </option>
                          <?php endforeach; ?>    
                        </select>    
                    </div>

                    <div class="form-group">
                        <label>Lokasi</label>
                        <select class="form-control" name="id_lokasi" required>
                          <option value="" hidden>-- Pilih Lokasi --</option>
                          <?php foreach ($dtlokasi as $key => $value) : ?>
                            <option value="<?= esc($value->id_lokasi) ?>" <?= $dtreturpembelian->id_lokasi == $value->id_lokasi ? 'selected' : '' ?>>
                              <?= esc($value->nama_lokasi) ?>
                            </option>
                          <?php endforeach; ?>    
                        </select>    
                    </div>

                    <div class="form-group">
                        <label>Nama Stock</label>
                        <input type="text" class="form-control" name="nama_stock" value="<?= esc($dtreturpembelian->nama_stock) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Satuan</label>
                        <select class="form-control" name="id_satuan" required>
                          <option value="" hidden>-- Pilih Satuan --</option>
                          <?php foreach ($dtsatuan as $key => $value) : ?>
                            <option value="<?= esc($value->id_satuan) ?>" <?= $dtreturpembelian->id_satuan == $value->id_satuan ? 'selected' : '' ?>>
                              <?= esc($value->kode_satuan) ?>
                            </option>
                          <?php endforeach; ?>    
                        </select>    
                    </div>

                    <div class="form-group">
                        <label>Qty 1</label>
                        <input type="number" id="qty_1" class="form-control" name="qty_1" value="<?= old('qty_1', $dtreturpembelian->qty_1) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Qty 2</label>
                        <input type="number" id="qty_2" class="form-control" name="qty_2" value="<?= old('qty_2', $dtreturpembelian->qty_2) ?>">
                    </div>

                    <div class="form-group">
                        <label>Harga Satuan</label>
                        <input type="number" id="harga_satuan" class="form-control" name="harga_satuan" value="<?= old('harga_satuan', $dtreturpembelian->harga_satuan) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Jumlah Harga</label>
                        <input type="text" id="jml_harga" class="form-control" name="jml_harga" value="<?= number_format(old('jml_harga', $dtreturpembelian->jml_harga) ?: 0, 0, ',', '.') ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Disc 1 (%)</label>
                        <input type="number" id="disc_1" class="form-control" name="disc_1" value="<?= old('disc_1', $dtreturpembelian->disc_1) ?>">
                    </div>

                    <div class="form-group">
                        <label>Disc 2 (%)</label>
                        <input type="number" id="disc_2" class="form-control" name="disc_2" value="<?= old('disc_2', $dtreturpembelian->disc_2) ?>">
                    </div>

                    <div class="form-group">
                        <label>Total</label>
                        <input type="text" id="total" class="form-control" name="total" value="<?= number_format(old('total', $dtreturpembelian->total) ?: 0, 0, ',', '.') ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Pembelian</label>
                        <select class="form-control" name="id_pembelian_tgl" required>
                          <option value="" hidden>-- Pilih Tanggal --</option>
                          <?php foreach ($dtpembelian as $key => $value) : ?>
                            <option value="<?= esc($value->id_pembelian) ?>" <?= $dtreturpembelian->id_pembelian_tgl == $value->id_pembelian ? 'selected' : '' ?>>
                              <?= esc($value->tanggal) ?>
                            </option>
                          <?php endforeach; ?>    
                        </select>    
                    </div>

                    <div class="form-group">
                        <label>Nota Pembelian</label>
                        <select class="form-control" name="id_pembelian_nota" required>
                          <option value="" hidden>-- Pilih Nota Pembelian --</option>
                          <?php foreach ($dtpembelian as $key => $value) : ?>
                            <option value="<?= esc($value->id_pembelian) ?>" <?= $dtreturpembelian->id_pembelian_nota == $value->id_pembelian ? 'selected' : '' ?>>
                              <?= esc($value->nota) ?>
                            </option>
                          <?php endforeach; ?>    
                        </select>    
                    </div>

                    <div class="form-group">
                        <label for="tipe">Pembayaran</label>
                        <select class="form-control" name="pembayaran" id="pembayaran" required>
                            <option value="kredit" <?= $dtreturpembelian->pembayaran == 'kredit' ? 'selected' : '' ?>>Kredit</option>
                            <option value="tunai" <?= $dtreturpembelian->pembayaran == 'tunai' ? 'selected' : '' ?>>Tunai</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="tipe">Tipe</label>
                        <select class="form-control" name="tipe" id="tipe" required>
                            <option value="exclude" <?= $dtreturpembelian->tipe == 'exclude' ? 'selected' : '' ?>>Exclude</option>
                            <option value="include" <?= $dtreturpembelian->tipe == 'include' ? 'selected' : '' ?>>Include</option>
                            <option value="non_ppn" <?= $dtreturpembelian->tipe == 'non_ppn' ? 'selected' : '' ?>>Non PPN</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Sub Total</label>
                        <input type="text" id="sub_total" class="form-control" name="sub_total" value="<?= number_format(old('sub_total', $dtreturpembelian->sub_total) ?: 0, 0, ',', '.') ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Discount Cash</label>
                        <input type="number" id="disc_cash" class="form-control" name="disc_cash" value="<?= old('disc_cash', $dtreturpembelian->disc_cash) ?>">
                    </div>

                    <div class="form-group">
                        <label>PPN (%)</label>
                        <input type="number" id="ppn" class="form-control" name="ppn" value="<?= old('ppn', $dtreturpembelian->ppn) ?>">
                    </div>

                    <div class="form-group">
                        <label>Grand Total</label>
                        <input type="text" id="grand_total" class="form-control" name="grand_total" value="<?= number_format(old('grand_total', $dtreturpembelian->grand_total) ?: 0, 0, ',', '.') ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>NPWP</label>
                        <input type="text" id="npwp" class="form-control" name="npwp" value="<?= old('npwp', $dtreturpembelian->npwp) ?>">
                    </div>

                    <div class="form-group">
                        <label>Terbilang</label>
                        <input type="text" id="terbilang" class="form-control" name="terbilang" value="<?= old('terbilang', $dtreturpembelian->terbilang) ?>">
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
