<div class="modal fade" tabindex="-1" role="dialog" id="modalEdit">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Stock</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?= form_open('setup_persediaan/stock/' . $stock->id_stock, ['id' => 'form-edit-stock']) ?>
      <input type="hidden" name="_method" value="PUT">
      <div class="modal-body">
        <div class="form-group row">
          <label for="kode" class="col-sm-3 col-form-label">Kode</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" id="kode" placeholder="Kode" name="kode" required value="<?= $stock->kode ?>">
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-3 col-form-label">Nama Barang </label>
          <div class="col-sm-9">
            <input type="text" class="form-control" name="nama_barang" placeholder="Nama Barang" required value="<?= $stock->nama_barang ?>">
          </div>
        </div>

        <div class="form-group row">
          <label class="col-sm-3 col-form-label">Group</label>
          <div class="col-sm-9">
            <select class="form-control" name="id_group" required>
              <option value="" hidden>--Pilih Group--</option>
              <?php foreach ($dtgroup as $group) : ?>
                <option value="<?= $group->id_group ?>" <?= $group->id_group == $stock->id_group ? 'selected' : '' ?>><?= $group->kode_group . ' - ' . $group->nama_group ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-sm-3 col-form-label">Kelompok</label>
          <div class="col-sm-9">
            <select class="form-control" name="id_kelompok" required>
              <option value="" hidden>--Pilih Kelompok--</option>
              <?php foreach ($dtkelompok as $kelompok) : ?>
                <option value="<?= $kelompok->id_kelompok ?>" <?= $kelompok->id_kelompok == $stock->id_kelompok ? 'selected' : '' ?>><?= $kelompok->kode_kelompok . ' - ' . $kelompok->nama_kelompok ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-sm-3 col-form-label">Satuan 1</label>
          <div class="col-sm-9">
            <select class="form-control" name="id_satuan" required>
              <option value="" hidden>--Pilih Satuan--</option>
              <?php foreach ($dtsatuan as $satuan) : ?>
                <option value="<?= $satuan->id_satuan ?>" <?= $satuan->id_satuan == $stock->id_satuan ? 'selected' : '' ?>><?= $satuan->kode_satuan . ' - ' . $satuan->nama_satuan ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-sm-3 col-form-label">Faktor Konversi</label>
          <div class="col-sm-9"><input type="number" class="form-control" name="conv_factor" placeholder="Faktor Konversi" required value="<?= $stock->conv_factor ?>"></div>

        </div>

        <div class="form-group row">
          <label class="col-sm-3 col-form-label">Satuan 2</label>
          <div class="col-sm-9">
            <select class="form-control" name="id_satuan2" required>
              <option value="" hidden>--Pilih Satuan--</option>
              <?php foreach ($dtsatuan as $satuan) : ?>
                <option value="<?= $satuan->id_satuan ?>" <?= $satuan->id_satuan == $stock->id_satuan2 ? 'selected' : '' ?>><?= $satuan->kode_satuan . ' - ' . $satuan->nama_satuan ?></option>
              <?php endforeach; ?>
            </select>
          </div>

        </div>

        <div class="form-group row">
          <label class="col-sm-3 col-form-label">Supplier</label>
          <div class="col-sm-9">
            <select class="form-control" name="id_setupsupplier" required>
              <option value="" hidden>--Pilih Supplier--</option>
              <?php foreach ($dtsupplier as $supplier) : ?>
                <option value="<?= $supplier->id_setupsupplier ?>" <?= $supplier->id_setupsupplier == $stock->id_setupsupplier ? 'selected' : '' ?>><?= $supplier->nama ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-sm-3 col-form-label">Stock Minimal </label>
          <div class="col-sm-9"><input type="text" class="form-control" name="min_stock" placeholder="Stock Minimal" required value="<?= $stock->min_stock ?>"></div>
        </div>

      </div>
      <div class="modal-footer bg-whitesmoke br">
        <div class="form-group">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success btn-edit">Update</button>
        </div>
      </div>
      <?= form_close() ?>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('#form-edit-stock').submit(function(e) {
      e.preventDefault();
      $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        beforeSend: function() {
          $('.btn-edit').addClass('disabled');
          $('.btn-edit').addClass('btn-progress');
        },
        complete: function() {
          $('.btn-edit').removeClass('disabled');
          $('.btn-edit').removeClass('btn-progress');
        },
        success: function(response) {

          iziToast.success({
            title: 'Sukses',
            message: 'Data berhasil diupdate',
            position: 'topCenter',
            titleSize: '20',
            messageSize: '20',
            layout: 2,
          });

          $('#modalEdit').modal('hide');
          reload_table();

        },
        error: function(xhr, ajaxOptions, thrownError) {
          alert(xhr.status + '\n' + xhr.responseText + '\n' + thrownError);
        }
      })
    });
  });
</script>