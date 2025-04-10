<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <a href="<?= site_url('setupbiaya') ?>" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali </a>
  </div>

  <div class="section-body">
  <!-- HALAMAN DINAMIS -->
  <div class="card">
    <div class="card-header">
        <h4>Edit Setup Buku Besar</h4>
    </div>
    <div class="card-body">
        <form method="post" action="<?= site_url('setupbiaya/' . $dtsetupbiaya->id_setupbiaya) ?>">
        <input type="hidden" name="_method" value="PUT">
        <?= csrf_field() ?>

        <div class="form-group">
          <label>Rekening</label>
          <select class="form-control" name="id_interface" required>
            <option value="" hidden>--Pilih Rekening--</option>
            <?php foreach ($dtinterface as $interface) : ?>
              <option value="<?= $interface->id_interface ?>" <?= $interface->id_interface == $dtsetupbiaya->id_interface ? 'selected' : '' ?>><?= $interface->rekening_biaya ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
            <label>Kode</label>
            <input type="text" class="form-control" name="kode_setupbiaya" value="<?= esc($dtsetupbiaya->kode_setupbiaya) ?>" required>
        </div>

        <div class="form-group">
            <label>Nama</label>
            <input type="text" class="form-control" name="nama_setupbiaya" value="<?= esc($dtsetupbiaya->nama_setupbiaya) ?>" required>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-success">Update Data</button>
            <a href="<?= site_url('setupbiaya') ?>" class="btn btn-danger">Batal</a>
        </div>
    </form>          
    </div>
  </div>

  </div>
</section>

<?= $this->endSection(); ?>
