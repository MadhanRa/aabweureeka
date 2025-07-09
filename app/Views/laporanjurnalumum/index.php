<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>
<title>Akuntansi Eureeka &mdash; Laporan Jurnal Umum</title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <h1>Laporan Jurnal Umum</h1>
  </div>

  <!-- Tombol Print All -->
  <div class="section-body">
    <div class="card-body">
      <div class="row">
        <div class="col">
          <a href="<?= base_url('LaporanJurnalUmum/printPDF?tglawal=' . $tglawal . '&tglakhir=' . $tglakhir) ?>" class="btn btn-success" target="_blank">
            <i class="fas fa-print"></i> Cetak PDF
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Tabel Data -->
  <div class="section-body">
    <div class="card-body">
      <form action="<?= site_url('laporanjurnalumum') ?>" method="POST">
        <?= csrf_field() ?>
        <div class="row g-3">
          <div class="col">
            <input type="date" class="form-control" name="tglawal" value="<?= $tglawal ?>">
          </div>
          <div class="col">
            <input type="date" class="form-control" name="tglakhir" value="<?= $tglakhir ?>">
          </div>
          <div class="col">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Tampilkan Data</button>
          </div>
        </div>
      </form>

      <div class="card-body">

        <div class="table-responsive">
          <table class="table table-striped table-md display nowrap compact eureeka-table" id="myTable">
            <thead>
              <tr class="eureeka-table-header">
                <th>No</th>
                <th>Tanggal</th>
                <th>Nota</th>
                <th>Rekening</th>
                <th>B.Pembantu</th>
                <th>Nama Rekening</th>
                <th>Nama Buku Pembantu</th>
                <th>No.Ref</th>
                <th>Debet</th>
                <th>Kredit</th>
                <th>Tgl.Nota</th>
                <th>Keterangan</th>
                <!-- <th>Action</th> -->
              </tr>
            </thead>
            <tbody>
              <!-- Iterasi Data -->
              <?php foreach ($laporan as $key => $value) : ?>
                <tr>
                  <td><?= $key + 1 ?></td>
                  <td><?= $value->tanggal ?></td>
                  <td><?= $value->nota ?></td>
                  <td><?= $value->rekening ?></td>
                  <td><?= $value->b_pembantu ?></td>
                  <td><?= $value->nama_rekening ?></td>
                  <td><?= $value->nama_bpembantu ?></td>
                  <td><?= $value->no_ref ?></td>
                  <td><?= "Rp " . number_format($value->debet, 0, ',', '.') ?></td>
                  <td><?= "Rp " . number_format($value->kredit, 0, ',', '.') ?></td>
                  <td><?= $value->tgl_nota ?></td>
                  <td><?= $value->keterangan ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>

          </table>
        </div>
        <div class="row mt-3">
          <div class="col">
            <label>Total Debet</label>
            <input class="form-control" type="text" value="<?= "Rp " . number_format($total_debet, 0, ',', '.') ?>" readonly>
          </div>
          <div class="col">
            <label>Total Kredit</label>
            <input class="form-control" type="text" value="<?= "Rp " . number_format($total_kredit, 0, ',', '.') ?>" readonly>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?= $this->endSection(); ?>