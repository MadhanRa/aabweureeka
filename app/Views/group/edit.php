<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <a href="<?= site_url('group') ?>" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali </a>
  </div>

  <div class="section-body">
  <!-- HALAMAN DINAMIS -->
  <div class="card">
    <div class="card-header">
      <h4>Edit Group</h4>
    </div>
    <div class="card-body">
      <!-- Form untuk edit data -->
      <form method="post" action="<?= site_url('group/' . $dtgroup->id_group) ?>"> <!-- Arahkan form ke update dengan ID group -->
        <?= csrf_field() ?>
        <input type="hidden" name="_method" value="PUT">

        <div class="form-group">
          <label>Kode</label>
          <input type="text" class="form-control" name="kode_group" placeholder="Kode Group" value="<?= old('kode_group', $dtgroup->kode_group) ?>" required>
        </div>

        <div class="form-group">
          <label>Nama</label>
          <input type="text" class="form-control" name="nama_group" placeholder="Nama Group" value="<?= old('nama_group', $dtgroup->nama_group) ?>" required>
        </div>

        <div class="form-group">
          <label>Rekening</label>
          <select class="form-control" name="id_interface" required>
            <option value="" hidden>--Pilih Rekening--</option>
            <?php foreach ($dtinterface as $interface) : ?>
              <option value="<?= $interface->id_interface ?>" <?= $interface->id_interface == $dtgroup->id_interface ? 'selected' : '' ?>><?= $interface->rekening_biaya ?></option>
            <?php endforeach; ?>
          </select>
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

<?= $this->endSection(); ?>
