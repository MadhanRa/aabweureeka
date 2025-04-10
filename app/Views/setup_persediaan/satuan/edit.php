<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <a href="<?= site_url('setup_persediaan/satuan') ?>" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali </a>
  </div>

  <div class="section-body">
    <!-- HALAMAN DINAMIS -->
    <div class="card">
      <div class="card-header">
        <h4>Edit Satuan</h4>
      </div>
      <div class="card-body">
        <div class="form-container">
          <form method="post" action="<?= site_url('setup_persediaan/satuan/' . $dtsatuan->id_satuan) ?>">
            <input type="hidden" name="_method" value="PUT">
            <?= csrf_field() ?>

            <div class="form-group">
              <label>Kode</label>
              <input type="text" class="form-control" name="kode_satuan" value="<?= esc($dtsatuan->kode_satuan) ?>" placeholder="Kode Satuan" required>
            </div>

            <div class="form-group">
              <label>Nama</label>
              <input type="text" class="form-control" name="nama_satuan" value="<?= esc($dtsatuan->nama_satuan) ?>" placeholder="Nama Satuan" required>
            </div>

            <div class="form-group">
              <button type="submit" class="btn btn-success">Update Data</button>
              <a href="<?= site_url('satuan') ?>" class="btn btn-danger">Batal</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<?= $this->endSection(); ?>