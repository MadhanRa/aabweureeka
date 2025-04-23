<?= $this->extend("/layout/backend") ?>;

<?= $this->section("content") ?>
<title>Akuntansi Eureeka &mdash; Setup Interface</title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <h1>Interface</h1>
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
        <div class="card-header-action">
          <!-- // Kalau belum ada data interface, tampilkan tombol tambah data -->
          <?php if (empty($dtantarmuka)) : ?>
            <a href="<?= site_url('setup/antarmuka/new') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Data</a>
          <?php else : ?>
            <a href="<?= site_url('setup/antarmuka/') . $dtantarmuka[0]->id_interface . '/edit' ?>" class="btn btn-warning"><i class="fas fa-plus"></i> Edit Data</a>
          <?php endif; ?>
        </div>
      </div>
      <div class="card-body">
        <?php if (!empty($dtantarmuka)) : ?>
          <div class="row">
            <div class="col-lg-6">
              <table class="table w-auto">
                <tr>
                  <td><strong>Kas</strong></td>
                  <td>:</td>
                  <td><?= $dtantarmuka[0]->kas_interface ?></td>
                </tr>
                <tr>
                  <td><strong>Biaya</strong></td>
                  <td>:</td>
                  <td><?= $dtantarmuka[0]->biaya ?></td>
                </tr>
                <tr>
                  <td><strong>Hutang Dagang</strong></td>
                  <td>:</td>
                  <td><?= $dtantarmuka[0]->hutang ?></td>
                </tr>
                <tr>
                  <td><strong>HPP</strong></td>
                  <td>:</td>
                  <td><?= $dtantarmuka[0]->hpp ?></td>
                </tr>
                <tr>
                  <td><strong>BG Terima Mundur</strong></td>
                  <td>:</td>
                  <td><?= $dtantarmuka[0]->terima_mundur ?></td>
                </tr>
                <tr>
                  <td><strong>Klasifikasi Laba Ditahan</strong></td>
                  <td>:</td>
                  <td><?= $dtantarmuka[0]->laba_ditahan ?></td>
                </tr>
                <tr>
                  <td><strong>Klasifikasi Hutang Lancar</strong></td>
                  <td>:</td>
                  <td><?= $dtantarmuka[0]->hutang_lancar ?></td>
                </tr>
                <tr>
                  <td><strong>Neraca Laba Bulan Berjalan</strong></td>
                  <td>:</td>
                  <td><?= $dtantarmuka[0]->neraca_laba ?></td>
                </tr>
                <tr>
                  <td><strong>Piutang Salesman</strong></td>
                  <td>:</td>
                  <td><?= $dtantarmuka[0]->piutang_salesman ?></td>
                </tr>
                <tr>
                  <td><strong>Rek. Biaya Produksi</strong></td>
                  <td>:</td>
                  <td><?= $dtantarmuka[0]->rekening_biaya ?></td>
                </tr>
              </table>
            </div>
            <div class="col-lg-6">
              <table class="table w-auto">
                <tr>
                  <td><strong>Piutang Dagang</strong></td>
                  <td>:</td>
                  <td><?= $dtantarmuka[0]->piutang_dagang ?></td>
                </tr>
                <tr>
                  <td><strong>Penjualan</strong></td>
                  <td>:</td>
                  <td><?= $dtantarmuka[0]->penjualan ?></td>
                </tr>
                <tr>
                  <td><strong>Retur Penjualan</strong></td>
                  <td>:</td>
                  <td><?= $dtantarmuka[0]->retur_penjualan ?></td>
                </tr>
                <tr>
                  <td><strong>Disc. Penjualan</strong></td>
                  <td>:</td>
                  <td><?= $dtantarmuka[0]->diskon_penjualan ?></td>
                </tr>
                <tr>
                  <td><strong>Laba Bulan Berjalan</strong></td>
                  <td>:</td>
                  <td><?= $dtantarmuka[0]->laba_bulan ?></td>
                </tr>
                <tr>
                  <td><strong>Laba Ditahan</strong></td>
                  <td>:</td>
                  <td><?= $dtantarmuka[0]->laba_tahun ?></td>
                </tr>
                <tr>
                  <td><strong>Potongan Pembelian</strong></td>
                  <td>:</td>
                  <td><?= $dtantarmuka[0]->potongan_pembelian ?></td>
                </tr>

                <tr>
                  <td><strong>PPN Masukan</strong></td>
                  <td>:</td>
                  <td><?= $dtantarmuka[0]->ppn_masukan ?></td>
                </tr>

                <tr>
                  <td><strong>PPN Keluaran</strong></td>
                  <td>:</td>
                  <td><?= $dtantarmuka[0]->ppn_keluaran ?></td>
                </tr>
                <tr>
                  <td><strong>Potongan Penjualan</strong></td>
                  <td>:</td>
                  <td><?= $dtantarmuka[0]->potongan_penjualan ?></td>
                </tr>
                <tr>
                  <td><strong>Bank</strong></td>
                  <td>:</td>
                  <td><?= $dtantarmuka[0]->bank ?></td>
                </tr>
              </table>
            </div>
          </div>
        <?php else : ?>
          <div class="alert alert-info">
            <i class="fas fa-info-circle mr-2"></i> Belum ada data interface yang tersedia. Silahkan tambahkan data terlebih dahulu.
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<?= $this->endSection(); ?>