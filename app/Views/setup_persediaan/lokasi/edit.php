<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <a href="<?= site_url('setup_persediaan/lokasi') ?>" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali </a>
  </div>

  <div class="section-body">
    <!-- HALAMAN DINAMIS -->
    <div class="card">
      <div class="card-header">
        <h4>Edit Lokasi</h4>
      </div>
      <div class="card-body">
        <!-- Form to Edit Data -->
        <div class="form-container">
          <form method="post" action="<?= site_url('setup_persediaan/lokasi/' . $dtlokasi->id_lokasi) ?>">
            <input type="hidden" name="_method" value="PUT">
            <?= csrf_field() ?>

            <div class="form-group">
              <label>Kode</label>
              <input type="text" class="form-control" name="kode_lokasi" value="<?= old('kode_lokasi', $dtlokasi->kode_lokasi) ?>" placeholder="Kode Lokasi" required>
            </div>

            <div class="form-group">
              <label>Nama</label>
              <input type="text" class="form-control" name="nama_lokasi" value="<?= old('nama_lokasi', $dtlokasi->nama_lokasi) ?>" placeholder="Nama Lokasi" required>
            </div>

            <div class="form-group">
              <button type="submit" class="btn btn-success">Update Data</button>
              <button type="reset" class="btn btn-danger">Reset</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<?= $this->endSection(); ?>