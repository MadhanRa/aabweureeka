<?= $this->extend("layout/backend") ?>;

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <a href="<?=site_url('setupbiaya')?>" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali </a>
  </div>

  <div class="section-body">
    <div class="card">
      <div class="card-header">
        <h4>Setup Biaya</h4>
      </div>
      <div class="card-body">
        <form method="post" action="<?=site_url('setupbiaya') ?>">
          <?= csrf_field() ?>
          <div class="form-group">
            <label>Rekening Bank</label>
            <select class="form-control" name="id_interface" required>
              <option value="" hidden>--Pilih Rekening--</option>
              <?php foreach ($dtinterface as $interface) : ?>
                <option value="<?= $interface->id_interface ?>"><?= $interface->rekening_biaya ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label>Kode</label>
            <input type="text" class="form-control" name="kode_setupbiaya" required>
          </div>
          <div class="form-group">
            <label>Nama</label>
            <input type="text" class="form-control" name="nama_setupbiaya" required>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-success">Simpan Data</button>
            <button type="reset" class="btn btn-danger">Reset</button>
          </div>
        </form>
        
        <div class="form-group">
          <button type="button" class="btn btn-primary" id="btn-lookup">Lookup</button>
        </div>
        
        <div id="lookup-table" style="display: none;">
          <h5>Daftar Kode dan Nama Biaya</h5>
          <div class="table-responsive">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Kode Biaya</th>
                  <th>Nama Biaya</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($dtsetupbiaya)) : ?>
                  <?php $no = 1; foreach ($dtsetupbiaya as $biaya) : ?>
                    <tr>
                      <td><?= $no++ ?></td>
                      <td><?= $biaya->kode_setupbiaya ?></td>
                      <td><?= $biaya->nama_setupbiaya ?></td>
                    </tr>
                  <?php endforeach; ?>
                <?php else : ?>
                  <tr>
                    <td colspan="3" class="text-center">Data tidak ditemukan</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
    document.getElementById('btn-lookup').addEventListener('click', function() {
        var lookupTable = document.getElementById('lookup-table');
        if (lookupTable.style.display === "none") {
            lookupTable.style.display = "block"; // Tampilkan tabel
        } else {
            lookupTable.style.display = "none"; // Sembunyikan tabel
        }
    });
</script>

<?= $this->endSection(); ?>
