<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>
<title>Akuntansi Eureeka &mdash; Laporan Pembelian</title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <h1>Laporan Pembelian</h1>
  </div>

  <!-- Tombol Print All -->
  <div class="section-body">
    <div class="card-body">
      <div class="row">
        <div class="col">
          <a href="<?= base_url('LaporanKasKecil/printPDF?tglawal=' . ($filter['tglawal'] ?? '') . '&tglakhir=' . ($filter['tglakhir'] ?? '') . '&rekeningkas=' . ($filter['rekeningkas'] ?? '') . '&kelproduksi=' . ($filter['kelproduksi'] ?? '')) ?>" class="btn btn-success" target="_blank">
            <i class="fas fa-print"></i> Cetak PDF
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Menampilkan Pesan Sukses -->
  <?php if (session()->getFlashdata('Sukses')) : ?>
    <div class="alert alert-success alert-dismissible fade show">
      <button class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      <?= esc(session()->getFlashdata('Sukses')) ?>
    </div>
  <?php endif; ?>

  <!-- Filter Form -->
  <div class="section-body">
    <div class="card">
      <div class="card-body">
        <form action="<?= site_url('laporankaskecil') ?>" method="POST">
          <?= csrf_field() ?>
          <div class="row">
            <div class="col-md-3">
              <input type="date" class="form-control" name="tglawal" value="<?= esc($filter['tglawal'] ?? '') ?>">
            </div>
            <div class="col-md-3">
              <input type="date" class="form-control" name="tglakhir" value="<?= esc($filter['tglakhir'] ?? '') ?>">
            </div>
            <div class="col-md-3">
              <select name="rekeningkas" class="form-control">
                <option value="">-- Semua Rekening Kas --</option>
                <?php foreach ($dataRekeningKas as $kas) : ?>
                  <option value="<?= esc($kas->id_interface) ?>" <?= isset($filter['rekeningkas']) && $filter['rekeningkas'] == $kas->id_interface ? 'selected' : '' ?>>
                    <?= esc($kas->kas_interface) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-3">
              <select name="kelproduksi" class="form-control">
                <option value="">-- Semua Kelompok Produksi --</option>
                <?php foreach ($dataKelompokProduksi as $kel) : ?>
                  <option value="<?= esc($kel->id_kelproduksi) ?>" <?= isset($filter['kelproduksi']) && $filter['kelproduksi'] == $kel->id_kelproduksi ? 'selected' : '' ?>>
                    <?= esc($kel->nama_kelproduksi) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-3">
              <button type="submit" class="btn btn-primary">Filter</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>


  <!-- Tabel Data -->
  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped table-md display nowrap compact eureeka-table" id="myTable">
          <thead>
            <tr class="eureeka-table-header">
              <th>No</th>
              <th>Tanggal</th>
              <th>Nota</th>
              <th>Kas dan Setara Kas</th>
              <th>Kelompok Produksi</th>
              <th>Rekening</th>
              <th>B.Pembantu</th>
              <th>Nama Rekening</th>
              <th>Nama Buku Pembantu</th>
              <th>Mutasi</th>
              <th>Keterangan</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($dtkaskecil)) : ?>
              <?php foreach ($dtkaskecil as $key => $value) : ?>
                <tr>
                  <td><?= $key + 1 ?></td>
                  <td><?= $value->tanggal ?></td>
                  <td><?= $value->nota ?></td>
                  <td><?= $value->kas_interface ?></td>
                  <td><?= $value->nama_kelproduksi ?></td>
                  <td><?= $value->rekening ?></td>
                  <td><?= $value->b_pembantu ?></td>
                  <td><?= $value->nama_rekening ?></td>
                  <td><?= $value->nama_bpembantu ?></td>
                  <td><?= "Rp " . number_format($value->rp, 0, ',', '.') ?></td>
                  <td><?= $value->keterangan ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else : ?>
              <tr>
                <td colspan="13" class="text-center">Data tidak ditemukan.</td>
              </tr>
            <?php endif; ?>
          </tbody>
          <tfoot>
            <tr>
              <th colspan="10" class="text-right">Total Mutasi:</th>
              <th><?= "Rp " . number_format($totalMutasi ?? 0, 0, ',', '.') ?></th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
  </div>
</section>

<?= $this->endSection(); ?>