<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <a href="<?= site_url('stockopname') ?>" class="btn btn-primary">
      <i class="fas fa-arrow-left"></i> Kembali
    </a>
  </div>

  <div class="section-body">
    <div class="card">
      <div class="card-header">
        <h4>Edit Stock Opname</h4>
      </div>
      <div class="card-body">
        <form method="post" action="<?= site_url('stockopname/' . $dtstockopname->id_stockopname) ?>">
          <?= csrf_field() ?>
          <input type="hidden" name="_method" value="PUT">

          <div class="form-group">
            <label>Tanggal</label>
            <input type="date" class="form-control" name="tanggal" value="<?= old('tanggal', $dtstockopname->tanggal) ?>" required>
          </div>
          
          <div class="form-group">
            <label>Nota</label>
            <input type="text" class="form-control" name="nota" value="<?= old('nota', $dtstockopname->nota) ?>" required>
          </div>
          
          <div class="form-group">
            <label>Lokasi Asal</label>
            <select class="form-control" name="id_lokasi" required>
              <option value="" hidden>-- Pilih Lokasi --</option>
              <?php foreach ($dtlokasi as $key => $value) : ?>
                <option value="<?= esc($value->id_lokasi) ?>" <?= old('id_lokasi', $dtstockopname->id_lokasi) == $value->id_lokasi ? 'selected' : '' ?>>
                  <?= esc($value->nama_lokasi) ?>
                </option>
              <?php endforeach; ?>    
            </select>    
          </div>

          <div class="form-group">
            <label>User</label>
            <select class="form-control" name="id_setupuser" required>
              <option value="" hidden>-- Pilih User --</option>
              <?php foreach ($dtsetupuser as $key => $value) : ?>
                <option value="<?= esc($value->id_setupuser) ?>" <?= old('id_setupuser', $dtstockopname->id_setupuser) == $value->id_setupuser ? 'selected' : '' ?>>
                  <?= esc($value->nama_setupuser) ?>
                </option>
              <?php endforeach; ?>    
            </select>    
          </div>

          <div class="form-group">
            <label>Nama Stock</label>
            <input type="text" class="form-control" name="nama_stock" value="<?= old('nama_stock', $dtstockopname->nama_stock) ?>" required>
          </div>

          <div class="form-group">
            <label>Satuan</label>
            <select class="form-control" name="id_satuan" required>
              <option value="" hidden>-- Pilih Satuan --</option>
              <?php foreach ($dtsatuan as $key => $value) : ?>
                <option value="<?= esc($value->id_satuan) ?>" <?= old('id_satuan', $dtstockopname->id_satuan) == $value->id_satuan ? 'selected' : '' ?>>
                  <?= esc($value->kode_satuan) ?>
                </option>
              <?php endforeach; ?>    
            </select>    
          </div>

          <div class="form-group">
            <label>QTY 1</label>
            <input type="number" class="form-control" name="qty_1" value="<?= old('qty_1', $dtstockopname->qty_1) ?>" required>
          </div>

          <div class="form-group">
            <label>QTY 2</label>
            <input type="number" class="form-control" name="qty_2" value="<?= old('qty_2', $dtstockopname->qty_2) ?>" required>
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
