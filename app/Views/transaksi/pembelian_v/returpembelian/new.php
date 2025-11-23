<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <!-- <h1>Pembelian</h1> -->
    <a href="<?= site_url('transaksi/pembelian/returpembelian') ?>" class="btn btn-primary">
      <i class="fas fa-arrow-left"></i> Kembali
    </a>
  </div>

  <div class="section-body">
    <!-- HALAMAN DINAMIS -->
    <div class="card">
      <div class="card-header">
        <h4>Return Pembelian</h4>
        <div class="card-header-action">
          <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalLookupReturPembelian">Lookup</button>
        </div>
      </div>
      <div class="card-body">
        <?= $this->include('transaksi/pembelian_v/returpembelian/_form') ?>
      </div>
    </div>
  </div>

</section>

<?= $this->include('transaksi/pembelian_v/returpembelian/_modals') ?>

<?= $this->endSection(); ?>

<?= $this->section('pageScript') ?>
<script src="<?= base_url('assets/js/views/transaksi/pembelian/returpembelian.js') ?>"></script>
<?= $this->endSection(); ?>