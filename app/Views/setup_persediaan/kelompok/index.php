<?= $this->extend("layout/backend") ?>;

<?= $this->section("content") ?>
<title>Akuntansi Eureeka &mdash; Setup Kelompok</title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <h1>Kelompok</h1>
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
        <h4>Setup Kelompok</h4>
        <div class="card-header-action">
          <a href="<?= site_url('setup_persediaan/kelompok/new') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Data</a>

        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped table-md eureeka-table" id="myTable">
            <thead>
              <tr class="eureeka-table-header">
                <th>No</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($dtkelompok as $key => $value) : ?>
                <tr>
                  <td><?= $key + 1 ?></td>
                  <td><?= $value->kode_kelompok ?></td>
                  <td><?= $value->nama_kelompok ?></td>

                  <td class="text-center">
                    <!-- Tombol Edit Data -->
                    <a href="<?= site_url('setup_persediaan/kelompok/' . $value->id_kelompok) .  '/edit' ?>" class="btn btn-warning mr-1"><i class="fas fa-pencil-alt"></i> Edit</a>
                    <input type="hidden" name="_method" value="PUT">
                    <!-- Tombol Hapus Data -->
                    <form action="<?= site_url('setup_persediaan/kelompok/' . $value->id_kelompok) ?>" method="post" id="del-<?= $value->id_kelompok ?>" class="d-inline">
                      <?= csrf_field() ?>
                      <input type="hidden" name="_method" value="DELETE">
                      <button class="btn btn-danger btn-small" data-confirm="Hapus Data....?" data-confirm-yes="hapus(<?= $value->id_kelompok ?>)"><i class="fas fa-trash"></i> Hapus</button>
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

<script>
  $(document).ready(function() {
    $('#myTable').DataTable({
      columnDefs: [{
          targets: 3,
          orderable: false,
          searchable: false
        },
        {
          targets: 2,
          className: 'font-weight-bold',
        }
      ],
    });
  });
</script>

<?= $this->endSection(); ?>