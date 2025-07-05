<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>
<title>Akuntansi Eureeka &mdash; Laporan Stock Opname</title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <h1>Laporan Stock Opname</h1>
  </div>

  <div class="card">
    <div class="card-header">
      <div class="card-header-action">
        <a href="<?= base_url('laporanstock_opname/printPDF?tglawal=' . $tglawal . '&tglakhir=' . $tglakhir . '&lokasi=' . $lokasi . '&user=' . $user) ?>" class="btn btn-success" target="_blank">
          <i class="fas fa-print"></i> Cetak PDF
        </a>
      </div>
    </div>
    <div class="card-body">
      <form method="GET" action="<?= base_url('laporanstock_opname') ?>">
        <div class="row">
          <div class="col-md-3">
            <label for="tglawal">Tanggal Awal</label>
            <input type="date" name="tglawal" class="form-control" value="<?= $tglawal ?>">
          </div>
          <div class="col-md-3">
            <label for="tglakhir">Tanggal Akhir</label>
            <input type="date" name="tglakhir" class="form-control" value="<?= $tglakhir ?>">
          </div>
        </div>
        <div class="row mt-3">
          <div class="col-md-3">
            <label for="lokasi">Lokasi</label>
            <select id="lokasi" name="lokasi" class="form-control">
              <option value="">Semua Lokasi</option>
              <?php foreach ($dtlokasi as $lok): ?>
                <option value="<?= $lok->id_lokasi ?>" <?= $lok->id_lokasi == $lokasi ? 'selected' : '' ?>><?= $lok->nama_lokasi ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-3">
            <label for="user">User</label>
            <select name="user" class="form-control">
              <option value="">Semua User</option>
              <?php foreach ($dtuser as $user): ?>
                <option value="<?= $user->id_user ?>" <?= $user->id_user == $user ? 'selected' : '' ?>><?= $user->nama_user ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-auto mt-4">
            <button type="submit" class="btn btn-primary">Filter</button>
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
              <th>Lokasi</th>
              <th>User</th>
              <th>Stock#</th>
              <th>Nama Stock</th>
              <th>Satuan</th>
              <th>Qty1</th>
              <th>Qty2</th>
            </tr>
          </thead>
          <tbody>
            <!-- Iterasi Data -->
            <?php foreach ($dtdaftar_stock_opname as $key => $value) : ?>
              <tr>
                <td><?= $key + 1 ?></td>
                <td><?= $value->tanggal ?></td>
                <td><?= $value->nota ?></td>
                <td><?= $value->nama_lokasi ?></td>
                <td><?= $value->nama_user ?></td>
                <td><?= $value->kode ?></td>
                <td><?= $value->nama_barang ?></td>
                <td><?= $value->satuan ?></td>
                <td><?= $value->qty_1 ?></td>
                <td><?= $value->qty_2 ?></td>
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