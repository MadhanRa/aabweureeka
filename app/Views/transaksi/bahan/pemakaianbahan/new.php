<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <!-- <h1>APA INI</h1> -->
     <a href="<?= site_url('pemakaianbahan') ?>" class="btn btn-primary">
       <i class="fas fa-arrow-left"></i> Kembali
     </a>
  </div>

  <div class="section-body">
    <!-- HALAMAN DINAMIS -->
    <div class="card">
      <div class="card-header">
        <h4>Pemakaian Bahan</h4>
      </div>
      <div class="card-body">
        <form method="post" action="<?= site_url('pemakaianbahan') ?>">
          <?= csrf_field() ?>

          <div class="form-group">
            <label>Nota</label>
            <input type="text" class="form-control" name="nota_bahan" value="<?= old('nota_bahan') ?>" required>
          </div>

          <div class="form-group">
            <label>Lokasi</label>
            <select class="form-control" name="id_lokasi" required>
              <option value="" hidden>-- Pilih Lokasi --</option>
              <?php foreach ($dtlokasi as $key => $value) : ?>
                <option value="<?= esc($value->id_lokasi) ?>" <?= old('id_lokasi') == $value->id_lokasi ? 'selected' : '' ?>>
                  <?= esc($value->nama_lokasi) ?>
                </option>
              <?php endforeach; ?>    
            </select>    
          </div>

          <div class="form-group">
            <label>Kelompok Produksi</label>
            <select class="form-control" name="id_kelproduksi" required>
              <option value="" hidden>-- Pilih Kelompok Produksi --</option>
              <?php foreach ($dtkelompokproduksi as $key => $value) : ?>
                <option value="<?= esc($value->id_kelproduksi) ?>" <?= old('id_kelproduksi') == $value->id_kelproduksi ? 'selected' : '' ?>>
                  <?= esc($value->nama_kelproduksi) ?>
                </option>
              <?php endforeach; ?>    
            </select>    
          </div>

          <div class="form-group">
            <label>Biaya Produksi</label>
            <select class="form-control" name="id_setupbank" required>
              <option value="" hidden>-- Pilih Biaya Produksi --</option>
              <?php foreach ($dtsetupbank as $key => $value) : ?>
                <option value="<?= esc($value->id_setupbank) ?>" <?= old('id_setupbank') == $value->id_setupbank ? 'selected' : '' ?>>
                  <?= esc($value->nama_setupbank) ?>
                </option>
              <?php endforeach; ?>    
            </select>    
          </div>


          <div class="form-group">
            <label>Nama Stock</label>
            <input type="text" class="form-control" name="nama_stock" value="<?= old('nama_stock') ?>" required>
          </div>

          <div class="form-group">
            <label>Satuan</label>
            <select class="form-control" name="id_satuan" required>
              <option value="" hidden>-- Pilih Satuan --</option>
              <?php foreach ($dtsatuan as $key => $value) : ?>
                <option value="<?= esc($value->id_satuan) ?>" <?= old('id_satuan') == $value->id_satuan ? 'selected' : '' ?>>
                  <?= esc($value->kode_satuan) ?>
                </option>
              <?php endforeach; ?>    
            </select>    
          </div>

          <div class="form-group">
            <label>Qty 1</label>
            <input type="number" class="form-control" name="qty_1" value="<?= old('qty_1') ?>" required>
          </div>

          <div class="form-group">
            <label>Qty 2</label>
            <input type="number" class="form-control" name="qty_2" value="<?= old('qty_2') ?>" required>
          </div>

          <div class="form-group">
            <label>Tanggal</label>
            <input type="date" class="form-control" name="tanggal" value="<?= old('tanggal') ?>" required>
          </div>

          <div class="form-group">
            <button type="submit" class="btn btn-success">Simpan Data</button>
            <button type="reset" class="btn btn-danger">Reset</button>
          </div>
        </form>          
      </div>
    </div>
  </div>
</section>

<?= $this->endSection(); ?>
