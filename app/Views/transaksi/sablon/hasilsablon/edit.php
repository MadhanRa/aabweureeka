<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
     <a href="<?= site_url('hasilsablon') ?>" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali </a>
  </div>

  <?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success alert-dismissible show fade">
      <div class="alert-body">
        <button class="close" data-dismiss="alert">
          <span>&times;</span>
        </button>
        <?= session()->getFlashdata('success') ?>
      </div>
    </div>
  <?php endif; ?>

  <div class="section-body">
    <div class="card">
      <div class="card-header">
        <h4>Edit Hasil Sablon</h4>
      </div>
      <div class="card-body">
        <form method="post" action="<?= site_url('hasilsablon/' . $dthasilsablon->id_sablon) ?>">
          <?= csrf_field() ?>
          <input type="hidden" name="_method" value="PUT">

          <div class="form-group">
            <label>Nota</label>
            <input type="text" class="form-control" name="nota_sablon" value="<?= old('nota_sablon', $dthasilsablon->nota_sablon) ?>" required>
          </div>

          <div class="form-group">
            <label>Bahan</label>
            <input type="text" class="form-control" name="bahan_sablon" value="<?= old('bahan_sablon', $dthasilsablon->bahan_sablon) ?>" required>
          </div>

          <div class="form-group">
            <label>Lokasi</label>
            <select class="form-control" name="id_lokasi" required>
              <option value="" hidden>-- Pilih Lokasi --</option>
              <?php foreach ($dtlokasi as $key => $value) : ?>
                <option value="<?= esc($value->id_lokasi) ?>" <?= old('id_lokasi', $dthasilsablon->id_lokasi) == $value->id_lokasi ? 'selected' : '' ?>>
                  <?= esc($value->nama_lokasi) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label>Supplier</label>
            <select class="form-control" name="id_setupsupplier" required>
              <option value="" hidden>-- Pilih Supplier --</option>
              <?php foreach ($dtsetupsupplier as $key => $value) : ?>
                <option value="<?= esc($value->id_setupsupplier) ?>" <?= old('id_setupsupplier', $dthasilsablon->id_setupsupplier) == $value->id_setupsupplier ? 'selected' : '' ?>>
                  <?= esc($value->nama) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label>Terpakai</label>
            <input type="number" class="form-control" name="terpakai" value="<?= old('terpakai', $dthasilsablon->terpakai) ?>" required>
          </div>

          <div class="form-group">
            <label>Rusak</label>
            <input type="number" class="form-control" name="rusak" value="<?= old('rusak', $dthasilsablon->rusak) ?>" required>
          </div>

          <div class="form-group">
            <label>Nama Stock</label>
            <input type="text" class="form-control" name="nama_stock" value="<?= old('nama_stock', $dthasilsablon->nama_stock) ?>" required>
          </div>

          <div class="form-group">
            <label>Satuan</label>
            <select class="form-control" name="id_satuan" required>
              <option value="" hidden>-- Pilih Satuan --</option>
              <?php foreach ($dtsatuan as $key => $value) : ?>
                <option value="<?= esc($value->id_satuan) ?>" <?= old('id_satuan', $dthasilsablon->id_satuan) == $value->id_satuan ? 'selected' : '' ?>>
                  <?= esc($value->kode_satuan) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
                    <label>Qty 1</label>
                        <input type="number" id="qty_1" class="form-control" name="qty_1" value="<?= old('qty_1', $dthasilsablon->qty_1) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Qty 2</label>
                        <input type="number" id="qty_2" class="form-control" name="qty_2" value="<?= old('qty_2', $dthasilsablon->qty_2) ?>">
                    </div>

                    <div class="form-group">
                        <label>Harga Satuan</label>
                        <input type="text" id="harga_satuan" class="form-control" name="harga_satuan" value="<?= old('harga_satuan', $dthasilsablon->harga_satuan) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Jumlah Harga</label>
                        <input type="text" id="jml_harga" class="form-control" name="jml_harga" value="<?= number_format(old('jml_harga', $dthasilsablon->jml_harga) ?: 0, 0, ',', '.') ?>" readonly>
                    </div>

          <div class="form-group">
            <button type="submit" class="btn btn-success">Simpan Data</button>
            <button type="reset" class="btn btn-danger">Reset</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<script>
document.addEventListener("input", function(event) {
    // Pastikan event terjadi pada input yang relevan
    if (event.target.id === "qty_1" || event.target.id === "qty_2" || event.target.id === "harga_satuan") {
        // Ambil nilai input qty_1, qty_2, dan harga_satuan
        const qty1 = parseFloat(document.getElementById("qty_1").value) || 0;
        const qty2 = parseFloat(document.getElementById("qty_2").value) || 0;
        const hargaSatuan = parseFloat(document.getElementById("harga_satuan").value) || 0;

        // Hitung Jumlah Harga
        const jmlHarga = hargaSatuan * (qty1 + qty2);
        document.getElementById("jml_harga").value = formatRupiah(jmlHarga); // Update field Jumlah Harga
    }
});

// Fungsi untuk format angka ke format Rupiah
function formatRupiah(angka) {
    return new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR" }).format(angka);
}
</script>

<?= $this->endSection(); ?>
