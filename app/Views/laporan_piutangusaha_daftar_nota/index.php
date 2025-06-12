<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>
<title>Akuntansi Eureeka &mdash; Laporan Daftar Piutang Usaha Per Nota</title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <h1>Daftar Piutang Usaha Per Nota</h1>
  </div>

  <div class="card">
    <div class="card-header">
      <div class="card-header-action">
        <a href="<?= base_url('laporandaftarpiutangusahanota/printPDF?tglawal=' . $tglawal . '&tglakhir=' . $tglakhir . '&salesman=' . $salesman . '&pelanggan=' . $pelanggan) ?>" class="btn btn-success" target="_blank">
          <i class="fas fa-print"></i> Cetak PDF
        </a>
      </div>
    </div>
    <div class="card-body">
      <form method="GET" action="<?= base_url('laporandaftarpiutangusahanota') ?>">
        <div class="row">
          <div class="col-md-2">
            <label for="tglawal">Tanggal Awal</label>
            <input type="date" name="tglawal" class="form-control" value="<?= $tglawal ?>">
          </div>
          <div class="col-md-2">
            <label for="tglakhir">Tanggal Akhir</label>
            <input type="date" name="tglakhir" class="form-control" value="<?= $tglakhir ?>">
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
            <label for="pelanggan">Pelanggan</label>
            <select name="pelanggan" class="form-control">
              <option value="">Semua Pelanggan</option>
              <?php foreach ($dtpelanggan as $plgn): ?>
                <option value="<?= $plgn->id_pelanggan ?>" <?= $plgn->id_pelanggan == $pelanggan ? 'selected' : '' ?>><?= $plgn->kode_pelanggan . ' ' . $plgn->nama_pelanggan ?></option>
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
              <th>Salesman#</th>
              <th>Nama Salesman</th>
              <th>Tgl.Jual</th>
              <th>Nota.Jual</th>
              <th>Tgl.JT</th>
              <th>Awal</th>
              <th>Debet</th>
              <th>Kredit</th>
              <th>Saldo</th>
              <th>Tgl.Bayar</th>
            </tr>
          </thead>
          <tbody>
            <!-- Iterasi Data -->
            <?php foreach ($dtdaftar_piutang as $key => $value) : ?>
              <tr>
                <td><?= $key + 1 ?></td>
                <td><?= $value->kode_pelanggan ?></td>
                <td><?= $value->nama_pelanggan ?></td>
                <td><?= $value->kode_salesman ?></td>
                <td><?= $value->nama_salesman ?></td>
                <td><?= $value->tanggal ?></td>
                <td><?= $value->nota ?></td>
                <td><?= $value->tgl_jatuhtempo ?></td>
                <td><?= "Rp " . number_format($value->saldo_awal, 0, ',', '.') ?></td>
                <td><?= "Rp " . number_format($value->debit, 0, ',', '.') ?></td>
                <td><?= "Rp " . number_format($value->kredit, 0, ',', '.') ?></td>
                <td><?= "Rp " . number_format($value->saldo, 0, ',', '.') ?></td>
                <td><?= $value->tgl_bayar ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="row mt-3">
        <div class="col">
          <label>Saldo Awal</label>
          <input class="form-control" type="text" value="<?= "Rp " . number_format($saldo_awal_total, 0, ',', '.') ?>" readonly>
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
          <label>Saldo Akhir</label>
          <input class="form-control" type="text" value="<?= "Rp " . number_format($saldo_akhir_total, 0, ',', '.') ?>" readonly>
        </div>
      </div>
    </div>
  </div>
  </div>
  </div>
</section>

<?= $this->endSection(); ?>