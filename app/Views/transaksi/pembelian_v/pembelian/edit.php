<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <!-- <h1>Pembelian</h1> -->
    <a href="<?= site_url('transaksi/pembelian/pembelian') ?>" class="btn btn-primary">
      <i class="fas fa-arrow-left"></i> Kembali
    </a>
  </div>

  <div class="section-body">
    <!-- HALAMAN DINAMIS -->
    <div class="card">
      <div class="card-header">
        <h4>Edit Pembelian</h4>
      </div>
      <div class="card-body">
        <form id="formPembelian" method="post" action="<?= site_url('transaksi/pembelian/pembelian/' . $dtheader->id_pembelian) ?>">
          <input type="hidden" name="_method" value="PUT">
          <?= csrf_field() ?>

          <div class="row">
            <div class="col-lg-2">
              <div class="form-group">
                <!-- Tanggal -->
                <label>Tanggal</label>
                <input type="date" class="form-control form-control-sm" name="tanggal" value="<?= $dtheader->tanggal ?>" required>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                <!-- Supplier -->
                <label>Supplier</label>
                <select class="form-control form-control-sm" name="id_setupsupplier" required>
                  <option value="" hidden>-- Pilih Supplier --</option>
                  <?php foreach ($dtsetupsupplier as $key => $value) : ?>
                    <option value="<?= esc($value->id_setupsupplier) ?>" <?= $dtheader->id_setupsupplier == $value->id_setupsupplier ? 'selected' : '' ?>>
                      <?= esc($value->kode . ' - ' . $value->nama) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-lg-1">
              <div class="form-group">
                <!-- TOP -->
                <label>TOP</label>
                <input type="text" class="form-control form-control-sm" name="TOP" value="<?= $dtheader->TOP ?>" required>
              </div>
            </div>
            <div class="col-lg-2">
              <div class="form-group">
                <!-- Tanggal Jatuh Tempo -->
                <label>Tanggal Jatuh Tempo</label>
                <input type="date" class="form-control form-control-sm" name="tgl_jatuhtempo" value="<?= $dtheader->tgl_jatuhtempo ?>" readonly>
              </div>
            </div>
            <div class="col-lg-2">
              <div class="form-group">
                <!-- Tanggal Invoice -->
                <label>Tanggal Invoice</label>
                <input type="date" class="form-control form-control-sm" name="tgl_invoice" value="<?= $dtheader->tgl_invoice ?>" required>
              </div>
            </div>
            <div class="col-lg-2">
              <div class="form-group">
                <!-- No Invoice -->
                <label>No Invoice</label>
                <input type="text" class="form-control form-control-sm" name="no_invoice" value="<?= $dtheader->no_invoice ?>" required>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Nota</label>
                <input type="text" class="form-control form-control-sm" name="nota" value="<?= $dtheader->nota ?>" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Lokasi</label>
                <select class="form-control form-control-sm" name="id_lokasi" required>
                  <option value="" hidden>-- Pilih Lokasi --</option>
                  <?php foreach ($dtlokasi as $key => $value) : ?>
                    <option value="<?= esc($value->id_lokasi) ?>" <?= $dtheader->id_lokasi == $value->id_lokasi ? 'selected' : '' ?>>
                      <?= esc($value->kode_lokasi . ' - ' . $value->nama_lokasi) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
          <div class="row mt-3">
            <div class="responsive-table" style="width: 100%; overflow-x: auto;">
              <table class="table table-bordered table-sm w-100" id="tabelDetail">
                <thead>
                  <tr>
                    <th style="width: 100px;">Stock#</th>
                    <th style="width: auto; min-width: 200px;">Nama Stock</th>
                    <th style="width: 100px;">Satuan</th>
                    <th style="width: 60px;">Qty1</th>
                    <th style="width: 60px;">Qty2</th>
                    <th style="width: 160px;">Hrg.Sat</th>
                    <th style="width: 160px;">Jml.Harga</th>
                    <th style="width: 60px;">Dis.1(%)</th>
                    <th style="width: 160px;">Dis.1(Rp.)</th>
                    <th style="width: 60px;">Dis.2(%)</th>
                    <th style="width: 160px;">Dis.2(Rp.)</th>
                    <th style="width: 160px;">Total</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($dtdetail as $key => $value) : ?>
                    <tr>
                      <td>
                        <input name="detail[<?= $key ?>][id_detail]" value="<?= $value->id ?>" hidden>
                        <input name="detail[<?= $key ?>][id_stock]" value="<?= $value->id_stock ?>" hidden>
                        <input name="detail[<?= $key ?>][kode]" class="form-control form-control-sm" value="<?= $value->kode ?>">
                        <input name="detail[<?= $key ?>][conv_factor]" hidden class="form-control form-control-sm" value="<?= $value->conv_factor ?>">
                      </td>
                      <td><input name="detail[<?= $key ?>][nama_barang]" class="form-control form-control-sm" value="<?= $value->nama_barang ?>" readonly></td>
                      <td><input name="detail[<?= $key ?>][satuan]" class="form-control form-control-sm" value="<?= $value->satuan ?>" readonly></td>
                      <td><input name="detail[<?= $key ?>][qty1]" class="form-control form-control-sm" value="<?= $value->qty1 ?>"></td>
                      <td><input name="detail[<?= $key ?>][qty2]" class="form-control form-control-sm" value="<?= $value->qty2 ?>"></td>
                      <td><input name="detail[<?= $key ?>][harga_satuan]" class="form-control form-control-sm" value="<?= $value->harga_satuan ?>" readonly></td>
                      <td><input name="detail[<?= $key ?>][jml_harga]" class="form-control form-control-sm" value="<?= $value->jml_harga ?>" readonly></td>
                      <td><input name="detail[<?= $key ?>][disc_1_perc]" class="form-control form-control-sm" value="<?= $value->disc_1_perc ?>"></td>
                      <td><input name="detail[<?= $key ?>][disc_1_rp]" class="form-control form-control-sm" value="<?= $value->disc_1_rp ?>"></td>
                      <td><input name="detail[<?= $key ?>][disc_2_perc]" class="form-control form-control-sm" value="<?= $value->disc_2_perc ?>"></td>
                      <td><input name="detail[<?= $key ?>][disc_2_rp]" class="form-control form-control-sm" value="<?= $value->disc_2_rp ?>"></td>
                      <td><input name="detail[<?= $key ?>][total]" class="form-control form-control-sm" value="<?= $value->total ?>" readonly></td>
                      <td><button type="button" class="btn btn-danger btnRemove">X</button></td>
                    </tr>
                  <?php endforeach ?>
                </tbody>
              </table>
              <button type="button" class="btn btn-sm btn-primary" id="btnAddRow">Tambah Baris</button>
            </div>
          </div>
          <div class="row mt-3 justify-content-between">
            <div class="col-md-4">
              <div class="form-group">
                <label>Rekening</label>
                <select class="form-control" name="id_setupbuku" required>
                  <option value="" hidden>-- Pilih Rekening --</option>
                  <?php foreach ($dtrekening as $key => $value) : ?>
                    <option value="<?= esc($value->id_setupbuku) ?>" <?= $dtheader->id_setupbuku == $value->id_setupbuku ? 'selected' : '' ?>>
                      <?= esc($value->kode_setupbuku . '-' . $value->nama_setupbuku) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Sub Total</label>
                <input type="text" id="sub_total" class="form-control form-control-sm" name="sub_total" value="<?= $dtheader->sub_total ?>" readonly>
              </div>
              <div class="form-row">
                <div class="form-group col-lg-6">
                  <input type="number" id="disc_cash" class="form-control form-control-sm " name="disc_cash" placeholder="Discount cash %" value="<?= $dtheader->disc_cash  ?>">
                </div>
                <div class="form-group col-lg-6">
                  <input type="text" class="form-control form-control-sm" id="disc_cash_amount" name="disc_cash_amount" readonly>
                </div>
              </div>
              <div class="form-group">
                <label>DPP</label>
                <input type="text" class="form-control form-control-sm" readonly name="dpp" value="<?= $dtheader->dpp ?>">
              </div>

              <div class="form-row justify-content-between">
                <div class="form-group col-lg-4 ">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="ppn_option" value="exclude" <?= ($dtheader->ppn_option == 'exclude') ? 'checked' : '' ?>> Exclude
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="ppn_option" value="include" <?= ($dtheader->ppn_option == 'include') ? 'checked' : '' ?>> Include
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="ppn_option" value="non_ppn" <?= ($dtheader->ppn_option == 'non_ppn') ? 'checked' : '' ?>> Non PPN
                  </div>
                </div>
                <div class="form-group col-lg-6">
                  <label>PPN (%)</label>
                  <input type="number" id="ppn" class="form-control form-control-sm" name="ppn" value="<?= $dtheader->ppn ?>">
                </div>
              </div>
              <div class="form-group">
                <label>Grand Total</label>
                <input type="text" id="grand_total" class="form-control form-control-sm" name="grand_total" value="<?= $dtheader->grand_total ?>" readonly>
              </div>

              <div class="form-group">
                <label>Tunai</label>
                <input type="text" id="tunai" class="form-control form-control-sm" name="tunai" value="<?= $dtheader->tunai ?>">
              </div>

              <div class="form-group">
                <label>Hutang</label>
                <input type="text" id="hutang" class="form-control form-control-sm" name="hutang" value="<?= $dtheader->hutang ?>" readonly>
              </div>
            </div>
          </div>

          <div class="form-group">
            <a href="<?= site_url('transaksi/pembelian/pembelian') ?>" class="btn btn-primary mr-3">
              <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <button type="submit" class="btn btn-success">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<?= $this->endSection(); ?>

<?= $this->section('pageScript') ?>
<script src="<?= base_url('assets/js/views/transaksi/pembelian/editpembelian.js') ?>"></script>
<?= $this->endSection(); ?>