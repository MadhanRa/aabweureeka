<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <a href="<?= site_url('bahansablon') ?>" class="btn btn-primary">
      <i class="fas fa-arrow-left"></i> Kembali
    </a>
  </div>

  <div class="section-body">
    <div class="card">
      <div class="card-header">
        <h4>Edit Bahan di Sablon</h4>
      </div>
      <div class="card-body">
        <form method="post" action="<?= site_url('bahansablon/' . $dtbahansablon->id_bahan) ?>">
        <input type="hidden" name="_method" value="PUT">
          <?= csrf_field() ?>
          <!-- Hidden field to spoof the PUT request method -->
          <input type="hidden" name="_method" value="PUT">

          <div class="form-group">
            <label>Lokasi Asal</label>
            <select class="form-control" name="id_lokasi_asal" required>
              <option value="" hidden>-- Pilih Lokasi Asal --</option>
              <?php foreach ($dtlokasi as $key => $value) : ?>
                <option value="<?= esc($value->id_lokasi) ?>" <?= $dtbahansablon->id_lokasi_asal == $value->id_lokasi ? 'selected' : '' ?>>
                  <?= esc($value->nama_lokasi) ?>
                </option>
              <?php endforeach; ?>    
            </select>    
          </div>

          <div class="form-group">
            <label>Lokasi Tujuan</label>
            <select class="form-control" name="id_lokasi_tujuan" required>
              <option value="" hidden>-- Pilih Lokasi Tujuan --</option>
              <?php foreach ($dtlokasi as $key => $value) : ?>
                <option value="<?= esc($value->id_lokasi) ?>" <?= $dtbahansablon->id_lokasi_tujuan == $value->id_lokasi ? 'selected' : '' ?>>
                  <?= esc($value->nama_lokasi) ?>
                </option>
              <?php endforeach; ?>    
            </select>    
          </div>

          <div class="form-group">
            <label>Nota</label>
            <input type="text" class="form-control" name="nota_pindah" value="<?= esc($dtbahansablon->nota_pindah) ?>" required>
          </div>

          <div class="form-group">
            <label>Nama Stock</label>
            <input type="text" class="form-control" name="nama_stock" value="<?= esc($dtbahansablon->nama_stock) ?>" required>
          </div>

          <div class="form-group">
            <label>Satuan</label>
            <select class="form-control" name="id_satuan" required>
              <option value="" hidden>-- Pilih Satuan --</option>
              <?php foreach ($dtsatuan as $key => $value) : ?>
                <option value="<?= esc($value->id_satuan) ?>" <?= $dtbahansablon->id_satuan == $value->id_satuan ? 'selected' : '' ?>>
                  <?= esc($value->kode_satuan) ?>
                </option>
              <?php endforeach; ?>    
            </select>    
          </div>

          <div class="form-group">
            <label>Qty 1</label>
            <input type="number" class="form-control" name="qty_1" value="<?= esc($dtbahansablon->qty_1) ?>" required>
          </div>

          <div class="form-group">
            <label>Qty 2</label>
            <input type="number" class="form-control" name="qty_2" value="<?= esc($dtbahansablon->qty_2) ?>" required>
          </div>

          <div class="form-group">
            <label>Tanggal</label>
            <input type="date" class="form-control" name="tanggal" value="<?= esc($dtbahansablon->tanggal) ?>" required>
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
