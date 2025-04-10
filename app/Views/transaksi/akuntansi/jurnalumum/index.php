<?= $this->extend("layout/backend") ?>;

<?= $this->section("content") ?>
<title>Akuntansi Eureeka &mdash; Transaksi Jurnal Umum</title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <!-- <h1>APA INI</h1> -->
    <a href="<?= site_url('jurnalumum/new') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Data</a>
  </div>

  <!-- Tombol Print All -->
  <div class="section-body">
    <div class="card-body">
      <form action="<?= site_url('jurnalumum') ?>" method="POST">
        <?= csrf_field() ?>
        <div class="row">
          <div class="col">
            <a href="<?= base_url('JurnalUmum/printPDF') ?>" class="btn btn-success" target="_blank">
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
        <h4>Transaksi Jurnal Umum</h4>
      </div>
      <div class="section-body">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped table-md display nowrap compact eureeka-table" id="myTable">
              <thead>
                <tr class="eureeka-table-header">
                  <th>No</th>
                  <th>Tanggal</th>
                  <th>Nota</th>
                  <th>Rekening</th>
                  <th>B.Pembantu</th>
                  <th>Nama Rekening</th>
                  <th>Nama Buku Pembantu</th>
                  <th>No.Ref</th>
                  <th>Debet</th>
                  <th>Kredit</th>
                  <th>Tgl.Nota</th>
                  <th>Keterangan</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <!-- TEMPAT FOREACH -->
                <?php foreach ($dtjurnalumum as $key => $value) : ?>
                  <tr>
                    <td><?= $key + 1 ?></td>
                    <td><?= $value->tanggal ?></td>
                    <td><?= $value->nota ?></td>
                    <td><?= $value->rekening ?></td>
                    <td><?= $value->b_pembantu ?></td>
                    <td><?= $value->nama_rekening ?></td>
                    <td><?= $value->nama_bpembantu ?></td>
                    <td><?= $value->no_ref ?></td>
                    <td><?= "Rp " . number_format($value->debet, 0, ',', '.') ?></td>
                    <td><?= "Rp " . number_format($value->kredit, 0, ',', '.') ?></td>
                    <td><?= $value->tgl_nota ?></td>
                    <td><?= $value->keterangan ?></td>

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
                        <a href="<?= site_url('jurnalumum/' . $value->id_jurnalumum) .  '/edit' ?>" class="btn btn-warning"><i class="fas fa-pencil-alt btn-small"></i> Edit</a>
                        <input type="hidden" name="_method" value="PUT">



                        <!-- Tombol Hapus Data -->
                        <form action="<?= site_url('jurnalumum/' . $value->id_jurnalumum) ?>" method="post" id="del-<?= $value->id_jurnalumum ?>" class="d-inline">
                          <?= csrf_field() ?>
                          <input type="hidden" name="_method" value="DELETE">
                          <button class="btn btn-danger btn-small" data-confirm="Hapus Data....?" data-confirm-yes="hapus(<?= $value->id_jurnalumum ?>)">
                            <i class="fas fa-trash"></i>
                          </button>
                        </form>
                      <?php endif ?>
                      <!-- Tombol Print Data -->
                      <a href="<?= base_url('JurnalUmum/printPDF/' . $value->id_jurnalumum) ?>" class="btn btn-success btn-small" target="_blank">
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