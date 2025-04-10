<?= $this->extend("layout/backend") ?>;

<?= $this->section("content") ?>
<title>Akuntansi Eureeka &mdash; Setup Stock </title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <!-- <h1>APA INI</h1> -->
    <h1>Stock</h1>
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
        <h4>Setup Stock</h4>
        <div class="card-header-action">
          <a href="javascript:void(0)" class="btn btn-primary" onclick="tambah_data()"><i class="fas fa-plus"></i> Tambah Data</a>
          <a href="javascript:void(0)" class="btn btn-warning" onclick="reload_table()"><i class="fas fa-redo-alt"></i> Refresh Data</a>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive" id="view-table">

        </div>
      </div>
    </div>
  </div>
</section>
<div id="modalPlace" style="display: none;"></div>

<script>
  function reload_table() {
    $.ajax({
      url: "<?= site_url('setup_persediaan/stock/getStock') ?>",
      type: "GET",
      success: function(response) {
        $('#view-table').html(response.data);
      }
    });
  }

  $(document).ready(function() {
    reload_table();
  });

  function tambah_data() {
    $.ajax({
      url: "<?= site_url('setup_persediaan/stock/new') ?>",
      type: "GET",
      dataType: "json",
      success: function(response) {
        $('#modalPlace').html(response.data);
        $('#modalPlace').show();
        $('#modalTambah').modal('show');
      }
    });
  }
</script>
<?= $this->endSection(); ?>