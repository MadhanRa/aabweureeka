<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>
<title>Akuntansi Eureeka &mdash; Laporan Kas Keluar</title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <h1>Kas Keluar</h1>
  </div>

  <div class="card">
    <div class="card-header">
      <div class="card-header-action">
        <a href="<?= base_url('laporan_kas_keluar/printPDF?tglawal=' . $tglawal . '&tglakhir=' . $tglakhir) ?>" class="btn btn-success" target="_blank">
          <i class="fas fa-print"></i> Cetak PDF
        </a>
      </div>
    </div>
    <div class="card-body">
      <form method="GET" action="<?= base_url('laporan_kas_keluar') ?>">
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
              <th>Tanggal</th>
              <th>Nota</th>
              <th>Rek. Kas#</th>
              <th>Rekening#</th>
              <th>B.Pembantu#</th>
              <th>Nama Rekening</th>
              <th>Nama B.Pembantu</th>
              <th>Debet</th>
              <th>Kredit</th>
              <th>Mutasi</th>
            </tr>
          </thead>
          <tbody>
            <!-- Iterasi Data -->
            <?php foreach ($dt_mutasi as $key => $value) : ?>
              <tr>
                <td><?= $key + 1 ?></td>
                <td><?= $value->tanggal ?></td>
                <td><?= $value->nota ?></td>
                <td><?= $value->kode_setupbuku ?></td>
                <td><?= $value->rekening ?></td>
                <td><?= $value->b_pembantu ?></td>
                <td><?= $value->nama_rekening ?></td>
                <td><?= $value->nama_bpembantu ?></td>
                <td><?= "Rp " . number_format($value->debet, 0, ',', '.') ?></td>
                <td><?= "Rp " . number_format($value->kredit, 0, ',', '.') ?></td>
                <td><?= "Rp " . number_format($value->mutasi, 0, ',', '.') ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="row mt-3">
        <div class="col">
          <label>Total Debet</label>
          <input class="form-control" type="text" value="<?= "Rp " . number_format($debit_total, 0, ',', '.') ?>" readonly>
        </div>
        <div class="col">
          <label>Total Kredit</label>
          <input class="form-control" type="text" value="<?= "Rp " . number_format($kredit_total, 0, ',', '.') ?>" readonly>
        </div>
        <div class="col">
          <label>Total Mutasi</label>
          <input class="form-control" type="text" value="<?= "Rp " . number_format($mutasi_total, 0, ',', '.') ?>" readonly>
        </div>
      </div>
    </div>
  </div>
  </div>
  </div>
</section>

<?= $this->endSection(); ?>