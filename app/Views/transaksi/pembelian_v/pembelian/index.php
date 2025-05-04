<?= $this->extend("layout/backend") ?>;

<?= $this->section("content") ?>
<title>Akuntansi Eureeka &mdash; Transaksi Pembelian</title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <h1>Dashboard Pembelian</h1>
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
        <h4>Transaksi Pembelian</h4>
        <div class="card-header-action">
          <a href="<?= base_url('Pembelian/printPDF') ?>" class="btn btn-success" target="_blank">
            <i class="fas fa-print"></i> Cetak PDF
          </a>
          <a href="<?= site_url('transaksi/pembelian/pembelian/new') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Data</a>
        </div>
      </div>
      <div class="section-body">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped table-md eureeka-table" id="myTable">
              <thead>
                <tr class="eureeka-table-header">
                  <th>No</th>
                  <th>Tanggal</th>
                  <th>Nota</th>
                  <th>Supplier</th>
                  <th>TOP</th>
                  <th>Tgl. Jatuh Tempo</th>
                  <th>Tgl. Invoice</th>
                  <th>No. Invoice</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <!-- TEMPAT FOREACH -->
                <?php foreach ($dtpembelian as $key => $value) : ?>
                  <tr>
                    <td><?= $key + 1 ?></td>
                    <td><?= $value->tanggal ?></td>
                    <td><?= $value->nota ?></td>
                    <td><?= $value->nama_supplier ?></td>
                    <td><?= $value->TOP ?></td>
                    <td><?= $value->tgl_jatuhtempo ?></td>
                    <td><?= $value->tgl_invoice ?></td>
                    <td><?= $value->no_invoice ?></td>
                    <td class="text-center">
                      <?php if ($is_closed === 'TRUE'): ?>
                        <button class="btn btn-warning btn-read">
                          <i class="lock-icon fas fa-lock"></i> Edit
                        </button>
                        <button class="btn btn-danger btn-read">
                          <i class="lock-icon fas fa-lock"></i> Delete
                        </button>
                      <?php else: ?>
                        <!-- Tombol Edit Data -->
                        <a href="<?= site_url('transaksi/pembelian/pembelian/' . $value->id_pembelian) .  '/edit' ?>" class="btn btn-warning btn-small"><i class="fas fa-pencil-alt btn-small"></i> Edit</a>
                        <input type="hidden" name="_method" value="PUT">
                        <!-- Tombol Hapus Data -->
                        <form action="<?= site_url('transaksi/pembelian/pembelian/' . $value->id_pembelian) ?>" method="post" id="del-<?= $value->id_pembelian ?>" class="d-inline">
                          <?= csrf_field() ?>
                          <input type="hidden" name="_method" value="DELETE">
                          <button class="btn btn-danger btn-small" data-confirm="Hapus Data....?" data-confirm-yes="hapus(<?= $value->id_pembelian ?>)">
                            <i class="fas fa-trash"></i>
                          </button>
                        </form>
                      <?php endif ?>

                      <!-- Tombol Print Data -->
                      <a href="<?= base_url('Pembelian/printPDF/' . $value->id_pembelian) ?>" class="btn btn-success btn-small" target="_blank">
                        <i class="fas fa-print"></i>
                      </a>
                    </td>
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

<script>
  $(document).ready(function() {
    $('#myTable').DataTable({
      columnDefs: [{
          targets: 8,
          orderable: false,
          searchable: false
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