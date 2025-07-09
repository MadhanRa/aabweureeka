<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>
<title>Akuntansi Eureeka &mdash; Laporan Biaya Pendapatan</title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <h1>Daftar Biaya dan Pendapatan</h1>
  </div>

  <div class="card">
    <div class="card-header">
      <div class="card-header-action">
        <a href="<?= base_url('laporan_biaya_daftar/printPDF?tglawal=' . $tglawal . '&tglakhir=' . $tglakhir . '&rekening=' . $rekening) ?>" class="btn btn-success" target="_blank">
          <i class="fas fa-print"></i> Cetak PDF
        </a>
      </div>
    </div>
    <div class="card-body">
      <form method="GET" action="<?= base_url('laporan_biaya_daftar') ?>">
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
          <div class="col-md-3">
            <button type="submit" class="btn btn-primary mt-4">Filter</button>
          </div>
        </div>
      </form>

      <div class="table-responsive mt-5">
        <table class="table table-striped table-md display nowrap compact eureeka-table" id="myTable">
          <thead>
            <tr class="eureeka-table-header">
              <th>No</th>
              <th>Biaya#</th>
              <th>Nama Biaya</th>
              <th>Awal</th>
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
                <td><?= $value->kode_setupbiaya ?></td>
                <td><?= $value->nama_setupbiaya ?></td>
                <td><?= "Rp " . number_format($value->saldo_awal, 0, ',', '.') ?></td>
                <td><?= "Rp " . number_format($value->debit, 0, ',', '.') ?></td>
                <td><?= "Rp " . number_format($value->kredit, 0, ',', '.') ?></td>
                <td><?= "Rp " . number_format($value->saldo, 0, ',', '.') ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="row mt-3">
        <div class="col">
          <label>Awal Debet</label>
          <input class="form-control" type="text" value="<?= "Rp " . number_format($awal_debet, 0, ',', '.') ?>" readonly>
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
          <input class="form-control" type="text" value="<?= "Rp " . number_format($akhir_debet, 0, ',', '.') ?>" readonly>
        </div>
      </div>
    </div>
  </div>
  </div>
  </div>
</section>

<?= $this->endSection(); ?>