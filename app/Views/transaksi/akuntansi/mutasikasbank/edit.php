<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <a href="<?= site_url('mutasikasbank') ?>" class="btn btn-primary">
      <i class="fas fa-arrow-left"></i> Kembali
    </a>
  </div>

  <div class="section-body">
    <div class="card">
      <div class="card-header">
        <h4>Edit Mutasi Kas dan Bank</h4>
      </div>
      <div class="card-body">
        <form method="post" action="<?= site_url('mutasikasbank/' . $dtmutasikasbank->id_mutasikasbank) ?>">
          <?= csrf_field() ?>
          <!-- Hidden field for method spoofing -->
          <input type="hidden" name="_method" value="PUT">

          <div class="form-group">
            <label>Tanggal</label>
            <input type="date" class="form-control" name="tanggal" value="<?= esc($dtmutasikasbank->tanggal) ?>" required>
          </div>

          <div class="form-group">
            <label>Nota</label>
            <input type="text" class="form-control" name="nota" value="<?= esc($dtmutasikasbank->nota) ?>" required>
          </div>

          <div class="form-group">
            <label>Rekening Kas dan Setara Kas</label>
            <select class="form-control" name="id_interface" required>
              <option value="" hidden>-- Pilih Rekening --</option>
              <?php foreach ($dtinterface as $key => $value) : ?>
                <option value="<?= esc($value->id_interface) ?>" <?= $dtmutasikasbank->id_interface == $value->id_interface ? 'selected' : '' ?>>
                  <?= esc($value->kas_interface) ?>
                </option>
              <?php endforeach; ?>    
            </select>    
          </div>

          <div class="form-group">
            <label>Rekening</label>
            <input type="text" class="form-control" name="rekening" value="<?= esc($dtmutasikasbank->rekening) ?>" required>
          </div>

          <div class="form-group">
            <label>B.Pembantu</label>
            <input type="text" class="form-control" name="b_pembantu" value="<?= esc($dtmutasikasbank->b_pembantu) ?>" required>
          </div>

          <div class="form-group">
            <label>Nama Rekening</label>
            <input type="text" class="form-control" name="nama_rekening" value="<?= esc($dtmutasikasbank->nama_rekening) ?>" required>
          </div>

          <div class="form-group">
            <label>Nama Buku Pembantu</label>
            <input type="text" class="form-control" name="nama_bpembantu" value="<?= esc($dtmutasikasbank->nama_bpembantu) ?>" required>
          </div>

          <div class="form-group">
            <label>No.Ref</label>
            <input type="number" class="form-control" name="no_ref" value="<?= esc($dtmutasikasbank->no_ref) ?>" required>
          </div>

          <div class="form-group">
            <label>Debet</label>
            <input type="text" class="form-control" name="debet" value="<?= number_format(floatval(old('debet', $dtmutasikasbank->debet) ?: 0), 0, ',', '.') ?>">
          </div>

          <div class="form-group">
            <label>Kredit</label>
            <input type="text" class="form-control" name="kredit" value="<?= number_format(floatval(old('kredit', $dtmutasikasbank->kredit) ?: 0), 0, ',', '.') ?>">
          </div>

          <div class="form-group">
            <label>Mutasi</label>
            <input type="text" class="form-control" name="mutasi" value="<?= esc($dtmutasikasbank->mutasi) ?>" required>
          </div>

          <div class="form-group">
            <label>Tgl.Nota</label>
            <input type="date" class="form-control" name="tgl_nota" value="<?= esc($dtmutasikasbank->tgl_nota) ?>" required>
          </div>

          <div class="form-group">
            <label>Keterangan</label>
            <input type="text" class="form-control" name="keterangan" value="<?= esc($dtmutasikasbank->keterangan) ?>" required>
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
