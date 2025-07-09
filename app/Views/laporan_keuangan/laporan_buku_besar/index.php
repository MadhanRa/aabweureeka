<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>
<title>Akuntansi Eureeka &mdash; Laporan Buku Besar</title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <h1>Buku Besar</h1>
  </div>

  <div class="card">
    <div class="card-header">
      <div class="card-header-action">
        <a href="<?= base_url('laporan_buku_besar/printPDF?tglawal=' . $tglawal . '&tglakhir=' . $tglakhir . '&id_setupbuku=' . $id_setupbuku) ?>" class="btn btn-success" target="_blank">
          <i class="fas fa-print"></i> Cetak PDF
        </a>
      </div>
    </div>
    <div class="card-body">
      <form method="GET" action="<?= base_url('laporan_buku_besar') ?>">
        <div class="d-flex align-items-end">
          <div class="col-md-3">
            <label for="tglawal">Tanggal Awal</label>
            <input type="date" name="tglawal" class="form-control" value="<?= $tglawal ?>">
          </div>
          <div class="col-md-3">
            <label for="tglakhir">Tanggal Akhir</label>
            <input type="date" name="tglakhir" class="form-control" value="<?= $tglakhir ?>">
          </div>
          <div class="col-md-3">
            <label for="rekening">Kode</label>
            <input type="text" class="form-control" id="rekening" name="rekening" value="<?= $rekening ?>" readonly>
            <input type="text" class="form-control" name="id_setupbuku" value="<?= $id_setupbuku ?>" hidden>
          </div>
          <div class="col-md-auto">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCariBuku">cari</button>
          </div>
        </div>
        <div class="d-flex align-items-end mt-3">
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
            <?php foreach ($dt_transaksi_buku as $key => $value) : ?>
              <tr>
                <td><?= $key + 1 ?></td>
                <td><?= $value->tanggal ?></td>
                <td><?= $value->nota ?></td>
                <td><?= $value->deskripsi ?></td>
                <td><?= "Rp " . number_format($value->debit, 0, ',', '.') ?></td>
                <td><?= "Rp " . number_format($value->kredit, 0, ',', '.') ?></td>
                <td><?= "Rp " . number_format($value->saldo_setelah, 0, ',', '.') ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="row mt-3">
        <div class="col">
          <label>Awal Debet</label>
          <input class="form-control" type="text" value="<?= "Rp " . number_format($debit_awal_total, 0, ',', '.') ?>" readonly>
        </div>
        <div class="col">
          <label>Awal Kredit</label>
          <input class="form-control" type="text" value="<?= "Rp " . number_format($kredit_awal_total, 0, ',', '.') ?>" readonly>
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
          <label>Akhir Debet</label>
          <input class="form-control" type="text" value="<?= "Rp " . number_format($debit_akhir_total, 0, ',', '.') ?>" readonly>
        </div>
        <div class="col">
          <label>Akhir Kredit</label>
          <input class="form-control" type="text" value="<?= "Rp " . number_format($kredit_akhir_total, 0, ',', '.') ?>" readonly>
        </div>
      </div>
    </div>
  </div>
  </div>
  </div>
</section>

<!-- Tempat modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="modalCariBuku" data-buku-url="<?= site_url('laporan_buku_besar/cari-buku/') ?>">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Cari Buku Besar</h5>
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
                <th>Kode</th>
                <th>Rekening</th>
                <th>Neraca</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="tabelCariBuku">
              <?php foreach ($dtsetup_buku as $key => $value) : ?>
                <tr>
                  <td><?= $key + 1 ?></td>
                  <td><?= $value->kode_setupbuku ?></td>
                  <td><?= $value->nama_setupbuku ?></td>
                  <td><?= $value->nama_posneraca ?></td>
                  <td><button class="btn btn-primary" id="btn_cari_<?= $value->id_setupbuku ?>" onclick="pilihBuku(<?= $value->id_setupbuku ?>)">Pilih</button></td>
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
  function pilihBuku(id) {
    const url = $('#modalCariBuku').data('buku-url');
    // Fungsi untuk mencari buku besar dan autofill data
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
          const dataBuku = data.data;
          $('input[name="id_setupbuku"]').val(dataBuku.id_setupbuku);
          $('input[name="rekening"]').val(dataBuku.kode_setupbuku + ' - ' + dataBuku.nama_setupbuku);

          // kembalikan button ke semula
          $('#btn_cari_' + id).html('pilih');

          // Close modal
          $('#modalCariBuku').modal('hide');
        }
      },
      error: function(xhr, status, error) {
        console.error('Error fetching Buku:', error);
      }
    });
  }
</script>
<?= $this->endSection(); ?>