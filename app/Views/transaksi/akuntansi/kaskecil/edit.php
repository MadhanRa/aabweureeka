<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <a href="<?= site_url('kaskecil') ?>" class="btn btn-primary">
      <i class="fas fa-arrow-left"></i> Kembali
    </a>
  </div>

  <div class="section-body">
    <div class="card">
      <div class="card-header">
        <h4>Edit Pengeluaran Kas Kecil</h4>
      </div>
      <div class="card-body">
        <form method="post" action="<?= site_url('kaskecil/' . $dtkaskecil->id_kaskecil) ?>">
          <?= csrf_field() ?>
          <input type="hidden" name="_method" value="PUT">

          <div class="form-group">
            <label>Tanggal</label>
            <input type="date" class="form-control" name="tanggal" value="<?= old('tanggal', $dtkaskecil->tanggal) ?>" required>
          </div>
          
          <div class="form-group">
            <label>Nota</label>
            <input type="text" class="form-control" name="nota" value="<?= old('nota', $dtkaskecil->nota) ?>" required>
          </div>

          <div class="form-group">
            <label>Rekening Kas dan Setara Kas</label>
            <select class="form-control" name="id_interface" required>
              <option value="" hidden>-- Pilih Rekening --</option>
              <?php foreach ($dtantarmuka as $key => $value) : ?>
                <option value="<?= esc($value->id_interface) ?>" <?= old('id_interface', $dtkaskecil->id_interface) == $value->id_interface ? 'selected' : '' ?>>
                  <?= esc($value->kas_interface) ?>
                </option>
              <?php endforeach; ?>    
            </select>    
          </div>

          <div class="form-group">
            <label>Kelompok Produksi</label>
            <select class="form-control" name="id_kelproduksi" required>
              <option value="" hidden>-- Pilih Kelompok Produksi --</option>
              <?php foreach ($dtkelompokproduksi as $key => $value) : ?>
                <option value="<?= esc($value->id_kelproduksi) ?>" <?= old('id_kelproduksi', $dtkaskecil->id_kelproduksi) == $value->id_kelproduksi ? 'selected' : '' ?>>
                  <?= esc($value->nama_kelproduksi) ?>
                </option>
              <?php endforeach; ?>    
            </select>    
          </div>

          <div class="form-group">
            <label>Rekening</label>
            <input type="text" class="form-control" name="rekening" value="<?= old('rekening', $dtkaskecil->rekening) ?>" required>
          </div>

          <div class="form-group">
            <label>B.Pembantu</label>
            <input type="text" class="form-control" name="b_pembantu" value="<?= old('b_pembantu', $dtkaskecil->b_pembantu) ?>" required>
          </div>

          <div class="form-group">
            <label>Nama Rekening</label>
            <input type="text" class="form-control" name="nama_rekening" value="<?= old('nama_rekening', $dtkaskecil->nama_rekening) ?>" required>
          </div>

          <div class="form-group">
            <label>Nama Buku Pembantu</label>
            <input type="text" class="form-control" name="nama_bpembantu" value="<?= old('nama_bpembantu', $dtkaskecil->nama_bpembantu) ?>" required>
          </div>

          <div class="form-group">
            <label>Rp</label>
            <input type="number" class="form-control" name="rp" value="<?= number_format(floatval(old('rp', $dtkaskecil->rp) ?: 0), 0, ',', '.') ?>">
          </div>

          <div class="form-group">
            <label>Keterangan</label>
            <input type="text" class="form-control" name="keterangan" value="<?= old('keterangan', $dtkaskecil->keterangan) ?>" required>
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

<script>

// Fungsi untuk format angka ke Rupiah
function formatRupiah(angka) {
    return new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR" }).format(angka);
}
</script>

<?= $this->endSection(); ?>
