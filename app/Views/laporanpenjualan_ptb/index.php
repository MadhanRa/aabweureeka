<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>
<title>Akuntansi Eureeka &mdash; Laporan Penjualan Per Salesman</title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <h1>Laporan Penjualan Per Salesman</h1>
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
        <a href="<?= base_url('laporanpenjualan_ptb/printPDF?tahun=' . $tahun . '&salesman=' . $salesman . '&lokasi=' . $lokasi . '&pelanggan=' . $pelanggan . '&supplier=' . $supplier . '&view_option=' . $view_option) ?>" class="btn btn-success" target="_blank">
          <i class="fas fa-print"></i> Cetak PDF
        </a>
      </div>
    </div>
    <div class="card-body">
      <form method="GET" action="<?= base_url('laporanpenjualan_ptb') ?>">
        <div class="row">
          <div class="col-md-3">
            <label for="tahun">Tahun</label>
            <input type="number" name="tahun" class="form-control" value="<?= $tahun ?>" min="1900" max="<?= date('Y') ?>" value="<?= date('Y') ?>">
          </div>
          <div class="col-md-3">
            <label for="lokasi">Lokasi</label>
            <select name="lokasi" class="form-control">
              <option value="">Semua Lokasi</option>
              <?php foreach ($dtlokasi as $lok): ?>
                <option value="<?= $lok->id_lokasi ?>" <?= $lok->id_lokasi == $lokasi ? 'selected' : '' ?>><?= $lok->nama_lokasi ?></option>
              <?php endforeach; ?>
            </select>
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

        </div>
        <div class="row mt-3">
          <div class="col-md-3">
            <label for="supplier">Supplier</label>
            <select name="supplier" class="form-control">
              <option value="">Semua Supplier</option>
              <?php foreach ($dtsupplier as $sup): ?>
                <option value="<?= $sup->id_setupsupplier ?>" <?= $sup->id_setupsupplier == $supplier ? 'selected' : '' ?>><?= $sup->kode . ' ' . $sup->nama ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-3">
            <label for="pelanggan">Pelanggan</label>
            <select name="pelanggan" class="form-control">
              <option value="">Semua Pelanggan</option>
              <?php foreach ($dtpelanggan as $plgn): ?>
                <option value="<?= $plgn->id_pelanggan ?>" <?= $plgn->id_pelanggan == $pelanggan ? 'selected' : '' ?>><?= $plgn->kode_pelanggan . ' ' . $plgn->nama_pelanggan ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-3">
            <label for="stock">Stock</label>
            <select name="stock" class="form-control">
              <option value="">Semua Stock</option>
              <?php foreach ($dtstock as $stck): ?>
                <option value="<?= $stck->id_stock ?>" <?= $stck->id_stock == $stock ? 'selected' : '' ?>><?= $stck->kode . '-' . $stck->nama_barang ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="row mt-3">
          <div class="col-md-3">
            <div class="form-group p-3">
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="view_option" value="qty" id="view_option1" <?= $view_option === 'qty' ? 'checked' : '' ?>>
                <label class="form-check-label" for="view_option1">Qty</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="view_option" value="rp" id="view_option2" <?= $view_option === 'rp' ? 'checked' : '' ?>>
                <label class="form-check-label" for="view_option2">Rp</label>
              </div>
            </div>
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
              <th>Kode Stock</th>
              <th>Nama Stock</th>
              <th>Satuan</th>
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
                <td><?= $value->kode ?></td>
                <td><?= $value->nama_barang ?></td>
                <td><?= $value->satuan ?></td>
                <td><?= ($view_option === 'rp') ? "Rp " . number_format($value->total_tahun_lalu, 0, ',', '.') : $value->total_tahun_lalu ?></td>
                <td><?= ($view_option === 'rp') ? "Rp " . number_format($value->Jan, 0, ',', '.') : $value->Jan ?></td>
                <td><?= ($view_option === 'rp') ? "Rp " . number_format($value->Feb, 0, ',', '.') : $value->Feb ?></td>
                <td><?= ($view_option === 'rp') ? "Rp " . number_format($value->Mar, 0, ',', '.') : $value->Mar ?></td>
                <td><?= ($view_option === 'rp') ? "Rp " . number_format($value->Apr, 0, ',', '.') : $value->Apr ?></td>
                <td><?= ($view_option === 'rp') ? "Rp " . number_format($value->Mei, 0, ',', '.') : $value->Mei ?></td>
                <td><?= ($view_option === 'rp') ? "Rp " . number_format($value->Jun, 0, ',', '.') : $value->Jun ?></td>
                <td><?= ($view_option === 'rp') ? "Rp " . number_format($value->Jul, 0, ',', '.') : $value->Jul ?></td>
                <td><?= ($view_option === 'rp') ? "Rp " . number_format($value->Agu, 0, ',', '.') : $value->Agu ?></td>
                <td><?= ($view_option === 'rp') ? "Rp " . number_format($value->Sep, 0, ',', '.') : $value->Sep ?></td>
                <td><?= ($view_option === 'rp') ? "Rp " . number_format($value->Okt, 0, ',', '.') : $value->Okt ?></td>
                <td><?= ($view_option === 'rp') ? "Rp " . number_format($value->Nov, 0, ',', '.') : $value->Nov ?></td>
                <td><?= ($view_option === 'rp') ? "Rp " . number_format($value->Des, 0, ',', '.') : $value->Des ?></td>
                <td><?= ($view_option === 'rp') ? "Rp " . number_format($value->Total, 0, ',', '.') : $value->Total ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  </div>
  </div>
</section>

<?= $this->endSection(); ?>