<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <!-- <h1>APA INI</h1> -->
    <a href="<?= site_url('transaksi/penjualan/returpenjualan') ?>" class="btn btn-primary">
      <i class="fas fa-arrow-left"></i> Kembali
    </a>
  </div>

  <div class="section-body">
    <!-- HALAMAN DINAMIS -->
    <div class="card">
      <div class="card-header">
        <h4>Retur Penjualan</h4>
      </div>
      <div class="card-body">
        <form id="formReturPenjualan" action="<?= site_url('transaksi/penjualan/returpenjualan') ?>" data-stock-url="<?= site_url('transaksi/penjualan/penjualan/lookup-stock') ?>" method="POST">
          <?= csrf_field() ?>
          <input type="hidden" name="id_penjualan">
          <div class="row">
            <div class="col-lg-2">
              <div class="form-group">
                <!-- Tanggal -->
                <label>Tanggal</label>
                <input type="date" class="form-control form-control-sm" name="tanggal" value="<?= old('tanggal') ?>" required>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                <label>Pelanggan</label>
                <select class="form-control form-control-sm" name="id_pelanggan" id="id_pelanggan" required>
                  <option value="" hidden>-- Pilih Pelanggan --</option>
                  <?php foreach ($dtpelanggan as $key => $value) : ?>
                    <option value="<?= esc($value->id_pelanggan) ?>" <?= old('id_pelanggan') == $value->id_pelanggan ? 'selected' : '' ?>>
                      <?= esc($value->kode_pelanggan . ' - ' . $value->nama_pelanggan) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                <label>Salesman</label>
                <select class="form-control form-control-sm" name="id_salesman" id="id_salesman" required>
                  <option value="" hidden>-- Pilih Salesman --</option>
                  <?php foreach ($dtsalesman as $key => $value) : ?>
                    <option value="<?= esc($value->id_salesman) ?>" <?= old('id_salesman') == $value->id_salesman ? 'selected' : '' ?>>
                      <?= esc($value->kode_salesman . ' - ' . $value->nama_salesman) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group p-3">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="ppn_option" value="exclude" id="inlineRadio1" checked>
                  <label class="form-check-label" for="inlineRadio1">Exclude</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="ppn_option" value="include" id="inlineRadio2">
                  <label class="form-check-label" for="inlineRadio2">Include</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="ppn_option" value="non_ppn" id="inlineRadio3">
                  <label class="form-check-label" for="inlineRadio3">Non PPN</label>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Nota</label>
                <input type="text" class="form-control form-control-sm" name="nota" value="<?= old('nota') ?>" required>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Lokasi</label>
                <select class="form-control form-control-sm" name="id_lokasi" required>
                  <option value="" hidden>-- Pilih Lokasi --</option>
                  <?php foreach ($dtlokasi as $key => $value) : ?>
                    <option value="<?= esc($value->id_lokasi) ?>" <?= old('id_lokasi') == $value->id_lokasi ? 'selected' : '' ?>>
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
                  <tr>
                    <td>
                      <input name="detail[0][id_stock]" hidden>
                      <input name="detail[0][kode]" class="form-control form-control-sm">
                      <input name="detail[0][conv_factor]" hidden class="form-control form-control-sm">
                    </td>
                    <td><input name="detail[0][nama_barang]" class="form-control form-control-sm" readonly></td>
                    <td><input name="detail[0][satuan]" class="form-control form-control-sm" readonly></td>
                    <td><input name="detail[0][qty1]" class="form-control form-control-sm"></td>
                    <td><input name="detail[0][qty2]" class="form-control form-control-sm"></td>
                    <td>
                      <input name="detail[0][harga_satuan]" class="form-control form-control-sm" readonly>
                      <input name="detail[0][harga_satuan_include]" type="hidden">
                      <input name="detail[0][harga_satuan_exclude]" type="hidden">
                    </td>
                    <td><input name="detail[0][jml_harga]" class="form-control form-control-sm" readonly></td>
                    <td><input name="detail[0][disc_1_perc]" class="form-control form-control-sm"></td>
                    <td><input name="detail[0][disc_1_rp]" class="form-control form-control-sm"></td>
                    <td><input name="detail[0][disc_2_perc]" class="form-control form-control-sm" readonly></td>
                    <td><input name="detail[0][disc_2_rp]" class="form-control form-control-sm" readonly></td>
                    <td><input name="detail[0][total]" class="form-control form-control-sm" readonly></td>
                    <td><button type="button" class="btn btn-danger btnRemove">X</button></td>
                  </tr>
                </tbody>
              </table>
              <!-- <button type="button" class="btn btn-sm btn-primary" id="btnAddRow">Tambah Baris</button> -->
            </div>
          </div>
          <div class="row mt-3 justify-content-between">
            <div class="col-md-4">
              <div class="form-group p-3 w-50 border">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="opsi_return" id="inlineRadio1" value="kredit" checked>
                  <label class="form-check-label" for="inlineRadio1">Kredit</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="opsi_return" id="inlineRadio2" value="tunai">
                  <label class="form-check-label" for="inlineRadio2">Tunai</label>
                </div>
              </div>
              <div class="form-group">
                <label>Tanggal Penjualan</label>
                <input type="date" class="form-control" name="tgl_penjualan" readonly>
              </div>
              <div class="form-group">
                <label>Nota Penjualan</label>
                <div class="form-row">
                  <div class="col-md-8">
                    <input type="text" class="form-control" name="nota_penjualan" readonly>
                  </div>
                  <div class="col-md-4">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modalNotaPenjualan">cari</button>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Sub Total</label>
                <input type="text" id="sub_total" class="form-control form-control-sm" name="sub_total" value="<?= number_format(old('sub_total') ?: 0, 0, ',', '.') ?>" readonly>
              </div>
              <div class="form-row">
                <div class="form-group col-lg-6">
                  <label>Disc Cash %</label>
                  <input type="number" id="disc_cash" class="form-control form-control-sm " name="disc_cash" value="<?= old('disc_cash') ?>">
                </div>
                <div class="form-group col-lg-6">
                  <label>Disc Cash Rp</label>
                  <input type="text" class="form-control form-control-sm" id="disc_cash_rp" name="disc_cash_rp" value="<?= number_format(old('disc_cash_rp') ?: 0, 0, ',', '.') ?>" readonly>
                </div>
              </div>
              <div class="form-group">
                <label>Netto</label>
                <input type="text" class="form-control form-control-sm" readonly name="netto">
              </div>
              <div class="form-group">
                <label>PPN %</label>
                <input type="number" id="ppn" class="form-control form-control-sm" name="ppn" value="<?= old('ppn') ?>" readonly>
              </div>

              <div class="form-group">
                <label>Grand Total</label>
                <input type="text" id="grand_total" class="form-control form-control-sm" name="grand_total" value="<?= number_format(old('grand_total') ?: 0, 0, ',', '.') ?>" readonly>
              </div>
            </div>
          </div>

          <div class="form-group">
            <button type="reset" class="btn btn-danger mr-3">Reset</button>
            <button type="submit" class="btn btn-success">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- Tempat modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="modalNotaPenjualan" data-nota-url="<?= site_url('transaksi/penjualan/penjualan/') ?>">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Cari Nota Penjualan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-striped table-md" id="myTable">
            <thead>
              <tr class="eureeka-table-header">
                <th>No</th>
                <th>Tanggal</th>
                <th>Nota</th>
                <th>Salesman</th>
                <th>Pelanggan</th>
                <th>Tgl. Jatuh Tempo</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="tabelNotaPenjualan">
              <!-- Data akan diisi dengan AJAX -->
              <?php foreach ($dtpenjualan as $key => $value) : ?>
                <tr>
                  <td><?= $key + 1 ?></td>
                  <td><?= $value->tanggal ?></td>
                  <td><?= $value->nota ?></td>
                  <td><?= $value->nama_salesman ?></td>
                  <td><?= $value->nama_pelanggan ?></td>
                  <td><?= $value->tgl_jatuhtempo ?></td>
                  <td><button class="btn btn-primary" onclick="pilihNota(<?= $value->id_penjualan ?>)">Pilih</button></td>
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
<script src="<?= base_url('assets/js/views/transaksi/penjualan/returpenjualan.js') ?>"></script>
<?= $this->endSection(); ?>