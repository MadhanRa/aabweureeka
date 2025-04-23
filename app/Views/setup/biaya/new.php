<?= $this->extend("layout/backend") ?>;

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <a href="<?= site_url('setup/biaya') ?>" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali </a>
  </div>

  <div class="section-body">
    <div class="card">
      <div class="card-header">
        <h4>Tambah Biaya & Pendapatan</h4>
      </div>
      <div class="card-body">
        <?php if (empty($dtrekening)) : ?>
          <div class="alert alert-danger">Data Rekening Kosong, Silahkan Tambah Rekening Terlebih Dahulu</div>
        <?php else : ?>
          <div class="form-container">
            <form method="post" action="<?= site_url('setup/biaya') ?>">
              <?= csrf_field() ?>
              <div class="form-group">
                <label>Rekening Bank</label>
                <!-- Kalau Data rekening kosong -->
                <select class="form-control" name="id_setupbuku" required>
                  <option value="" hidden>--Pilih Rekening--</option>
                  <?php foreach ($dtrekening as $setupbuku) : ?>
                    <option value="<?= $setupbuku->id_setupbuku ?>"><?= $setupbuku->kode_setupbuku . '-' . $setupbuku->nama_setupbuku ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <label>Kode</label>
                <input type="text" class="form-control" name="kode_setupbiaya" required>
              </div>
              <div class="form-group">
                <label>Nama</label>
                <input type="text" class="form-control" name="nama_setupbiaya" required>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-success">Simpan Data</button>
                <button type="reset" class="btn btn-danger">Reset</button>
              </div>
            </form>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<?= $this->endSection(); ?>