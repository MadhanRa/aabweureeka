<?= $this->extend("layout/backend") ?>;

<?= $this->section("content") ?>
<title>Akuntansi Eureeka &mdash; Setup Kelompok Produksi </title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <h1>Kelompok Produksi</h1>
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
        <h4>Setup Kelompok Produksi</h4>
        <div class="card-header-action">
          <a href="<?= site_url('setup/kelompokproduksi/new') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Data</a>
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
                <th>Rekening</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <!-- TEMPAT FOREACH -->
              <?php foreach ($dtkelompokproduksi as $key => $value) : ?>
                <tr>
                  <td><?= $key + 1 ?></td>
                  <td><?= $value->kode_kelproduksi ?></td>
                  <td><?= $value->nama_kelproduksi ?></td>
                  <td><?= $value->nama_setupbuku ?></td>

                  <td class="text-center">
                    <!-- Tombol Edit Data -->
                    <a href="<?= site_url('setup/kelompokproduksi/' . $value->id_kelproduksi) .  '/edit' ?>" class="btn btn-warning"><i class="fas fa-pencil-alt btn-small"></i> Edit</a>
                    <input type="hidden" name="_method" value="PUT">
                    <!-- Tombol Hapus Data -->
                    <form action="<?= site_url('setup/kelompokproduksi/' . $value->id_kelproduksi) ?>" method="post" id="del-<?= $value->id_kelproduksi ?>" class="d-inline">
                      <?= csrf_field() ?>
                      <input type="hidden" name="_method" value="DELETE">
                      <button class="btn btn-danger btn-small" data-confirm="Hapus Data....?" data-confirm-yes="hapus(<?= $value->id_kelproduksi ?>)"><i class="fas fa-trash"></i> Hapus</button>
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

  </div>
</section>

<?= $this->endSection(); ?>