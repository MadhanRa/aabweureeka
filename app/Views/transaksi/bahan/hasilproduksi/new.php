<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <!-- <h1>APA INI</h1> -->
     <a href="<?= site_url('hasilproduksi') ?>" class="btn btn-primary">
       <i class="fas fa-arrow-left"></i> Kembali
     </a>
  </div>

  <div class="section-body">
    <!-- HALAMAN DINAMIS -->
    <div class="card">
      <div class="card-header">
        <h4>Hasil Produksi</h4>
      </div>
      <div class="card-body">
        <form method="post" action="<?= site_url('hasilproduksi') ?>">
          <?= csrf_field() ?>

          <div class="form-group">
            <label>Nota</label>
            <input type="text" class="form-control" name="nota_produksi" value="<?= old('nota_produksi') ?>" required>
          </div>

          <div class="form-group">
            <label>Lokasi</label>
            <select class="form-control" name="id_lokasi" required>
              <option value="" hidden>-- Pilih Lokasi --</option>
              <?php foreach ($dtlokasi as $key => $value) : ?>
                <option value="<?= esc($value->id_lokasi) ?>" <?= old('id_lokasi') == $value->id_lokasi ? 'selected' : '' ?>>
                  <?= esc($value->nama_lokasi) ?>
                </option>
              <?php endforeach; ?>    
            </select>    
          </div>

          <div class="form-group">
            <label>Kelompok Produksi</label>
            <select class="form-control" name="id_kelproduksi" required>
              <option value="" hidden>-- Pilih Kelompok Produksi --</option>
              <?php foreach ($dtkelompokproduksi as $key => $value) : ?>
                <option value="<?= esc($value->id_kelproduksi) ?>" <?= old('id_kelproduksi') == $value->id_kelproduksi ? 'selected' : '' ?>>
                  <?= esc($value->nama_kelproduksi) ?>
                </option>
              <?php endforeach; ?>    
            </select>    
          </div>

          <div class="form-group">
            <label>Nama Stock</label>
            <input type="text" class="form-control" name="nama_stock" value="<?= old('nama_stock') ?>" required>
          </div>

          <div class="form-group">
            <label>Satuan</label>
            <select class="form-control" name="id_satuan" required>
              <option value="" hidden>-- Pilih Satuan --</option>
              <?php foreach ($dtsatuan as $key => $value) : ?>
                <option value="<?= esc($value->id_satuan) ?>" <?= old('id_satuan') == $value->id_satuan ? 'selected' : '' ?>>
                  <?= esc($value->kode_satuan) ?>
                </option>
              <?php endforeach; ?>    
            </select>    
          </div>

          <div class="form-group">
              <label>Qty 1</label>
              <input type="number" id="qty_1" class="form-control" name="qty_1" value="<?= old('qty_1') ?>" required>
          </div>

          <div class="form-group">
              <label>Qty 2</label>
              <input type="number" id="qty_2" class="form-control" name="qty_2" value="<?= old('qty_2') ?>">
          </div>

          <div class="form-group">
              <label>Harga Satuan</label>
              <input type="text" id="harga_satuan" class="form-control" name="harga_satuan" value="<?= old('harga_satuan') ?>" required>
          </div>

          <div class="form-group">
              <label>Jumlah Harga</label>
              <input type="text" id="jml_harga" class="form-control" name="jml_harga" value="<?= number_format(old('jml_harga') ?: 0, 0, ',', '.') ?>" readonly>
          </div>

          <div class="form-group">
            <label>Tanggal</label>
            <input type="date" class="form-control" name="tanggal" value="<?= old('tanggal') ?>" required>
          </div>

          <div class="form-group">
            <label>Tanggal</label>
            <input type="date" class="form-control" name="tanggal" value="<?= old('tanggal') ?>" required>
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
document.addEventListener("input", function() {
    const qty1 = parseFloat(document.getElementById("qty_1").value) || 0;
    const qty2 = parseFloat(document.getElementById("qty_2").value) || 0;
    const hargaSatuan = parseFloat(document.getElementById("harga_satuan").value) || 0;

    // Kalkulasi Jumlah Harga
    const jmlHarga = hargaSatuan * (qty1 + qty2);
    document.getElementById("jml_harga").value = formatRupiah(jmlHarga);

});

// Fungsi untuk format angka ke Rupiah
function formatRupiah(angka) {
    return new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR" }).format(angka);
}


</script>

<?= $this->endSection(); ?>
