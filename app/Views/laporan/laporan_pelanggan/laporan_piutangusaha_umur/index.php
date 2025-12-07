<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>
<title>Akuntansi Eureeka &mdash; Laporan Umur Piutang</title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <h1>Daftar Umur Piutang</h1>
  </div>

  <div class="card">
    <div class="card-header">
      <div class="card-header-action">
        <a href="<?= base_url('laporanumurpiutang/printPDF?per_tanggal=' . $per_tanggal . '&salesman=' . $salesman) ?>" class="btn btn-success" target="_blank">
          <i class="fas fa-print"></i> Cetak PDF
        </a>
      </div>
    </div>
    <div class="card-body">
      <form method="GET" action="<?= base_url('laporanumurpiutang') ?>">
        <div class="row">
          <div class="col-md-2">
            <label for="tglawal">Per Tanggal</label>
            <input type="date" name="per_tanggal" class="form-control" value="<?= $per_tanggal ?>">
          </div>
          <div class="col-md-3">
            <label for="salesman">Salesman</label>
            <select name="salesman" class="form-control">
              <option value="">Semua Salesman</option>
              <?php foreach ($dtsalesman as $sales): ?>
                <option value="<?= $sales->id_salesman ?>" <?= $sales->id_salesman == $salesman ? 'selected' : '' ?>><?= $sales->nama_salesman ?></option>
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
              <th>Pelanggan#</th>
              <th>Nama Pelanggan</th>
              <th>Tgl.Jual</th>
              <th>Nota.Jual</th>
              <th>Tgl.JT</th>
              <th>
                <=30 Hari</th>
              <th>31-60 Hari</th>
              <th>61-90 Hari</th>
              <th>>90 Hari</th>
            </tr>
          </thead>
          <tbody>
            <!-- Iterasi Data -->
            <?php foreach ($dtdaftar_piutang as $key => $value) : ?>
              <tr>
                <td><?= $key + 1 ?></td>
                <td><?= $value['kode_pelanggan'] ?></td>
                <td><?= $value['nama_pelanggan'] ?></td>
                <td><?= $value['tanggal'] ?></td>
                <td><?= $value['nota'] ?></td>
                <td><?= $value['tgl_jatuhtempo'] ?></td>
                <td><?= "Rp " . number_format($value['kurang_dari'], 0, ',', '.') ?></td>
                <td><?= "Rp " . number_format($value['antara1'], 0, ',', '.') ?></td>
                <td><?= "Rp " . number_format($value['antara2'], 0, ',', '.') ?></td>
                <td><?= "Rp " . number_format($value['lebih_dari'], 0, ',', '.') ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="row mt-3">
        <div class="col">
          <label>
            <=30 Hari</label>
              <input class="form-control" type="text" value="<?= "Rp " . number_format($kurang_dari_total, 0, ',', '.') ?>" readonly>
        </div>
        <div class="col">
          <label>31-60 Hari</label>
          <input class="form-control" type="text" value="<?= "Rp " . number_format($antara1_total, 0, ',', '.') ?>" readonly>
        </div>
        <div class="col">
          <label>61-90 Hari</label>
          <input class="form-control" type="text" value="<?= "Rp " . number_format($antara2_total, 0, ',', '.') ?>" readonly>
        </div>
        <div class="col">
          <label>>90 Hari</label>
          <input class="form-control" type="text" value="<?= "Rp " . number_format($lebih_dari_total, 0, ',', '.') ?>" readonly>
        </div>
        <div class="col">
          <label>Total</label>
          <input class="form-control" type="text" value="<?= "Rp " . number_format($grand_total, 0, ',', '.') ?>" readonly>
        </div>
      </div>
    </div>
  </div>
  </div>
  </div>
</section>

<?= $this->endSection(); ?>