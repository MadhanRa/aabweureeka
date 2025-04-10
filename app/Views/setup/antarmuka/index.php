<?= $this->extend("/layout/backend") ?>;

<?= $this->section("content") ?>
<title>Akuntansi Eureeka &mdash; Setup Interface</title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <!-- <h1>APA INI</h1> -->
    <a href="<?= site_url('antarmuka/new') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Data</a>
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
        <h4>Setup Interface</h4>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped table-md display nowrap compact eureeka-table" id="myTable">
            <thead>
              <tr class="eureeka-table-header">
                <th>No</th>
                <th>Kas</th>
                <th>Biaya</th>
                <th>Hutang Dagang</th>
                <th>HPP</th>
                <th>BG Terima Mundur</th>
                <th>Klasifikasi Laba Ditahan</th>
                <th>Klasifikasi Hutang Lancar</th>
                <th>Neraca Laba Bulan Berjalan</th>
                <th>Piutang Salesman</th>
                <th>Rek. Biaya Produksi</th>
                <th>Piutang Dagang</th>
                <th>Penjualan</th>
                <th>Retur Penjualan</th>
                <th>Disc. Penjualan</th>
                <th>Laba Bulan Berjalan</th>
                <th>Laba Ditahan</th>
                <th>Potongan Pembelian</th>
                <th>PPN Masukan</th>
                <th>PPN Keluaran</th>
                <th>Potongan Pembelian</th>
                <th>Bank</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($dtantarmuka as $key => $value) : ?>
                <tr>
                  <td><?= $key + 1 ?></td>
                  <td><?= $value->kas_interface ?></td>
                  <td><?= $value->biaya ?></td>
                  <td><?= $value->hutang ?></td>
                  <td><?= $value->hpp ?></td>
                  <td><?= $value->terima_mundur ?></td>
                  <td><?= $value->laba_ditahan ?></td>
                  <td><?= $value->hutang_lancar ?></td>
                  <td><?= $value->neraca_laba ?></td>
                  <td><?= $value->piutang_salesman ?></td>
                  <td><?= $value->rekening_biaya ?></td>
                  <td><?= $value->piutang_dagang ?></td>
                  <td><?= $value->penjualan ?></td>
                  <td><?= $value->retur_penjualan ?></td>
                  <td><?= $value->diskon_penjualan ?></td>
                  <td><?= $value->laba_bulan ?></td>
                  <td><?= $value->laba_tahun ?></td>
                  <td><?= $value->potongan_pembelian ?></td>
                  <td><?= $value->ppn_masukan ?></td>
                  <td><?= $value->ppn_keluaran ?></td>
                  <td><?= $value->potongan_penjualan ?></td>
                  <td><?= $value->bank ?></td>

                  <td class="text-center">
                    <!-- Tombol Edit Data -->

                    <!-- Tombol Hapus Data -->
                    <form action="<?= site_url('antarmuka/' . $value->id_interface) ?>" method="post" id="del-<?= $value->id_interface ?>" class="d-inline">
                      <?= csrf_field() ?>
                      <input type="hidden" name="_method" value="DELETE">
                      <button class="btn btn-danger btn-small" data-confirm="Hapus Data....?" data-confirm-yes="hapus(<?= $value->id_interface ?>)"><i class="fas fa-trash"></i></button>
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