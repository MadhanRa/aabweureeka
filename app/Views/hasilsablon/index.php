<?= $this->extend("layout/backend") ?>;

<?= $this->section("content") ?>
<title>Akuntansi Eureeka &mdash; Hasil Sablon</title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <!-- <h1>APA INI</h1> -->
    <a href="<?= site_url('hasilsablon/new') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Data</a>
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
        <h4>Hasil Sablon</h4>
      </div>
      <div class="section-body">
        <div class="card-body">
          <div class="table-responsive">
            <table border="2px" class="tablet able-striped table-md" id="myTable" style="border-color: #009548; border-width: 4px; border-style: solid;">
              <thead>
                <tr style="background-color: #009548; color: white;">
                  <th>No</th>
                  <th>Nota</th>
                  <th>Bahan</th>
                  <th>Lokasi</th>
                  <th>Supplier</th>
                  <th>Terpakai</th>
                  <th>Rusak</th>
                  <th>Nama Stock</th>
                  <th>Satuan</th>
                  <th>Qty 1</th>
                  <th>Qty 2</th>
                  <th>Jml.Harga</th>
                  <th>Tanggal</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <!-- TEMPAT FOREACH -->
                <?php foreach ($dthasilsablon as $key => $value) : ?>
                  <tr>
                    <td><?= $key + 1 ?></td>
                    <td><?= $value->nota_sablon ?></td>
                    <td><?= $value->bahan_sablon ?></td>
                    <td><?= $value->lokasi_asal ?></td>
                    <td><?= $value->nama ?></td>
                    <td><?= $value->terpakai ?></td>
                    <td><?= $value->rusak ?></td>
                    <td><?= $value->nama_stock ?></td>
                    <td><?= $value->kode_satuan ?></td>
                    <td><?= $value->qty_1 ?></td>
                    <td><?= $value->qty_2 ?></td>
                    <td><?= "Rp " . number_format($value->jml_harga, 0, ',', '.') ?></td>
                    <td><?= $value->tanggal ?></td>

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
                        <a href="<?= site_url('hasilsablon/' . $value->id_sablon) .  '/edit' ?>" class="btn btn-warning"><i class="fas fa-pencil-alt btn-small"></i> Edit</a>
                        <input type="hidden" name="_method" value="PUT">


                        <!-- Tombol Hapus Data -->
                        <form action="<?= site_url('hasilsablon/' . $value->id_sablon) ?>" method="post" id="del-<?= $value->id_sablon ?>" class="d-inline">
                          <?= csrf_field() ?>
                          <input type="hidden" name="_method" value="DELETE">
                          <button class="btn btn-danger btn-small" data-confirm="Hapus Data....?" data-confirm-yes="hapus(<?= $value->id_sablon ?>)">
                            <i class="fas fa-trash"></i>
                          </button>
                        </form>
                      <?php endif ?>

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
  // Fungsi untuk format angka ke Rupiah
  function formatRupiah(angka) {
    return new Intl.NumberFormat("id-ID", {
      style: "currency",
      currency: "IDR"
    }).format(angka);
  }
</script>
<?= $this->endSection(); ?>