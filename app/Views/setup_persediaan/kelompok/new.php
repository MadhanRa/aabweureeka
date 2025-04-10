<?= $this->extend("layout/backend") ?>;

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <!-- <h1>APA INI</h1> -->
    <a href="<?= site_url('setup_persediaan/kelompok') ?>" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali </a>
  </div>

  <div class="section-body">
    <!-- HALAMAN DINAMIS -->
    <div class="card">
      <div class="card-header">
        <h4>Setup Kelompok</h4>
      </div>
      <div class="card-body">
        <div class="form-container">
          <form method="post" action="<?= site_url('setup_persediaan/kelompok') ?> ">
            <?= csrf_field() ?>

            <div class="form-group">
              <label>Kode</label>
              <input type="text" class="form-control" name="kode_kelompok" placeholder="Kode Kelompok" required>
            </div>
            <div class="form-group">
              <label>Nama</label>
              <input type="text" class="form-control" name="nama_kelompok" placeholder="Nama Kelompok" required>
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-success">Simpan Data</button>
              <button type="reset" class="btn btn-danger">Reset</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  </div>
</section>

<?= $this->endSection(); ?>