<?= $this->extend("layout/backend") ?>;

<?= $this->section("content") ?>
<title>Akuntansi Eureeka &mdash; Setup Salesman </title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <h1>Salesman</h1>
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
    <div id="main-content">
      <div class="card">
        <div class="card-header">
          <h4>Setup Salesman</h4>
          <div class="card-header-action">
            <a href="<?= site_url('setup/salesman/new') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Data</a>
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
                  <th>Lokasi</th>
                  <th>Saldo</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <!-- TEMPAT FOREACH -->
                <?php foreach ($dtsetupsalesman as $key => $value) : ?>
                  <tr>
                    <td><?= $key + 1 ?></td>
                    <td><?= $value->kode_salesman ?></td>
                    <td><?= $value->nama_salesman ?></td>
                    <td><?= $value->nama_lokasi ?></td>
                    <td><?= 'Rp ' . number_format($value->saldo, 0, ',', '.') ?></td>

                    <td class="text-center">
                      <!-- Tombol Detail Data -->
                      <a href="javascript:void(0)" onclick="lihat_data(<?= $value->id_salesman ?>)"
                        id="btn-detail-<?= $value->id_salesman ?>"
                        class="btn btn-info"><i class="fas fa-eye"></i> Detail</a>

                      <!-- Tombol Edit Data -->
                      <a href="<?= site_url('setup/salesman/' . $value->id_salesman) .  '/edit' ?>" class="btn btn-warning"><i class="fas fa-pencil-alt btn-small"></i> Edit</a>
                      <input type="hidden" name="_method" value="PUT">

                      <!-- Tombol Hapus Data -->
                      <form action="<?= site_url('setup/salesman/' . $value->id_salesman) ?>" method="post" id="del-<?= $value->id_salesman ?>" class="d-inline">
                        <?= csrf_field() ?>
                        <input type="hidden" name="_method" value="DELETE">
                        <button class="btn btn-danger btn-small" data-confirm="Hapus Data....?" data-confirm-yes="hapus(<?= $value->id_salesman ?>)"><i class="fas fa-trash"></i> Hapus</button>
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
    <div id="detail-content">

    </div>
  </div>
</section>
<div id="modalPlace" style="display: none;"></div>

<script>
  $(document).ready(function() {
    $('#myTable').DataTable({
      columnDefs: [{
        targets: 5,
        orderable: false,
        searchable: false
      }],
    });
  });

  function lihat_data(id) {
    $.ajax({
      url: "<?= site_url('setup/salesman/') ?>" + id,
      type: "GET",
      dataType: "json",
      beforeSend: function() {
        $('#btn-detail-' + id).addClass('disabled');
        $('#btn-detail-' + id).addClass('btn-progress');
      },
      complete: function() {
        $('#btn-detail-' + id).removeClass('disabled');
        $('#btn-detail-' + id).removeClass('btn-progress');
      },
      success: function(response) {
        $('#detail-content').html(response.data);
      }
    });
  }
</script>

<?= $this->endSection(); ?>