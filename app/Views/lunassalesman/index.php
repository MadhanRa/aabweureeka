<?= $this->extend("layout/backend") ?>;

<?= $this->section("content") ?>
<title>Akuntansi Eureeka &mdash; Pelunasan Piutang Salesman</title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <!-- <h1>APA INI</h1> -->
    <a href="<?= site_url('lunassalesman/new') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Data</a>
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
        <h4>Pelunasan Piutang Salesman</h4>
      </div>
      <div class="section-body">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped table-md display nowrap compact eureeka-table" id="myTable">
              <thead>
                <tr class="eureeka-table-header">
                  <th>No</th>
                  <th>Nota</th>
                  <th>Salesman</th>
                  <th>Tanggal</th>
                  <th>Rekening Pelunasan</th>
                  <th>Saldo</th>
                  <th>Nilai Pelunasan</th>
                  <th>Discount</th>
                  <th>PDPT.lain-lain</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <!-- TEMPAT FOREACH -->
                <?php foreach ($dtlunassalesman as $key => $value) : ?>
                  <tr>
                    <td><?= $key + 1 ?></td>
                    <td><?= $value->nota ?></td>
                    <td><?= $value->nama_salesman ?></td>
                    <td><?= $value->tanggal ?></td>
                    <td><?= $value->nama_setupbank ?></td>
                    <td><?= "Rp " . number_format($value->saldo, 0, ',', '.') ?></td>
                    <td><?= "Rp " . number_format($value->nilai_pelunasan, 0, ',', '.') ?></td>
                    <td><?= $value->diskon ?></td>
                    <td><?= $value->pdpt ?></td>

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
                        <a href="<?= site_url('lunassalesman/' . $value->id_lunashusalesman) .  '/edit' ?>" class="btn btn-warning"><i class="fas fa-pencil-alt btn-small"></i> Edit</a>
                        <input type="hidden" name="_method" value="PUT">


                        <!-- Tombol Hapus Data -->
                        <form action="<?= site_url('lunassalesman/' . $value->id_lunashusalesman) ?>" method="post" id="del-<?= $value->id_lunashusalesman ?>" class="d-inline">
                          <?= csrf_field() ?>
                          <input type="hidden" name="_method" value="DELETE">
                          <button class="btn btn-danger btn-small" data-confirm="Hapus Data....?" data-confirm-yes="hapus(<?= $value->id_lunashusalesman ?>)">
                            <i class="fas fa-trash"></i>
                          </button>
                        </form>
                      <?php endif ?>

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