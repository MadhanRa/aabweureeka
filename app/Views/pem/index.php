<?= $this->extend("layout/backend") ?>;

<?= $this->section("content") ?>
<title>Akuntansi Eureeka &mdash; Transaksi Pembelian</title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <!-- <h1>APA INI</h1> -->
    <a href="<?= site_url('pembelian/new') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Data</a>
  </div>

  <!-- Tombol Print All -->
  <div class="section-body">
    <div class="card-body">
      <div class="row">
        <div class="col">
          <a href="<?= base_url('Pembelian/printPDF') ?>" class="btn btn-success" target="_blank">
            <i class="fas fa-print"></i> Cetak PDF
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- untuk menangkap session success dengan bawaan with -->

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

  <div class="section-body">
    <!-- HALAMAN DINAMIS -->
    <div class="card">
      <div class="card-header">
        <h4>Transaksi Pembelian</h4>
      </div>
      <div class="section-body">
        <div class="card-body">
          <div class="table-responsive">
            <table border="2px" class="tablet able-striped table-md" id="myTable" style="border-color:  #009548; border-width: 4px; border-style: solid;">
              <thead>
                <tr style="background-color:  #009548; color: white;">
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
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <!-- TEMPAT FOREACH -->
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

                    <td class="text-center">
                    <?php if ($is_closed === 'TRUE'): ?>
                        <button class="btn btn-warning btn-read">
                          <i class="lock-icon fas fa-lock"></i> Edit
                        </button>
                        <button class="btn btn-danger btn-read">
                          <i class="lock-icon fas fa-lock"></i> Delete
                        </button>
                      <?php else: ?>
                      <!-- Tombol Edit Data -->
                      <a href="<?= site_url('pembelian/' . $value->id_pembelian) .  '/edit' ?>" class="btn btn-warning btn-small"><i class="fas fa-pencil-alt btn-small"></i> Edit</a>
                      <input type="hidden" name="_method" value="PUT">
                      <!-- Tombol Hapus Data -->
                      <form action="<?= site_url('pembelian/' . $value->id_pembelian) ?>" method="post" id="del-<?= $value->id_pembelian ?>" class="d-inline">
                        <?= csrf_field() ?>
                        <input type="hidden" name="_method" value="DELETE">
                        <button class="btn btn-danger btn-small" data-confirm="Hapus Data....?" data-confirm-yes="hapus(<?= $value->id_pembelian ?>)">
                          <i class="fas fa-trash"></i>
                        </button>
                      </form>
                      <?php endif ?>

                      <!-- Tombol Print Data -->
                      <a href="<?= base_url('Pembelian/printPDF/' . $value->id_pembelian) ?>" class="btn btn-success btn-small" target="_blank">
                        <i class="fas fa-print"></i>
                      </a>

                    </td>
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