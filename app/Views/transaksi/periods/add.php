<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <!-- <h1>APA INI</h1> -->
     <a href="<?= site_url('close-period') ?>" class="btn btn-primary">
       <i class="fas fa-arrow-left"></i> Kembali
     </a>
  </div>

  <div class="section-body">
    <!-- HALAMAN DINAMIS -->
    <div class="card">
      <div class="card-header">
        <h4>Add New Close Books</h4>
      </div>
      <div class="card-body">
        <form method="post" action="<?= site_url('close-period/close') ?>">
          <?= csrf_field() ?>


          <div class="form-group">
            <label>Periode Tutup Buku</label>
            <input type="month" class="form-control" name="month" required>
          </div>

          <!-- <div class="form-group">
            <label>Status Tutup Buku</label>
            <select name="is_closed" id="is_closed" class="form-control">
              <option value="">Pilih Status</option>
              <option value="1">Tutup</option>
              <option value="0">Buka</option>
            </select>
          </div> -->

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
