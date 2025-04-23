<?= $this->extend("layout/backend") ?>;

<?= $this->section("content") ?>
<title>Akuntansi Eureeka &mdash; Setup User Stock Opname </title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <h1>User Stock Opname</h1>
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
        <h4>Setup User Stock Opname</h4>
        <div class="card-header-action">
          <a href="<?= site_url('setup/useropname/new') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Data</a>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped table-md display nowrap compact eureeka-table" id="myTable">
            <thead>
              <tr class="eureeka-table-header">
                <th>No</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <!-- TEMPAT FOREACH -->
              <?php foreach ($dtsetupuser as $key => $value) : ?>
                <tr>
                  <td><?= $key + 1 ?></td>
                  <td><?= $value->kode_user ?></td>
                  <td><?= $value->nama_user ?></td>
                  <td><span class="badge badge-<?= ($value->nonaktif == 0) ? 'info' : 'warning' ?>"><?= ($value->nonaktif == 0) ? 'Aktif' : 'Nonaktif' ?></span></td>

                  <td class="text-center">
                    <!-- Tombol Edit Data -->
                    <a href="<?= site_url('setup/useropname/' . $value->id_user) .  '/edit' ?>" class="btn btn-warning"><i class="fas fa-pencil-alt btn-small"></i> Edit</a>
                    <input type="hidden" name="_method" value="PUT">
                    <!-- Tombol Hapus Data -->
                    <form action="<?= site_url('setup/useropname/' . $value->id_user) ?>" method="post" id="del-<?= $value->id_user ?>" class="d-inline">
                      <?= csrf_field() ?>
                      <input type="hidden" name="_method" value="DELETE">
                      <button class="btn btn-danger btn-small" data-confirm="Hapus Data....?" data-confirm-yes="hapus(<?= $value->id_user ?>)"><i class="fas fa-trash"></i></button>
                    </form>
                  </td>
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