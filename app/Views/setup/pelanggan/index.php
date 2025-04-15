<?= $this->extend("layout/backend") ?>;

<?= $this->section("content") ?>
<title>Akuntansi Eureeka &mdash; Setup Pelanggan </title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <h1>Pelanggan</h1>
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
        <h4>Setup Pelanggan</h4>
        <div class="card-header-action">
          <a href="<?= site_url('setup/pelanggan/new') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Data</a>
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
                <th>Alamat</th>
                <th>Kota</th>
                <th>Telepon</th>
                <th>Saldo</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <!-- TEMPAT FOREACH -->
              <?php foreach ($dtsetuppelanggan as $key => $value) : ?>
                <tr>
                  <td><?= $key + 1 ?></td>
                  <td><?= $value->kode_pelanggan ?></td>
                  <td><?= $value->nama_pelanggan ?></td>
                  <td><?= $value->alamat_pelanggan ?></td>
                  <td><?= $value->kota_pelanggan ?></td>
                  <td><?= $value->telp_pelanggan ?></td>
                  <td class="saldo-detail-<?= $value->id_pelanggan ?>"><?= 'Rp ' . number_format($value->saldo, 0, ',', '.') ?></td>

                  <td class="text-center">

                    <!-- Tombol Detail -->
                    <a href="javascript:void(0)" onclick="lihat_data(<?= $value->id_pelanggan ?>)"
                      id="btn-detail-<?= $value->id_pelanggan ?>"
                      class="btn btn-info"><i class="fas fa-eye"></i> Detail</a>

                    <!-- Tombol Edit Data -->
                    <a href="<?= site_url('setup/pelanggan/' . $value->id_pelanggan) .  '/edit' ?>" class="btn btn-warning"><i class="fas fa-pencil-alt "></i> Edit</a>
                    <input type="hidden" name="_method" value="PUT">
                    <!-- Tombol Hapus Data -->
                    <form action="<?= site_url('setup/pelanggan/' . $value->id_pelanggan) ?>" method="post" id="del-<?= $value->id_pelanggan ?>" class="d-inline">
                      <?= csrf_field() ?>
                      <input type="hidden" name="_method" value="DELETE">
                      <button class="btn btn-danger" data-confirm="Hapus Data....?" data-confirm-yes="hapus(<?= $value->id_pelanggan ?>)"><i class="fas fa-trash"></i> Hapus</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div id="detail-content">

    </div>
  </div>
</section>
<div id="modalPlace" style="display: none;"></div>


<script>
  function lihat_data(id) {
    $.ajax({
      url: "<?= site_url('setup/pelanggan/') ?>" + id,
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
        if (response.success) {
          $('#detail-content').html(response.data);
        } else {
          alert(response.error);
        }
      }
    });
  }

  $(document).ready(function() {
    $('#myTable').DataTable({
      columnDefs: [{
          targets: 7,
          orderable: false,
          searchable: false,
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