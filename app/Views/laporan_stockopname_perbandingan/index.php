<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>
<title>Akuntansi Eureeka &mdash; Laporan Perbandingan Stock Opname</title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <h1>Laporan Perbandingan Stock Opname</h1>
  </div>

  <div class="card">
    <div class="card-header">
      <div class="card-header-action">
        <a href="<?= base_url('laporanstock_opname_perbandingan/printPDF?tanggal=' . $tanggal . '&lokasi=' . $lokasi . '&user=' . $user) ?>" class="btn btn-success" target="_blank">
          <i class="fas fa-print"></i> Cetak PDF
        </a>
      </div>
    </div>
    <div class="card-body">
      <form method="GET" action="<?= base_url('laporanstock_opname_perbandingan') ?>">
        <div class="d-flex align-items-end mb-3">
          <div class="col-md-3">
            <label for="tanggal">Per Tanggal</label>
            <input type="date" name="tanggal" class="form-control" value="<?= $tanggal ?>">
          </div>
          <div class="col-md-3">
            <label for="nota_s_opname">Nota S. Opname</label>
            <input type="text" class="form-control" id="nota_s_opname" name="nota_s_opname" readonly>
            <input type="text" class="form-control" name="id_stockopname" hidden>
          </div>
          <div class="col-md-2">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCariNota">cari</button>
          </div>
        </div>
        <div class="d-flex align-items-end">
          <div class="col-md-3">
            <label for="lokasi">Lokasi</label>
            <select id="lokasi" name="lokasi" class="form-control">
              <option value="">Semua Lokasi</option>
              <?php foreach ($dtlokasi as $lok): ?>
                <option value="<?= $lok->id_lokasi ?>" <?= $lok->id_lokasi == $lokasi ? 'selected' : '' ?>><?= $lok->nama_lokasi ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-3">
            <label for="user">User</label>
            <select name="user" class="form-control">
              <option value="">Semua User</option>
              <?php foreach ($dtuser as $user): ?>
                <option value="<?= $user->id_user ?>" <?= $user->id_user == $user ? 'selected' : '' ?>><?= $user->nama_user ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Filter</button>
          </div>
        </div>
      </form>

      <div class="table-responsive mt-5">
        <table class="table table-striped table-md display nowrap compact eureeka-table" id="myTable">
          <thead>
            <tr class="eureeka-table-header">
              <th>No</th>
              <th>Stock#</th>
              <th>Nama Stock</th>
              <th>Satuan</th>
              <th>Qty(F)</th>
              <th>Qty(b)</th>
              <th>Selisih</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($dts_opname as $key => $value): ?>
              <tr>
                <td><?= $key + 1 ?></td>
                <td><?= $value->kode ?></td>
                <td><?= $value->nama_barang ?></td>
                <td><?= $value->satuan ?></td>
                <td><?= $value->qty_1 ?> / <?= $value->qty_2 ?></td>
                <td><?= $value->qty_1_sys ?> / <?= $value->qty_2_sys ?></td>
                <td><?= $value->selisih_1 ?> / <?= $value->selisih_2 ?></td>
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

<!-- Tempat modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="modalCariNota" data-nota-url="<?= site_url('laporanstock_opname_perbandingan/cari-nota/') ?>">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Cari Nota Stock Opname</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="table-responsive table-wrapper-medium">
          <table class="table table-striped table-md" id="myTableSearch">
            <thead>
              <tr class="eureeka-table-header">
                <th>No</th>
                <th>Tanggal</th>
                <th>Nota</th>
                <th>Lokasi</th>
                <th>User</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="tabelCariNota">
              <?php foreach ($dtnota_opname as $key => $value) : ?>
                <tr>
                  <td><?= $key + 1 ?></td>
                  <td><?= $value->tanggal ?></td>
                  <td><?= $value->nota ?></td>
                  <td><?= $value->nama_lokasi ?></td>
                  <td><?= $value->nama_user ?></td>
                  <td><button class="btn btn-primary" id="btn_cari_<?= $value->id_stockopname ?>" onclick="pilihNota(<?= $value->id_stockopname ?>)">Pilih</button></td>
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
<script>
  function pilihNota(id) {
    const url = $('#modalCariNota').data('nota-url');
    // Fungsi untuk mencari nota stock opname dan autofill data
    $.ajax({
      url: url + id,
      method: 'GET',
      dataType: 'json',
      beforeSend: function() {
        // Show loading indicator
        $('#btn_cari_' + id).html('<i class="fa fa-spinner fa-spin"></i>');
      },
      success: function(data) {
        if (data.status) {
          const dataOpname = data.data;
          $('input[name="id_stockopname"]').val(dataOpname.id_stockopname);
          $('input[name="nota_s_opname"]').val(dataOpname.nota);

          // buat isi tabel stock opname
          let tbody = '';
          tbody += `<tr>
               <td>${1}</td>
               <td>${dataOpname.kode}</td>
               <td>${dataOpname.nama_barang}</td>
               <td>${dataOpname.satuan}</td>
               <td>${dataOpname.qty_1} / ${dataOpname.qty_2}</td>
               <td>${dataOpname.qty_1_sys} / ${dataOpname.qty_2_sys}</td>
               <td>${dataOpname.selisih_1} / ${dataOpname.selisih_2}</td>
             </tr>`;
          $('#myTable tbody').html(tbody);

          // kembalikan button ke semula
          $('#btn_cari_' + id).html('pilih');

          // Close modal
          $('#modalCariNota').modal('hide');
        }
      },
      error: function(xhr, status, error) {
        console.error('Error fetching Nota Opname:', error);
      }
    });
  }
</script>
<?= $this->endSection(); ?>