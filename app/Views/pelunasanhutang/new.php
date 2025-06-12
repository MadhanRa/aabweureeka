<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <!-- <h1>APA INI</h1> -->
    <a href="<?= site_url('pelunasanhutang') ?>" class="btn btn-primary">
      <i class="fas fa-arrow-left"></i> Kembali
    </a>
  </div>

  <div class="section-body">
    <!-- HALAMAN DINAMIS -->
    <div class="card">
      <div class="card-header">
        <h4>Pelunasan Hutang</h4>
      </div>
      <div class="card-body">
        <form method="post" action="<?= site_url('pelunasanhutang') ?>">
          <?= csrf_field() ?>

          <div class="form-group">
            <label>Tanggal</label>
            <input type="date" class="form-control" name="tanggal" value="<?= old('tanggal') ?>" required>
          </div>

          <div class="form-group">
            <label>Nota</label>
            <input type="text" class="form-control" name="nota" value="<?= old('nota') ?>" required>
          </div>

          <div class="form-group">
            <label>Supplier</label>
            <select class="form-control" name="id_setupsupplier" required>
              <option value="" hidden>-- Pilih Supplier --</option>
              <?php foreach ($dtsetupsupplier as $key => $value) : ?>
                <option value="<?= esc($value->id_setupsupplier) ?>" <?= old('id_setupsupplier') == $value->id_setupsupplier ? 'selected' : '' ?>>
                  <?= esc($value->nama) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label>Rekening Pelunasan</label>
            <select class="form-control" name="id_setupbank" required>
              <option value="" hidden>-- Pilih Rekening Pelunasan --</option>
              <?php foreach ($dtsetupbank as $key => $value) : ?>
                <option value="<?= esc($value->id_setupbank) ?>" <?= old('id_setupbank') == $value->id_setupbank ? 'selected' : '' ?>>
                  <?= esc($value->nama_setupbank) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Nota Pembelian -->
          <div class="form-group">
            <label>Nota Pembelian</label>
            <select class="form-control" name="id_pembelian" required>
              <option value="" hidden>-- Pilih Nota Pembelian --</option>
              <?php foreach ($dtpembelian as $key => $value) : ?>
                <option value="<?= esc($value->id_pembelian) ?>" <?= old('id_pembelian') == $value->id_pembelian ? 'selected' : '' ?>>
                  <?= esc($value->nota) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label>Saldo</label>
            <input type="text" class="form-control" name="saldo" value="<?= number_format(old('saldo') ?: 0, 0, ',', '.') ?>" required>
          </div>

          <div class="form-group">
            <label>Nilai Pelunasan</label>
            <input type="text" class="form-control" name="nilai_pelunasan" value="<?= number_format(old('nilai_pelunasan') ?: 0, 0, ',', '.') ?>" required>
          </div>

          <div class="form-group">
            <label>Discount</label>
            <input type="number" class="form-control" name="diskon" value="<?= old('diskon') ?>" required>
          </div>

          <div class="form-group">
            <label>PDPT.Lain-lain</label>
            <input type="text" class="form-control" name="pdpt" value="<?= old('pdpt') ?>" required>
          </div>

          <div class="form-group">
            <label>Sisa</label>
            <input type="text" class="form-control" name="sisa" value="<?= number_format(old('sisa') ?: 0, 0, ',', '.') ?>" readonly>
          </div>

          <div class="form-group">
            <label>Keterangan</label>
            <input type="text" class="form-control" name="keterangan" value="<?= old('keterangan') ?>" required>
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
    const saldo = parseFloat(document.querySelector("input[name='saldo']").value.replace(/\./g, '').replace(',', '.')) || 0;
    const nilai_pelunasan = parseFloat(document.querySelector("input[name='nilai_pelunasan']").value.replace(/\./g, '').replace(',', '.')) || 0;
    const diskon = parseFloat(document.querySelector("input[name='diskon']").value) || 0;

    // Kalkulasi Sisa: saldo - nilai_pelunasan + (diskon % dari nilai_pelunasan)
    const diskonValue = (diskon / 100) * nilai_pelunasan;
    const sisa = saldo - nilai_pelunasan + diskonValue;

    // Tampilkan hasil sisa dalam format Rupiah dan di set ke field sisa
    document.querySelector("input[name='sisa']").value = formatRupiah(sisa);
  });

  // Fungsi untuk format angka ke Rupiah
  function formatRupiah(angka) {
    return new Intl.NumberFormat("id-ID", {
      style: "currency",
      currency: "IDR"
    }).format(angka);
  }
</script>

<?= $this->endSection(); ?>