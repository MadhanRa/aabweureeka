<?= $this->extend("layout/backend") ?>;

<?= $this->section("content") ?>
<title>Akuntansi Eureeka - Setup Harga</title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <h1>Harga Jual/Beli</h1>
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
        <h4>Setup Harga Jual/Beli</h4>
        <div class="card-header-action">
          <a href="<?= site_url('setup_persediaan/harga/new') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Data</a>
        </div>
      </div>
      <div class="card-body">
        <!-- Filter Kelompok -->
        <div class="row mb-3">
          <div class="col-md-4">
            <form action="<?= site_url('setup_persediaan/harga') ?>" method="get" class="form-inline">
              <select name="search_kelompok" class="form-control">
                <option value="" hidden>-- Pilih Kelompok --</option>
                <?php foreach ($dtkelompok as $key => $value) : ?>
                  <option value="<?= $value->id_kelompok ?>" <?= ($searchKelompok == $value->id_kelompok) ? 'selected' : '' ?>>
                    <?= $value->nama_kelompok ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <button class="btn btn-primary ml-3" type="submit">
                <i class="fas fa-search"></i> Cari
              </button>
              <?php if ($searchKelompok) : ?>
                <a href="<?= site_url('setup_persediaan/harga') ?>" class="btn btn-light ml-2">
                  <i class="fas fa-undo"></i> Reset
                </a>
              <?php endif; ?>
            </form>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-striped eureeka-table" id="myTable">
            <thead>
              <tr class="eureeka-table-header">
                <th>Kode</th>
                <th>Nama</th>
                <th>Harga Jual (EXC)</th>
                <th>Harga Jual (INC)</th>
                <th>Harga Beli</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($dtharga as $key => $value) : ?>
                <tr>
                  <td><?= $value->kode ?></td>
                  <td><?= $value->nama_barang ?></td>
                  <td>Rp <?= number_format($value->harga_jualexc, 0, ',', '.') ?></td>
                  <td>Rp <?= number_format($value->harga_jualinc, 0, ',', '.') ?></td>
                  <td>Rp <?= number_format($value->harga_beli, 0, ',', '.') ?></td>

                  <td class="text-center">
                    <!-- Tombol Edit Data -->
                    <a href="<?= site_url('setup_persediaan/harga/' . $value->id_harga) .  '/edit' ?>" class="btn btn-warning mr-1"><i class="fas fa-pencil-alt"></i> Edit</a>
                    <input type="hidden" name="_method" value="PUT">
                    <!-- Tombol Hapus Data -->
                    <form action="<?= site_url('setup_persediaan/harga/' . $value->id_harga) ?>" method="post" id="del-<?= $value->id_harga ?>" class="d-inline">
                      <?= csrf_field() ?>
                      <input type="hidden" name="_method" value="DELETE">
                      <button class="btn btn-danger" data-confirm="Hapus Data....?" data-confirm-yes="hapus(<?= $value->id_harga ?>)"><i class="fas fa-trash"></i> Hapus</button>
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
          targets: 5,
          orderable: false,
          searchable: false
        },
        {
          targets: 1,
          className: 'font-weight-bold',
        }
      ],
    });
  });
</script>

<?= $this->endSection(); ?>