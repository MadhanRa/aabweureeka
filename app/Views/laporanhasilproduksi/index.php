<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>
<title>Akuntansi Eureeka &mdash; Laporan Hasil Produksi</title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <h1>Laporan Hasil Produksi</h1>
  </div>

  <!-- Tombol Print All -->
  <div class="section-body">
    <div class="card-body">
      <div class="row">
        <div class="col">
          <a href="<?= base_url('LaporanHasilProduksi/printPDF?tglawal=' . $tglawal . '&tglakhir=' . $tglakhir) ?>" class="btn btn-success" target="_blank">
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
      <form action="<?= site_url('laporanhasilproduksi') ?>" method="POST">
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
                <th>Nota</th>
                <th>Lokasi</th>
                <th>Kelompok Produksi</th>
                <th>Nama Stock</th>
                <th>Satuan</th>
                <th>Qty 1</th>
                <th>Qty 2</th>
                <th>Harga Satuan</th>
                <th>Jml. Harga</th>
                <th>Tanggal</th>
                <!-- <th>Action</th> -->
              </tr>
            </thead>
            <tbody>
              <!-- Iterasi Data -->
              <?php foreach ($dthasilproduksi as $key => $value) : ?>
                <tr>
                  <td><?= $key + 1 ?></td>
                  <td><?= $value->nota_produksi ?></td>
                  <td><?= $value->lokasi_asal ?></td>
                  <td><?= $value->nama_kelproduksi ?></td>
                  <td><?= $value->nama_stock ?></td>
                  <td><?= $value->kode_satuan ?></td>
                  <td><?= $value->qty_1 ?></td>
                  <td><?= $value->qty_2 ?></td>
                  <td><?= "Rp " . number_format($value->harga_satuan, 0, ',', '.') ?></td>
                  <td><?= "Rp " . number_format($value->jml_harga, 0, ',', '.') ?></td>
                  <td><?= $value->tanggal ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

<?= $this->endSection(); ?>