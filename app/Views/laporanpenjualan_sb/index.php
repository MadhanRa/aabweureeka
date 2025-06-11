<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>
<title>Akuntansi Eureeka &mdash; Laporan Penjualan Per Supplier Per Barang</title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <h1>Laporan Penjualan Per Supplier Per Barang</h1>
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
        <a href="<?= base_url('laporanpenjualan_sb/printPDF?tglawal=' . $tglawal . '&tglakhir=' . $tglakhir) ?>" class="btn btn-success" target="_blank">
          <i class="fas fa-print"></i> Cetak PDF
        </a>
      </div>
    </div>
    <div class="card-body">
      <form method="GET" action="<?= base_url('laporanpenjualan_sb') ?>">
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
            <button type="submit" class="btn btn-primary mt-4">Filter</button>
          </div>
        </div>
      </form>

      <div class="table-responsive mt-5">
        <table class="table table-striped table-md display nowrap compact eureeka-table" id="myTable">
          <thead>
            <tr class="eureeka-table-header">
              <th>No</th>
              <th>Supplier#</th>
              <th>Nama Supplier</th>
              <th>Stock#</th>
              <th>Nama Stock</th>
              <th>Qty(D/P)</th>
              <th>Hrg.Sat</th>
              <th>Jml.Hrg</th>
              <th>Disc.1</th>
              <th>Disc.2</th>
              <th>Sub.Tot</th>
              <th>D.Cash</th>
              <th>DPP</th>
              <th>PPN</th>
              <th>TOT</th>
            </tr>
          </thead>
          <tbody>
            <!-- Iterasi Data -->
            <?php foreach ($dtpenjualan as $key => $value) : ?>
              <tr>
                <td><?= $key + 1 ?></td>
                <td><?= $value->kode_supplier ?></td>
                <td><?= $value->nama_supplier ?></td>
                <td><?= $value->kode_barang ?></td>
                <td><?= $value->nama_barang ?></td>
                <td><?= $value->qty ?></td>
                <td><?= $value->harga_satuan ?></td>
                <td><?= "Rp " . number_format($value->jml_harga, 0, ',', '.') ?></td>
                <td><?= $value->disc_1 ?></td>
                <td><?= $value->disc_2 ?></td>
                <td><?= "Rp " . number_format($value->sub_total, 0, ',', '.') ?></td>
                <td><?= "Rp " . number_format($value->disc_cash, 0, ',', '.') ?></td>
                <td><?= "Rp " . number_format($value->netto, 0, ',', '.') ?></td>
                <td><?= "Rp " . number_format($value->ppn, 0, ',', '.') ?></td>
                <td><?= "Rp " . number_format($value->total, 0, ',', '.') ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="row mt-3">
        <div class="col">
          <label>Jml. Harga</label>
          <input class="form-control" type="text" value="<?= "Rp " . number_format($jml_harga, 0, ',', '.') ?>" readonly>
        </div>
        <div class="col">
          <label>Sub. Total</label>
          <input class="form-control" type="text" value="<?= "Rp " . number_format($subtotal, 0, ',', '.') ?>" readonly>
        </div>
        <div class="col">
          <label>Discount Cash</label>

          <input class="form-control" type="text" value="<?= "Rp " . number_format($discount_cash, 0, ',', '.') ?>" readonly>
        </div>
        <div class="col">
          <label>DPP</label>

          <input class="form-control" type="text" value="<?= "Rp " . number_format($dpp, 0, ',', '.') ?>" readonly>
        </div>
        <div class="col">
          <label>PPN</label>

          <input class="form-control" type="text" value="<?= "Rp " . number_format($ppn, 0, ',', '.') ?>" readonly>
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