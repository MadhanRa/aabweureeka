<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>
<title>Akuntansi Eureeka &mdash; Laporan Pembelian</title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <h1>Laporan Pembelian</h1>
  </div>

  <!-- Tombol Print All -->
  <div class="section-body">
    <div class="card-body">
      <div class="row">
        <div class="col">
          <a href="<?= base_url('LaporanPembelian/printPDF?tglawal=' . $tglawal . '&tglakhir=' . $tglakhir . '&supplier=' . $supplier) ?>" class="btn btn-success" target="_blank">
            <i class="fas fa-print"></i> Cetak PDF
          </a>
        </div>
      </div>
    </div>
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

  <!-- Tabel Data -->
  <div class="section-body">
    <div class="card-body">
      <form action="<?= site_url('laporanpembelian') ?>" method="POST">
        <?= csrf_field() ?>
        <div class="row g-3">
          <div class="col">
            <input type="date" class="form-control" name="tglawal" value="<?= $tglawal ?>">
          </div>
          <div class="col">
            <input type="date" class="form-control" name="tglakhir" value="<?= $tglakhir ?>">
          </div>
          <div class="col">
            <select name="supplier" class="form-control">
              <option value="">-- Semua Supplier --</option>
              <?php foreach ($dtsetupsupplier as $sup): ?>
                <option value="<?= $sup->id_setupsupplier ?>" <?= $supplier == $sup->id_setupsupplier ? 'selected' : '' ?>>
                  <?= $sup->nama ?>
                </option>
              <?php endforeach; ?>
            </select>
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
                <th>Supplier</th>
                <th>Nama Stock</th>
                <th>Satuan</th>
                <th>Qty 1</th>
                <th>Qty 2</th>
                <th>Harga</th>
                <th>Jml. Harga</th>
                <th>Disc.1(%)</th>
                <th>Disc.2(%)</th>
                <th>Total</th>
                <!-- <th>Action</th> -->
              </tr>
            </thead>
            <tbody>
              <!-- Iterasi Data -->
              <?php foreach ($dtpembelian as $key => $value) : ?>
                <tr>
                  <td><?= $key + 1 ?></td>
                  <td><?= $value->tanggal ?></td>
                  <td><?= $value->nota ?></td>
                  <td><?= $value->nama_supplier ?></td>
                  <td><?= $value->nama_stock ?></td>
                  <td><?= $value->kode_satuan ?></td>
                  <td><?= $value->qty_1 ?></td>
                  <td><?= $value->qty_2 ?></td>
                  <td><?= "Rp " . number_format($value->harga_satuan, 0, ',', '.') ?></td>
                  <td><?= "Rp " . number_format($value->jml_harga, 0, ',', '.') ?></td>
                  <td><?= $value->disc_1 ?></td>
                  <td><?= $value->disc_2 ?></td>
                  <td><?= "Rp " . number_format($value->total, 0, ',', '.') ?></td>
                  <!-- <td>
                    <a href="<?= base_url('Pembelian/printPDF/' . $value->id_pembelian) ?>" class="btn btn-success btn-small" target="_blank">
                      <i class="fas fa-print"></i>
                    </a>
                  </td> -->
                </tr>
              <?php endforeach; ?>
            </tbody>
            <tfoot>
              <tr>
                <th colspan="12" style="text-align: right;">Subtotal:</th>
                <th><?= "Rp " . number_format($subtotal, 0, ',', '.') ?></th>
                <th colspan="3"></th>
              </tr>
              <tr>
                <th colspan="12" style="text-align: right;">Grand Total:</th>
                <th><?= "Rp " . number_format($grandtotal, 0, ',', '.') ?></th>
                <th colspan="3"></th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

<?= $this->endSection(); ?>