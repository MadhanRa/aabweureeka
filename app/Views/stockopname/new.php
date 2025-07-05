<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <!-- <h1>APA INI</h1> -->
    <a href="<?= site_url('stockopname') ?>" class="btn btn-primary">
      <i class="fas fa-arrow-left"></i> Kembali
    </a>
  </div>

  <div class="section-body">
    <!-- HALAMAN DINAMIS -->
    <div class="card">
      <div class="card-header">
        <h4>Stock Opname</h4>
      </div>
      <div class="card-body">
        <div class="form-container">
          <form method="post" action="<?= site_url('stockopname') ?>">
            <?= csrf_field() ?>

            <div class="form-group">
              <label>Tanggal</label>
              <input type="date" class="form-control" name="tanggal" value="<?= old('tanggal') ?>" required>
            </div>

            <div class="form-group">
              <label>Nota</label>
              <input type="text" class="form-control" name="nota" value="<?= old('nota') ?>" required>
            </div>

            <div class="form-group">
              <label>Lokasi Asal</label>
              <select class="form-control" name="id_lokasi" required>
                <option value="" hidden>-- Pilih Lokasi --</option>
                <?php foreach ($dtlokasi as $key => $value) : ?>
                  <option value="<?= esc($value->id_lokasi) ?>" <?= old('id_lokasi') == $value->id_lokasi ? 'selected' : '' ?>>
                    <?= esc($value->nama_lokasi) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-group">
              <label>User</label>
              <select class="form-control" name="id_user" required>
                <option value="" hidden>-- Pilih User --</option>
                <?php foreach ($dtsetupuser as $key => $value) : ?>
                  <option value="<?= esc($value->id_user) ?>" <?= old('id_user') == $value->id_user ? 'selected' : '' ?>>
                    <?= esc($value->nama_user) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-group">
              <label>Nama Stock</label>
              <input type="hidden" name="id_stock" value="<?= old('id_stock') ?>">
              <input type="text" class="form-control" name="nama_stock" value="<?= old('nama_stock') ?>" required>
            </div>

            <div class="form-group">
              <label>Satuan</label>
              <input type="text" class="form-control" name="satuan" value="<?= old('satuan') ?>" readonly>
            </div>

            <div class="form-group">
              <label>QTY 1</label>
              <input type="number" class="form-control" name="qty_1" value="<?= old('qty_1') ?>" required>
            </div>

            <div class="form-group">
              <label>QTY 2</label>
              <input type="number" class="form-control" name="qty_2" value="<?= old('qty_2') ?>" required>
            </div>

            <div class="form-group">
              <button type="submit" class="btn btn-success">Simpan Data</button>
              <button type="reset" class="btn btn-danger">Reset</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<?= $this->endSection(); ?>

<?= $this->section('pageScript') ?>
<script>
  // Autocomplete untuk nama stock
  $(document).ready(function() {
    $('input[name="nama_stock"]').autocomplete({
      source: function(req, res) {
        let locationId = $("select[name='id_lokasi']").val();

        $.get('<?= site_url('stockopname/autocomplete') ?>', {
          term: req.term,
          location_id: locationId
        }, data => {
          res(data.map(item => ({
            label: `${item.kode} - ${item.nama_barang}`,
            value: item.kode,
            item: item
          })));
        }).fail(() => res([]));
      },
      minLength: 2,
      select: function(event, ui) {
        $('input[name="id_stock"]').val(ui.item.item.id_stock);
        $('input[name="nama_stock"]').val(ui.item.item.nama_barang);
        $('input[name="satuan"]').val(ui.item.item.satuan_1 + '/' + ui.item.item.satuan_2);
        return false;
      }
    }).autocomplete("instance")._renderItem = function(ul, item) {
      return $("<li>")
        .append(`
        <div><strong>${item.item.kode}</strong> - ${item.item.nama_barang} <br>
        <small>Satuan: ${item.item.satuan_1}${item.item.satuan_2 ? '/' + item.item.satuan_2 : ''}</small></div>`)
        .appendTo(ul);
    };
  });
</script>
<?= $this->endSection(); ?>