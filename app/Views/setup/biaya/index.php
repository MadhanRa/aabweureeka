<?= $this->extend("layout/backend") ?>;

<?= $this->section("content") ?>
<title>Akuntansi Eureeka &mdash; Setup Biaya </title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <h1>Setup Biaya</h1>
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
        <h4>Setup Klasifikasi Biaya dan Pendapatan</h4>
        <div class="card-header-action">
          <a href="<?= site_url('setup/biaya/new') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Data</a>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped table-md display nowrap compact eureeka-table" id="myTable">
            <thead>
              <tr class="eureeka-table-header">
                <th>No</th>
                <th>Rekening</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <!-- TEMPAT FOREACH -->
              <?php foreach ($dtsetupbiaya as $key => $value) : ?>
                <tr>
                  <td><?= $key + 1 ?></td>
                  <td><?= $value->nama_setupbuku ?></td>
                  <td><?= $value->kode_setupbiaya ?></td>
                  <td><?= $value->nama_setupbiaya ?></td>


                  <td class="text-center">
                    <!-- Tombol Edit Data -->
                    <a href="<?= site_url('setup/biaya/' . $value->id_setupbiaya) .  '/edit' ?>" class="btn btn-warning"><i class="fas fa-pencil-alt btn-small"></i> Edit</a>
                    <input type="hidden" name="_method" value="PUT">
                    <!-- Tombol Hapus Data -->
                    <form action="<?= site_url('setup/biaya/' . $value->id_setupbiaya) ?>" method="post" id="del-<?= $value->id_setupbiaya ?>" class="d-inline">
                      <?= csrf_field() ?>
                      <input type="hidden" name="_method" value="DELETE">
                      <button class="btn btn-danger btn-small" data-confirm="Hapus Data....?" data-confirm-yes="hapus(<?= $value->id_setupbiaya ?>)"><i class="fas fa-trash"></i></button>
                    </form>
                  </td>
                  <!-- <td><a href="#" class="btn btn-secondary">Detail</a></td> -->
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
        targets: 4,
        orderable: false,
        searchable: false
      }],
    });
  });
</script>

<?= $this->endSection(); ?>