<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
     <a href="<?= site_url('setupsupplier') ?>" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali </a>
  </div>

  <div class="section-body">
  <!-- HALAMAN DINAMIS -->
  <div class="card">
    <div class="card-header">
        <h4>Edit Setup Salesman</h4>
    </div>
    <div class="card-body">
        <form method="post" action="<?= site_url('setupsupplier/' . $dtsetupsupplier->id_setupsupplier) ?>">
        <input type="hidden" name="_method" value="PUT">
        <?= csrf_field() ?>

        <div class="form-group">
            <label>Kode</label>
            <input type="text" class="form-control" name="kode" value="<?= esc($dtsetupsupplier->kode) ?>" placeholder="Kode" required>
        </div>
        <div class="form-group">
            <label>Nama</label>
            <input type="text" class="form-control" name="nama" value="<?= esc($dtsetupsupplier->nama) ?>" placeholder="Nama" required>
        </div>
        <div class="form-group">
            <label>Alamat</label>
            <input type="text" class="form-control" name="alamat" value="<?= esc($dtsetupsupplier->alamat) ?>" placeholder="Alamat" required>
        </div>
        <div class="form-group">
            <label>Telepon</label>
            <input type="text" class="form-control" name="telepon" value="<?= esc($dtsetupsupplier->telepon) ?>" placeholder="Telepon" required>
        </div>
        <div class="form-group">
            <label>Contact Person</label>
            <input type="text" class="form-control" name="contact_person" value="<?= esc($dtsetupsupplier->contact_person) ?>" placeholder="Contact Person" required>
        </div>
        <div class="form-group">
            <label>NPWP</label>
            <input type="text" class="form-control" name="npwp" value="<?= esc($dtsetupsupplier->npwp) ?>" placeholder="NPWP" required>
        </div>
        <div class="form-group">
            <label>Tanggal</label>
            <input type="date" class="form-control" name="tanggal" value="<?= esc($dtsetupsupplier->tanggal) ?>" placeholder="Tanggal" required>
        </div>
        <div class="form-group">
            <label>Saldo</label>
            <input type="text" class="form-control" name="saldo" value="<?= esc($dtsetupsupplier->saldo) ?>" placeholder="Saldo" required>
        </div>
        <div class="form-group">
            <label>Tipe</label>
            <select class="form-control" name="tipe" required>
                <option value="Exclude" <?= $dtsetupsupplier->tipe == 'Exclude' ? 'selected' : '' ?>>Exclude</option>
                <option value="Include" <?= $dtsetupsupplier->tipe == 'Include' ? 'selected' : '' ?>>Include</option>
                <option value="Non PPN" <?= $dtsetupsupplier->tipe == 'Non PPN' ? 'selected' : '' ?>>Non PPN</option>
            </select>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-success">Update Data</button>
            <a href="<?= site_url('setupsupplier') ?>" class="btn btn-danger">Batal</a>
        </div>
    </form>          
    </div>
  </div>

  </div>
</section>

<?= $this->endSection(); ?>
