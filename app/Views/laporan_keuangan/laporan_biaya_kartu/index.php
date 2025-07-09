<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>
<title>Akuntansi Eureeka &mdash; Laporan Kartu Biaya Pendapatan</title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <h1>Kartu Biaya dan Pendapatan</h1>
  </div>

  <div class="card">
    <div class="card-header">
      <div class="card-header-action">
        <a href="<?= base_url('laporan_biaya_kartu/printPDF?tglawal=' . $tglawal . '&tglakhir=' . $tglakhir . '&rekening=' . $rekening . '&id_setupbiaya=' . $id_setupbiaya) ?>" class="btn btn-success" target="_blank">
          <i class="fas fa-print"></i> Cetak PDF
        </a>
      </div>
    </div>
    <div class="card-body">
      <form method="GET" action="<?= base_url('laporan_biaya_kartu') ?>">
        <div class="row">
          <div class="col-md-3">
            <label for="tglawal">Tanggal Awal</label>
            <input type="date" name="tglawal" class="form-control" value="<?= $tglawal ?>">
          </div>
          <div class="col-md-3">
            <label for="tglakhir">Tanggal Akhir</label>
            <input type="date" name="tglakhir" class="form-control" value="<?= $tglakhir ?>">
          </div>
          <div class="col-md-3">
            <label for="rekening">Rekening</label>
            <select id="rekening" name="rekening" class="form-control">
              <option value="">Semua Rekening</option>
              <?php foreach ($dtsetupbuku as $buku): ?>
                <option value="<?= $buku->id_setupbuku ?>" <?= $buku->id_setupbuku == $rekening ? 'selected' : '' ?>><?= $buku->nama_setupbuku ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="d-flex align-items-end mt-3">
          <div class="col-md-3">
            <label for="biaya">Kode Biaya</label>
            <input type="text" class="form-control" id="biaya" name="biaya" readonly>
            <input type="text" class="form-control" name="id_setupbiaya" hidden>
          </div>
          <div class="col-md-auto">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCariBiaya">cari</button>
          </div>
          <div class="col-md-auto">
            <button type="submit" class="btn btn-primary mt-4">Filter</button>
          </div>
        </div>
      </form>

      <div class="table-responsive mt-5">
        <table class="table table-striped table-md display nowrap compact eureeka-table" id="myTable">
          <thead>
            <tr class="eureeka-table-header">
              <th>No</th>
              <th>Tanggal</th>
              <th>Nota</th>
              <th>Keterangan</th>
              <th>Debet</th>
              <th>Kredit</th>
              <th>Saldo</th>
            </tr>
          </thead>
          <tbody>
            <!-- Iterasi Data -->
            <?php foreach ($dtdaftar_biaya as $key => $value) : ?>
              <tr>
                <td><?= $key + 1 ?></td>
                <td><?= $value->tanggal ?></td>
                <td><?= $value->nota ?></td>
                <td><?= $value->keterangan ?></td>
                <td><?= "Rp " . number_format($value->debit, 0, ',', '.') ?></td>
                <td><?= "Rp " . number_format($value->kredit, 0, ',', '.') ?></td>
                <td><?= "Rp " . number_format($value->saldo_berjalan, 0, ',', '.') ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="row mt-3">
        <div class="col">
          <label>Saldo Awal</label>
          <input class="form-control" type="text" value="<?= "Rp " . number_format($saldo_awal_total, 0, ',', '.') ?>" readonly>
        </div>
        <div class="col">
          <label>Debet</label>
          <input class="form-control" type="text" value="<?= "Rp " . number_format($debit_total, 0, ',', '.') ?>" readonly>
        </div>
        <div class="col">
          <label>Kredit</label>
          <input class="form-control" type="text" value="<?= "Rp " . number_format($kredit_total, 0, ',', '.') ?>" readonly>
        </div>
        <div class="col">
          <label>Saldo Akhir</label>
          <input class="form-control" type="text" value="<?= "Rp " . number_format($saldo_akhir_total, 0, ',', '.') ?>" readonly>
        </div>
      </div>
    </div>
  </div>
  </div>
  </div>
</section>

<!-- Tempat modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="modalCariBiaya" data-biaya-url="<?= site_url('laporan_biaya_kartu/cari-biaya/') ?>">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Cari Biaya & Pendapatan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="table-responsive table-wrapper-medium">
          <table class="table table-striped table-md" id="myTableSearch">
            <thead>
              <tr class="eureeka-table-header">
                <th>No</th>
                <th>Rekening</th>
                <th>Kode</th>
                <th>Biaya & Pendapatan</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="tabelCariBiaya">
              <?php foreach ($dtsetup_biaya as $key => $value) : ?>
                <tr>
                  <td><?= $key + 1 ?></td>
                  <td><?= $value->nama_setupbuku ?></td>
                  <td><?= $value->kode_setupbiaya ?></td>
                  <td><?= $value->nama_setupbiaya ?></td>
                  <td><button class="btn btn-primary" id="btn_cari_<?= $value->id_setupbiaya ?>" onclick="pilihBiaya(<?= $value->id_setupbiaya ?>)">Pilih</button></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer bg-whitesmoke br">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('pageScript') ?>
<script>
  function pilihBiaya(id) {
    const url = $('#modalCariBiaya').data('biaya-url');
    // Fungsi untuk mencari biaya dan autofill data
    $.ajax({
      url: url + id,
      method: 'GET',
      dataType: 'json',
      beforeSend: function() {
        // Show loading indicator
        $('#btn_cari_' + id).html('<i class="fa fa-spinner fa-spin"></i>');
      },
      success: function(data) {
        if (data.status) {
          const dataBiaya = data.data;
          $('input[name="id_setupbiaya"]').val(dataBiaya.id_setupbiaya);
          $('input[name="biaya"]').val(dataBiaya.kode_setupbiaya + ' - ' + dataBiaya.nama_setupbiaya);

          // kembalikan button ke semula
          $('#btn_cari_' + id).html('pilih');

          // Close modal
          $('#modalCariBiaya').modal('hide');
        }
      },
      error: function(xhr, status, error) {
        console.error('Error fetching Biaya:', error);
      }
    });
  }
</script>
<?= $this->endSection(); ?>