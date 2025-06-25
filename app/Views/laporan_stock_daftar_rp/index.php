<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>
<title>Akuntansi Eureeka &mdash; Laporan Daftar Stock (RP)</title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <h1>Daftar Stock (RP)</h1>
  </div>

  <div class="card">
    <div class="card-header">
      <div class="card-header-action">
        <a href="<?= base_url('laporandaftarstock/printPDF?tglawal=' . $tglawal . '&tglakhir=' . $tglakhir . '&kelompok=' . $kelompok . '&group=' . $group) ?>" class="btn btn-success" target="_blank">
          <i class="fas fa-print"></i> Cetak PDF
        </a>
      </div>
    </div>
    <div class="card-body">
      <form method="GET" action="<?= base_url('laporandaftarstock') ?>">
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
            <label for="kelompok">Kelompok</label>
            <select name="kelompok" class="form-control">
              <option value="">Semua Kelompok</option>
              <?php foreach ($dtkelompok as $kel): ?>
                <option value="<?= $kel->id_kelompok ?>" <?= $kel->id_kelompok == $kelompok ? 'selected' : '' ?>><?= $kel->nama_kelompok ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-3">
            <label for="group">Group</label>
            <select name="group" class="form-control">
              <option value="">Semua Group</option>
              <?php foreach ($dtgroup as $grp): ?>
                <option value="<?= $grp->id_group ?>" <?= $grp->id_group == $group ? 'selected' : '' ?>><?= $grp->nama_group ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-auto mt-3">
            <button type="submit" class="btn btn-primary">Filter</button>
          </div>
        </div>
      </form>

      <div class="table-responsive mt-5">
        <table class="table table-striped table-md display nowrap compact eureeka-table" id="myTable">
          <thead>
            <tr class="eureeka-table-header">
              <th>No</th>
              <th>Stock#</th>
              <th>Nama Stock</th>
              <th>Satuan</th>
              <th>Awal(Q)</th>
              <th>Awal(Rp)</th>
              <th>Masuk(Q)</th>
              <th>Masuk(Rp)</th>
              <th>Keluar(Q)</th>
              <th>Keluar(Rp)</th>
              <th>Saldo(Q)</th>
              <th>Saldo(Rp)</th>
              <th>Rata-Rata</th>
            </tr>
          </thead>
          <tbody>
            <!-- Iterasi Data -->
            <?php foreach ($dtdaftar_mutasi as $key => $value) : ?>
              <tr>
                <td><?= $key + 1 ?></td>
                <td><?= $value->kode ?></td>
                <td><?= $value->nama_barang ?></td>
                <td><?= $value->satuan ?></td>
                <td><?= $value->initial_qty1 . '/' . $value->initial_qty2 ?></td>
                <td><?= "Rp " . number_format($value->initial_nilai, 0, ',', '.') ?></td>
                <td><?= $value->in_qty1 . '/' . $value->in_qty2 ?></td>
                <td><?= "Rp " . number_format($value->in_nilai, 0, ',', '.') ?></td>
                <td><?= $value->out_qty1 . '/' . $value->out_qty2 ?></td>
                <td><?= "Rp " . number_format($value->out_nilai, 0, ',', '.') ?></td>
                <td><?= $value->ending_qty1 . '/' . $value->ending_qty2 ?></td>
                <td><?= "Rp " . number_format($value->ending_nilai, 0, ',', '.') ?></td>
                <td><?= "Rp " . number_format($value->rata_rata, 0, ',', '.') ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="row mt-3">
        <div class="col">
          <label>Awal</label>
          <input class="form-control" type="text" value="<?= "Rp " . number_format($awal_total, 0, ',', '.') ?>" readonly>
        </div>
        <div class="col">
          <label>Masuk</label>
          <input class="form-control" type="text" value="<?= "Rp " . number_format($masuk_total, 0, ',', '.') ?>" readonly>
        </div>
        <div class="col">
          <label>Keluar</label>
          <input class="form-control" type="text" value="<?= "Rp " . number_format($keluar_total, 0, ',', '.') ?>" readonly>
        </div>
        <div class="col">
          <label>Akhir (Rp)</label>
          <input class="form-control" type="text" value="<?= "Rp " . number_format($akhir_total, 0, ',', '.') ?>" readonly>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col">
          <input class="form-control" type="text" value="<?= "Rp " . number_format($awal_total_all, 0, ',', '.') ?>" readonly>
        </div>
        <div class="col">
          <input class="form-control" type="text" value="<?= "Rp " . number_format($masuk_total_all, 0, ',', '.') ?>" readonly>
        </div>
        <div class="col">
          <input class="form-control" type="text" value="<?= "Rp " . number_format($keluar_total_all, 0, ',', '.') ?>" readonly>
        </div>
        <div class="col">
          <input class="form-control" type="text" value="<?= "Rp " . number_format($akhir_total_all, 0, ',', '.') ?>" readonly>
        </div>
      </div>
    </div>
  </div>
  </div>
  </div>
</section>


<?= $this->endSection(); ?>