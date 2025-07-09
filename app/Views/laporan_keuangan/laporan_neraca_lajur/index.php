<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>
<title>Akuntansi Eureeka &mdash; Laporan Neraca Lajur</title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <h1>Neraca Lajur</h1>
  </div>

  <div class="card">
    <div class="card-header">
      <div class="card-header-action">
        <a href="<?= base_url('laporan_neraca_lajur/printPDF?tglawal=' . $tglawal . '&tglakhir=' . $tglakhir) ?>" class="btn btn-success" target="_blank">
          <i class="fas fa-print"></i> Cetak PDF
        </a>
      </div>
    </div>
    <div class="card-body">
      <form method="GET" action="<?= base_url('laporan_neraca_lajur') ?>">
        <div class="d-flex align-items-end">
          <div class="col-md-3">
            <label for="tglawal">Tanggal Awal</label>
            <input type="date" name="tglawal" class="form-control" value="<?= $tglawal ?>">
          </div>
          <div class="col-md-3">
            <label for="tglakhir">Tanggal Akhir</label>
            <input type="date" name="tglakhir" class="form-control" value="<?= $tglakhir ?>">
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
              <th>Rekening#</th>
              <th>Nama Rekening</th>
              <th>Awal Debet</th>
              <th>Awal Kredit</th>
              <th>Debet</th>
              <th>Kredit</th>
              <th>Saldo Debet</th>
              <th>Saldo Kredit</th>
            </tr>
          </thead>
          <tbody>
            <!-- Iterasi Data -->
            <?php foreach ($dt_laporan_neraca as $key => $value) : ?>
              <tr>
                <td><?= $key + 1 ?></td>
                <td><?= $value->kode_setupbuku ?></td>
                <td><?= $value->nama_setupbuku ?></td>
                <td><?= "Rp " . number_format($value->awal_debit, 0, ',', '.') ?></td>
                <td><?= "Rp " . number_format($value->awal_kredit, 0, ',', '.') ?></td>
                <td><?= "Rp " . number_format($value->debit, 0, ',', '.') ?></td>
                <td><?= "Rp " . number_format($value->kredit, 0, ',', '.') ?></td>
                <td><?= "Rp " . number_format($value->saldo_debit, 0, ',', '.') ?></td>
                <td><?= "Rp " . number_format($value->saldo_kredit, 0, ',', '.') ?></td>
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

<?= $this->endSection(); ?>