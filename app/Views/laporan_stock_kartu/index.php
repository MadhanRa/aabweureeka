<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>
<title>Akuntansi Eureeka &mdash; Laporan Karu Stock</title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <h1>Kartu Stock</h1>
  </div>

  <div class="card">
    <div class="card-header">
      <div class="card-header-action">
        <a href="<?= base_url('laporankartustock/printPDF?tglawal=' . $tglawal . '&tglakhir=' . $tglakhir . '&lokasi=' . $lokasi . '&stock=' . $stock) ?>" class="btn btn-success" target="_blank">
          <i class="fas fa-print"></i> Cetak PDF
        </a>
      </div>
    </div>
    <div class="card-body">
      <form method="GET" action="<?= base_url('laporankartustock') ?>">
        <div class="row">
          <div class="col-md-3">
            <label for="tglawal">Tanggal Awal</label>
            <input type="date" name="tglawal" class="form-control" value="<?= $tglawal ?>">
          </div>
          <div class="col-md-3">
            <label for="tglakhir">Tanggal Akhir</label>
            <input type="date" name="tglakhir" class="form-control" value="<?= $tglakhir ?>">
          </div>
          <div class="col-md-3">
            <label for="lokasi">Lokasi</label>
            <select name="lokasi" class="form-control">
              <option value="">Semua Lokasi</option>
              <?php foreach ($dtlokasi as $lok): ?>
                <option value="<?= $lok->id_lokasi ?>" <?= $lok->id_lokasi == $lokasi ? 'selected' : '' ?>><?= $lok->nama_lokasi ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-md-10">
            <div class="form-row align-items-center mt-3">
              <div class="col-auto">
                <label class="sr-only" for="kode_stock">Kode Stock</label>
                <input type="text" class="form-control" id="kode_stock" name="kode_stock" placeholder="Kode" value="<?= $kode_stock ?>" readonly>
                <input type="text" class="form-control" name="id_stock" hidden value="<?= $stock ?>">
              </div>
              <div class="col-auto">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCariStock">cari</button>
              </div>
              <div class="col-auto">
                <label class="sr-only" for="nama_stock">Nama Barang</label>
                <input type="text" class="form-control" id="nama_stock" value="<?= $nama_stock ?>" name="nama_stock" readonly>
              </div>
              <div class="col-auto">
                <label for="isi_stock" class="sr-only">Isi</label>
                <input type="text" class="form-control" id="isi_stock" name="isi_stock" value="<?= $isi_stock ?>" readonly>
              </div>
              <div class="col-auto ml-5">
                <button type="submit" class="btn btn-primary">Filter</button>
                <!-- tombol reset -->
                <a href="<?= base_url('laporankartustock') ?>" class="btn btn-secondary">Reset</a>
              </div>
            </div>
          </div>
        </div>
      </form>

      <div class="table-responsive mt-5">
        <table class="table table-striped table-md display nowrap compact eureeka-table" id="myTable">
          <thead>
            <tr class="eureeka-table-header">
              <th>No</th>
              <th>Tanggal</th>
              <th>Nota</th>
              <th>Keterangan</th>
              <th>Masuk (Q)</th>
              <th>Masuk (Rp)</th>
              <th>Keluar (Q)</th>
              <th>Keluar (Rp)</th>
              <th>Saldo (Q)</th>
              <th>Saldo (Rp)</th>
              <th>Rata2(Rp)</th>
            </tr>
          </thead>
          <tbody>
            <!-- Iterasi Data -->
            <?php foreach ($dtkartu_mutasi as $key => $value) : ?>
              <tr>
                <td><?= $key + 1 ?></td>
                <td><?= $value['tanggal'] ?></td>
                <td><?= $value['nota'] ?></td>
                <td><?= $value['sumber_transaksi'] ?></td>
                <td><?= $value['masuk_q'] ?></td>
                <td><?= "Rp " . number_format($value['masuk_r'], 0, ',', '.') ?></td>
                <td><?= $value['keluar_q'] ?></td>
                <td><?= "Rp " . number_format($value['keluar_r'], 0, ',', '.') ?></td>
                <td><?= $value['saldo_q'] ?></td>
                <td><?= "Rp " . number_format($value['saldo_r'], 0, ',', '.') ?></td>
                <td><?= "Rp " . number_format($value['rata_rata'], 0, ',', '.') ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="row mt-3">
        <div class="col">
          <label>Masuk (Qty)</label>
          <input class="form-control" type="text" value="<?= $masuk_total_q ?>" readonly>
        </div>
        <div class="col">
          <label>Masuk (Rp)</label>
          <input class="form-control" type="text" value="<?= "Rp " . number_format($masuk_total_r, 0, ',', '.') ?>" readonly>
        </div>
        <div class="col">
          <label>Keluar (Qty)</label>
          <input class="form-control" type="text" value="<?= $keluar_total_q ?>" readonly>
        </div>
        <div class="col">
          <label>Keluar (Rp)</label>
          <input class="form-control" type="text" value="<?= "Rp " . number_format($keluar_total_r, 0, ',', '.') ?>" readonly>
        </div>
      </div>
    </div>
  </div>
  </div>
  </div>
</section>

<!-- Tempat modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="modalCariStock" data-stock-url="<?= site_url('laporankartustock/cari-stock/') ?>">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Cari Stock</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="table-responsive table-wrapper-medium">
          <table class="table table-striped table-md" id="myTable">
            <thead>
              <tr class="eureeka-table-header">
                <th>No</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Satuan</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="tabelCariStock">
              <?php foreach ($dtstock as $key => $value) : ?>
                <tr>
                  <td><?= $key + 1 ?></td>
                  <td><?= $value->kode ?></td>
                  <td><?= $value->nama_barang ?></td>
                  <td><?= $value->kode_satuan . '/' . $value->kode_satuan2 ?></td>
                  <td><button class="btn btn-primary" id="btn_cari_<?= $value->id_stock ?>" onclick="pilihStock(<?= $value->id_stock ?>)">Pilih</button></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer bg-whitesmoke br">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('pageScript') ?>
<script src="<?= base_url('assets/js/views/laporan/kartu_stock/kartuStock.js') ?>"></script>
<?= $this->endSection(); ?>