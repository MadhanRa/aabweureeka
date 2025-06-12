<?= $this->extend("layout/backend") ?>;

<?= $this->section("content") ?>
<title>Akuntansi Eureeka &mdash; Pindah Lokasi</title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <!-- <h1>APA INI</h1> -->
    <a href="<?= site_url('pindahlokasi/new') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Data</a>
  </div>

  <!-- Tombol Print All -->
  <div class="section-body">
    <div class="card-body">
      <form action="<?= site_url('pindahlokasi') ?>" method="POST">
        <?= csrf_field() ?>
        <div class="row">
          <div class="col">
            <a href="<?= base_url('PindahLokasi/printPDF') ?>" class="btn btn-success" target="_blank">
              <i class="fas fa-print"></i> Cetak PDF
            </a>
          </div>
        </div>
      </form>
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
        <h4>Pindah Lokasi</h4>
      </div>
      <div class="section-body">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped table-md display nowrap compact eureeka-table" id="myTable">
              <thead>
                <tr class="eureeka-table-header">
                  <th>No</th>
                  <th>Lokasi Asal</th>
                  <th>Lokasi Tujuan</th>
                  <th>Nota</th>
                  <th>Nama Stock</th>
                  <th>Satuan</th>
                  <th>Qty 1</th>
                  <th>Qty 2</th>
                  <th>Tanggal</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <!-- TEMPAT FOREACH -->
                <?php
                // print_r($is_closed);die;
                foreach ($dtpindahlokasi as $key => $value) : ?>
                  <tr>
                    <td><?= $key + 1 ?></td>
                    <td><?= $value->lokasi_asal ?></td>
                    <td><?= $value->lokasi_tujuan ?></td>
                    <td><?= $value->nota_pindah ?></td>
                    <td><?= $value->nama_stock ?></td>
                    <td><?= $value->kode_satuan ?></td>
                    <td><?= $value->qty_1 ?></td>
                    <td><?= $value->qty_2 ?></td>
                    <td><?= $value->tanggal ?></td>
                    <td class="text-center">
                      <!-- Tombol Edit Data -->
                      <?php if ($is_closed === 'TRUE'): ?>
                        <button class="btn btn-warning btn-read">
                          <i class="lock-icon fas fa-lock"></i> Edit
                        </button>
                        <button class="btn btn-danger btn-read">
                          <i class="lock-icon fas fa-lock"></i> Delete
                        </button>
                      <?php else: ?>
                        <a href="<?= site_url('pindahlokasi/' . $value->id_pindah) .  '/edit' ?>" class="btn btn-warning "><i class="fas fa-pencil-alt btn-small"></i> Edit</a>
                        <input type="hidden" name="_method" value="PUT">
                        <!-- Tombol Hapus Data -->
                        <form action="<?= site_url('pindahlokasi/' . $value->id_pindah) ?>" method="post" id="del-<?= $value->id_pindah ?>" class="d-inline">
                          <?= csrf_field() ?>
                          <input type="hidden" name="_method" value="DELETE">
                          <button class="btn btn-danger btn-small " data-confirm="Hapus Data....?" data-confirm-yes="hapus(<?= $value->id_pindah ?>)">
                            <i class="fas fa-trash"></i>
                          </button>
                        </form>
                      <?php endif ?>

                      <!-- Tombol Print Data -->
                      <a href="<?= base_url('PindahLokasi/printPDF/' . $value->id_pindah) ?>" class="btn btn-success btn-small" target="_blank">
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