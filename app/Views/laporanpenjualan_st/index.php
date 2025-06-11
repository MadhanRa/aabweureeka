<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>
<title>Akuntansi Eureeka &mdash; Laporan Penjualan Per Salesman (Tahun)</title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <h1>Laporan Penjualan Per Salesman (Tahun)</h1>
  </div>

  <!-- Menampilkan Pesan Sukses -->
  <?php if (session()->getFlashdata('Sukses')) : ?>
    <div class="alert alert-success alert-dismissible show fade">
      <div class="alert-body">
        <button class="close" data-dismiss="alert">
          <span>&times;</span>
        </button>
        <?= session()->getFlashdata('Sukses') ?>
      </div>
    </div>
  <?php endif; ?>

  <div class="card">
    <div class="card-header">
      <div class="card-header-action">
        <a href="<?= base_url('laporanpenjualan_st/printPDF?tahun=' . $tahun) ?>" class="btn btn-success" target="_blank">
          <i class="fas fa-print"></i> Cetak PDF
        </a>
      </div>
    </div>
    <div class="card-body">
      <form method="GET" action="<?= base_url('laporanpenjualan_st') ?>">
        <div class="row">
          <div class="col-md-3">
            <label for="tahun">Tahun</label>
            <input type="number" name="tahun" class="form-control" value="<?= $tahun ?>" min="1900" max="<?= date('Y') ?>" value="<?= date('Y') ?>">
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
              <th>Salesman</th>
              <th>Tahun Lalu</th>
              <th>Januari</th>
              <th>Februari</th>
              <th>Maret</th>
              <th>April</th>
              <th>Mei</th>
              <th>Juni</th>
              <th>Juli</th>
              <th>Agustus</th>
              <th>September</th>
              <th>Oktober</th>
              <th>November</th>
              <th>Desember</th>
              <th>Total</th>
              <!-- <th>Action</th> -->
            </tr>
          </thead>
          <tbody>
            <!-- Iterasi Data -->
            <?php foreach ($dtpenjualan as $key => $value) : ?>
              <tr>
                <td><?= $key + 1 ?></td>
                <td><?= $value->nama_salesman ?></td>
                <td><?= "Rp " . number_format($value->total_tahun_lalu, 0, ',', '.') ?></td>
                <td><?= "Rp " . number_format($value->Jan, 0, ',', '.') ?></td>
                <td><?= "Rp " . number_format($value->Feb, 0, ',', '.') ?></td>
                <td><?= "Rp " . number_format($value->Mar, 0, ',', '.') ?></td>
                <td><?= "Rp " . number_format($value->Apr, 0, ',', '.') ?></td>
                <td><?= "Rp " . number_format($value->Mei, 0, ',', '.') ?></td>
                <td><?= "Rp " . number_format($value->Jun, 0, ',', '.') ?></td>
                <td><?= "Rp " . number_format($value->Jul, 0, ',', '.') ?></td>
                <td><?= "Rp " . number_format($value->Agu, 0, ',', '.') ?></td>
                <td><?= "Rp " . number_format($value->Sep, 0, ',', '.') ?></td>
                <td><?= "Rp " . number_format($value->Okt, 0, ',', '.') ?></td>
                <td><?= "Rp " . number_format($value->Nov, 0, ',', '.') ?></td>
                <td><?= "Rp " . number_format($value->Des, 0, ',', '.') ?></td>
                <td><?= "Rp " . number_format($value->Total, 0, ',', '.') ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr>
              <th colspan="2" style="text-align: right;">Total Tahun Lalu:</th>
              <td><?= "Rp " . number_format($total_tl, 0, ',', '.') ?></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
  </div>
  </div>
</section>

<?= $this->endSection(); ?>