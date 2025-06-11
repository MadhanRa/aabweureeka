<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>
<title>Akuntansi Eureeka &mdash; Laporan Pembelian</title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <h1>Laporan Pembelian</h1>
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
    <div class="card">
      <div class="card-header">
        <div class="card-header-action">
          <a href="<?= base_url('LaporanPembelian/printPDF?tglawal=' . $tglawal . '&tglakhir=' . $tglakhir . '&supplier=' . $supplier) ?>" class="btn btn-success" target="_blank">
            <i class="fas fa-print"></i> Cetak PDF
          </a>
        </div>
      </div>
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
                <?php foreach ($dtsupplier as $sup): ?>
                  <option value="<?= $sup->id_setupsupplier ?>" <?= $supplier == $sup->id_setupsupplier ? 'selected' : '' ?>>
                    <?= $sup->kode . '-' . $sup->nama ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col">
              <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Tampilkan Data</button>
            </div>
          </div>
        </form>

        <div class="table-responsive mt-3">
          <table class="table table-striped table-md display nowrap compact eureeka-table" id="myTable">
            <thead>
              <tr class="eureeka-table-header">
                <th>No</th>
                <th>Tanggal</th>
                <th>Nota</th>
                <th>Supplier#</th>
                <th>Supplier</th>
                <th>Stock#</th>
                <th>Nama Stock</th>
                <th>Satuan</th>
                <th>Qty 1</th>
                <th>Qty 2</th>
                <th>Harga</th>
                <th>Jml. Harga</th>
                <th>Disc.1(%)</th>
                <th>Disc.2(%)</th>
                <th>Sub.Total</th>
                <th>D.Cash</th>
                <th>Dpp</th>
                <th>PPN</th>
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
                  <td><?= $value->kode_supplier ?></td>
                  <td><?= $value->nama_supplier ?></td>
                  <td><?= $value->kode ?></td>
                  <td><?= $value->nama_barang ?></td>
                  <td><?= $value->satuan ?></td>
                  <td><?= $value->qty1 ?></td>
                  <td><?= $value->qty2 ?></td>
                  <td><?= "Rp " . number_format($value->harga_satuan, 0, ',', '.') ?></td>
                  <td><?= "Rp " . number_format($value->jml_harga, 0, ',', '.') ?></td>
                  <td><?= $value->disc_1_perc ?></td>
                  <td><?= $value->disc_2_perc ?></td>
                  <td><?= "Rp " . number_format($value->sub_total, 0, ',', '.') ?></td>
                  <td><?= "Rp " . number_format($value->row_disc_cash, 0, ',', '.') ?></td>
                  <td><?= "Rp " . number_format($value->row_dpp, 0, ',', '.') ?></td>
                  <td><?= "Rp " . number_format($value->row_ppn, 0, ',', '.') ?></td>
                  <td><?= "Rp " . number_format($value->row_grand_total, 0, ',', '.') ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
            <tfoot>
              <tr>
                <th colspan="14" style="text-align: right;">Subtotal:</th>
                <th><?= "Rp " . number_format($subtotal, 0, ',', '.') ?></th>
                <th colspan="2"></th>
                <th style="text-align: right;">Grand Total:</th>
                <th><?= "Rp " . number_format($grandtotal, 0, ',', '.') ?></th>
              </tr>
            </tfoot>
          </table>
        </div>
        <div class="row mt-3">
          <div class="col">
            <label>Subtotal</label>
            <input class="form-control" type="text" value="<?= "Rp " . number_format($subtotal, 0, ',', '.') ?>" readonly>
          </div>
          <div class="col">
            <label>Discount Cash</label>

            <input class="form-control" type="text" value="<?= "Rp " . number_format($disccash, 0, ',', '.') ?>" readonly>
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

            <input class="form-control" type="text" value="<?= "Rp " . number_format($grandtotal, 0, ',', '.') ?>" readonly>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?= $this->endSection(); ?>