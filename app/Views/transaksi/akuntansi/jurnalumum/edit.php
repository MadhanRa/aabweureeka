<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
     <a href="<?= site_url('jurnalumum') ?>" class="btn btn-primary">
       <i class="fas fa-arrow-left"></i> Kembali
     </a>
  </div>

  <div class="section-body">
    <div class="card">
      <div class="card-header">
        <h4>Edit Transaksi Jurnal Umum</h4>
      </div>
      <div class="card-body">
      <form method="post" action="<?= site_url('jurnalumum/' . $dtjurnalumum->id_jurnalumum) ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="_method" value="PUT">

          <div class="form-group">
            <label>Tanggal</label>
            <input type="date" class="form-control" name="tanggal" value="<?= old('tanggal', $dtjurnalumum->tanggal) ?>" required>
          </div>
          
          <div class="form-group">
            <label>Nota</label>
            <input type="text" class="form-control" name="nota" value="<?= old('nota', $dtjurnalumum->nota) ?>" required>
          </div>

          <div class="form-group">
            <label>Rekening</label>
            <input type="text" class="form-control" name="rekening" value="<?= old('rekening', $dtjurnalumum->rekening) ?>" required>
          </div>

          <div class="form-group">
            <label>B.Pembantu</label>
            <input type="text" class="form-control" name="b_pembantu" value="<?= old('b_pembantu', $dtjurnalumum->b_pembantu) ?>" required>
          </div>

          <div class="form-group">
            <label>Nama Rekening</label>
            <input type="text" class="form-control" name="nama_rekening" value="<?= old('nama_rekening', $dtjurnalumum->nama_rekening) ?>" required>
          </div>

          <div class="form-group">
            <label>Nama Buku Pembantu</label>
            <input type="text" class="form-control" name="nama_bpembantu" value="<?= old('nama_bpembantu', $dtjurnalumum->nama_bpembantu) ?>" required>
          </div>

          <div class="form-group">
            <label>No.Ref</label>
            <input type="number" class="form-control" name="no_ref" value="<?= old('no_ref', $dtjurnalumum->no_ref) ?>" required>
          </div>

          <div class="form-group">
            <label>Debet</label>
            <input type="text" class="form-control" name="debet" value="<?= number_format(floatval(old('debet', $dtjurnalumum->debet) ?: 0), 0, ',', '.') ?>">
          </div>

          <div class="form-group">
            <label>Kredit</label>
            <input type="text" class="form-control" name="kredit" value="<?= number_format(floatval(old('kredit', $dtjurnalumum->kredit) ?: 0), 0, ',', '.') ?>">
          </div>

          <div class="form-group">
            <label>Tgl.Nota</label>
            <input type="date" class="form-control" name="tgl_nota" value="<?= old('tgl_nota', $dtjurnalumum->tgl_nota) ?>" required>
          </div>

          <div class="form-group">
            <label>Keterangan</label>
            <input type="text" class="form-control" name="keterangan" value="<?= old('keterangan', $dtjurnalumum->keterangan) ?>" required>
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
