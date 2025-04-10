<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
     <a href="<?= site_url('setuppiutang') ?>" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali </a>
  </div>

  <div class="section-body">
  <!-- HALAMAN DINAMIS -->
  <div class="card">
    <div class="card-header">
        <h4>Edit Setup Piutang Lain-lain</h4>
    </div>
    <div class="card-body">
        <form method="post" action="<?= site_url('setuppiutang/' . $dtsetuppiutang->id_setuppiutang) ?>">
        <input type="hidden" name="_method" value="PUT">
        <?= csrf_field() ?>

        <div class="form-group">
            <label>Kode</label>
            <input type="text" class="form-control" name="kode_piutang" value="<?= esc($dtsetuppiutang->kode_piutang) ?>" placeholder="Kode" required>
        </div>
        <div class="form-group">
            <label>Nama</label>
            <input type="text" class="form-control" name="nama_piutang" value="<?= esc($dtsetuppiutang->nama_piutang) ?>" placeholder="Nama" required>
        </div>
        <div class="form-group">
            <label>Lokasi</label>
            <select type="text" class="form-control" name="lokasi_piutang" required>
                <option value="Lokasi 1" <?= $dtsetuppiutang->lokasi_piutang == 'Lokasi 1' ? 'selected' : '' ?>>Lokasi 1</option>
                <option value="Lokasi 2" <?= $dtsetuppiutang->lokasi_piutang == 'Lokasi 2' ? 'selected' : '' ?>>Lokasi 2</option>
                <option value="Lokasi 3" <?= $dtsetuppiutang->lokasi_piutang == 'Lokasi 3' ? 'selected' : '' ?>>Lokasi 3</option>
            </select>
        </div>
        <div class="form-group">
            <label>Tanggal</label>
            <input type="date" class="form-control" name="tanggal_piutang" value="<?= esc($dtsetuppiutang->tanggal_piutang) ?>" placeholder="Tanggal" required>
        </div>
        <div class="form-group">
            <label>Saldo</label>
            <input type="text" class="form-control" name="saldo_piutang" value="<?= esc($dtsetuppiutang->saldo_piutang) ?>" placeholder="Saldo" required>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-success">Update Data</button>
            <a href="<?= site_url('setuppiutang') ?>" class="btn btn-danger">Batal</a>
        </div>
    </form>          
    </div>
  </div>

  </div>
</section>

<?= $this->endSection(); ?>
