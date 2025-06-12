<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <a href="<?= site_url('lunassalesman') ?>" class="btn btn-primary">
      <i class="fas fa-arrow-left"></i> Kembali
    </a>
  </div>

  <div class="section-body">
    <div class="card">
      <div class="card-header">
        <h4>Edit Pelunasan Piutang Salesman</h4>
      </div>
      <div class="card-body">
        <form method="post" action="<?= site_url('lunassalesman/' . $dtlunassalesman->id_lunashusalesman) ?>">
          <?= csrf_field() ?>
          <!-- Hidden field for method spoofing -->
          <input type="hidden" name="_method" value="PUT">

          <div class="form-group">
            <label>Tanggal</label>
            <input type="date" class="form-control" name="tanggal" value="<?= esc($dtlunassalesman->tanggal) ?>" required>
          </div>

          <div class="form-group">
            <label>Nota</label>
            <input type="text" class="form-control" name="nota" value="<?= esc($dtlunassalesman->nota) ?>" required>
          </div>

          <div class="form-group">
            <label>Salesman</label>
            <select class="form-control" name="id_salesman" required>
              <option value="" hidden>-- Pilih Salesman --</option>
              <?php foreach ($dtsalesman as $key => $value) : ?>
                <option value="<?= esc($value->id_salesman) ?>" <?= $dtlunassalesman->id_salesman == $value->id_salesman ? 'selected' : '' ?>>
                  <?= esc($value->nama_salesman) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label>Rekening Pelunasan</label>
            <select class="form-control" name="id_setupbank" required>
              <option value="" hidden>-- Pilih Rekening Pelunasan --</option>
              <?php foreach ($dtsetupbank as $key => $value) : ?>
                <option value="<?= esc($value->id_setupbank) ?>" <?= $dtlunassalesman->id_setupbank == $value->id_setupbank ? 'selected' : '' ?>>
                  <?= esc($value->nama_setupbank) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Nota Penjualan - id_penjualan -->
          <div class="form-group">
            <label>Nota Penjualan</label>
            <select class="form-control" name="id_penjualan" required>
              <option value="" hidden>-- Pilih Nota Penjualan --</option>
              <?php foreach ($dtpenjualan as $key => $value) : ?>
                <option value="<?= esc($value->id_penjualan) ?>" <?= $dtlunassalesman->id_penjualan == $value->id_penjualan ? 'selected' : '' ?>>
                  <?= esc($value->nota) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label>Saldo</label>
            <input type="text" class="form-control" name="saldo" value="<?= number_format(floatval(old('saldo', $dtlunassalesman->saldo) ?: 0), 0, ',', '.') ?>">
          </div>

          <div class="form-group">
            <label>Nilai Pelunasan</label>
            <input type="text" class="form-control" name="nilai_pelunasan" value="<?= number_format(floatval(old('nilai_pelunasan', $dtlunassalesman->nilai_pelunasan) ?: 0), 0, ',', '.') ?>">
          </div>

          <div class="form-group">
            <label>Discount</label>
            <input type="number" class="form-control" name="diskon" value="<?= esc($dtlunassalesman->diskon) ?>" required>
          </div>

          <div class="form-group">
            <label>PDPT.Lain-lain</label>
            <input type="text" class="form-control" name="pdpt" value="<?= esc($dtlunassalesman->pdpt) ?>" required>
          </div>

          <div class="form-group">
            <label>Sisa</label>
            <input type="text" class="form-control" name="sisa" value="<?= number_format(old('sisa', $dtlunassalesman->sisa) ?: 0, 0, ',', '.') ?>" readonly>
          </div>

          <div class="form-group">
            <label>Keterangan</label>
            <input type="text" class="form-control" name="keterangan" value="<?= esc($dtlunassalesman->keterangan) ?>" required>
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