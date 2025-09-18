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
        <h4>Transaksi Pembelian</h4>
        <div class="card-header-action">
          <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalLookupPembelian">Lookup</button>
        </div>
      </div>
      <div class="card-body">
        <form id="formPembelian" action="<?= site_url('transaksi/pembelian/pembelian') ?>" data-stock-url="<?= site_url('setup_persediaan/stock/pilihItem') ?>" method="POST">
          <input type="hidden" id="main_csrf" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

          <div class="row">
            <div class="col-lg-2">
              <div class="form-group">
                <!-- Tanggal -->
                <label>Tanggal</label>
                <input type="date" class="form-control form-control" name="tanggal" value="<?= old('tanggal') ?>" required>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                <!-- Supplier -->
                <label>Supplier</label>
                <select class="form-control form-control" name="id_setupsupplier" id="id_setupsupplier" required>
                  <option value="" hidden>-- Pilih Supplier --</option>
                  <?php foreach ($dtsetupsupplier as $key => $value) : ?>
                    <option value="<?= esc($value->id_setupsupplier) ?>"
                      data-ppn="<?= esc($value->tipe) ?>"
                      <?= old('id_setupsupplier') == $value->id_setupsupplier ? 'selected' : '' ?>>
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
                <input type="text" class="form-control form-control" name="TOP" value="<?= old('TOP') ?>" required>
              </div>
            </div>
            <div class="col-lg-2">
              <div class="form-group">
                <!-- Tanggal Jatuh Tempo -->
                <label>Tanggal Jatuh Tempo</label>
                <input type="date" class="form-control form-control" name="tgl_jatuhtempo" value="<?= old('tgl_jatuhtempo') ?>" readonly>
              </div>
            </div>
            <div class="col-lg-2">
              <div class="form-group">
                <!-- Tanggal Invoice -->
                <label>Tanggal Invoice</label>
                <input type="date" class="form-control form-control" name="tgl_invoice" value="<?= old('tgl_invoice') ?>" required>
              </div>
            </div>
            <div class="col-lg-2">
              <div class="form-group">
                <!-- No Invoice -->
                <label>No Invoice</label>
                <input type="text" class="form-control form-control" name="no_invoice" value="<?= old('no_invoice') ?>" required>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Nota</label>
                <input type="text" class="form-control form-control" name="nota" value="<?= old('nota') ?>" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Lokasi</label>
                <select class="form-control form-control" name="id_lokasi" required>
                  <option value="" hidden>-- Pilih Lokasi --</option>
                  <?php foreach ($dtlokasi as $key => $value) : ?>
                    <option value="<?= esc($value->id_lokasi) ?>"
                      <?= old('id_lokasi') == $value->id_lokasi ? 'selected' : '' ?>>
                      <?= esc($value->kode_lokasi . ' - ' . $value->nama_lokasi) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
          <div class="row mt-3">
            <button type="button" class="btn btn-sm btn-primary mb-3" id="btnAddItem" data-toggle="modal" data-target="#modalTambahItem">Tambah Item</button>
            <div class="responsive-table" style="width: 100%; overflow-x: auto;">
              <table class="table table-bordered table-sm w-100" id="tabelDetail">
                <thead>
                  <tr>
                    <th style="width: 100px;">Stock#</th>
                    <th style="width: auto; min-width: 200px;">Nama Stock</th>
                    <th style="width: 100px;">Satuan</th>
                    <th style="width: 160px;">Hrg.Sat</th>
                    <th style="width: 60px;">Qty1</th>
                    <th style="width: 60px;">Qty2</th>
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
                </tbody>
              </table>
            </div>
          </div>
          <div class="row mt-3 justify-content-between">
            <div class="col-md-4">
              <div class="form-group">
                <label>Rekening</label>
                <select class="form-control" name="id_setupbuku" required>
                  <option value="" hidden>-- Pilih Rekening --</option>
                  <?php foreach ($dtrekening as $key => $value) : ?>
                    <option value="<?= esc($value->id_setupbuku) ?>" <?= old('id_setupbuku') == $value->id_setupbuku ? 'selected' : '' ?>>
                      <?= esc($value->kode_setupbuku . '-' . $value->nama_setupbuku) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
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
                  <label>Disc</label>
                  <input type="text" class="form-control form-control-sm" id="disc_cash_rp" name="disc_cash_rp" readonly value="<?= number_format(old('disc_cash_rp') ?: 0, 0, ',', '.') ?>">
                </div>
              </div>
              <div class="form-group">
                <label>DPP</label>
                <input type="text" class="form-control form-control-sm" readonly name="dpp">
              </div>

              <div class="form-row justify-content-between">
                <div class="form-group col-lg-4 ">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="ppn_option" value="exclude" checked> Exclude
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="ppn_option" value="include"> Include
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="ppn_option" value="non_ppn"> Non PPN
                  </div>
                </div>
                <div class="form-group col-lg-6">
                  <label>PPN (%)</label>
                  <input type="number" id="ppn" class="form-control form-control-sm" name="ppn" value="<?= old('ppn') ?>">
                </div>
              </div>
              <div class="form-group">
                <label>Grand Total</label>
                <input type="text" id="grand_total" class="form-control form-control-sm" name="grand_total" value="<?= number_format(old('grand_total') ?: 0, 0, ',', '.') ?>" readonly>
              </div>

              <div class="form-group">
                <label>Tunai</label>
                <input type="text" id="tunai" class="form-control form-control-sm" name="tunai" value="<?= number_format(old('tunai') ?: 0, 0, ',', '.') ?>">
              </div>

              <div class="form-group">
                <label>Hutang</label>
                <input type="text" id="hutang" class="form-control form-control-sm" name="hutang" value="<?= number_format(old('hutang') ?: 0, 0, ',', '.') ?>" readonly>
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
<div class="modal fade" tabindex="-1" role="dialog" id="modalTambahItem" data-item-url="<?= site_url('setup_persediaan/stock/lookup-stock') ?>">
  <input type="hidden" id="modal_item_csrf" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Item Pembelian</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-striped table-md" id="myTableItem" width="100%">
            <thead>
              <tr class="eureeka-table-header">
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Group</th>
                <th>Kelompok</th>
                <th>Supplier</th>
                <th>Satuan</th>
                <th>Aksi</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
      <div class="modal-footer bg-whitesmoke br">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
      </div>
    </div>
  </div>
</div>

<!-- Tempat modal lookup -->
<div class="modal fade" tabindex="-1" role="dialog" id="modalLookupPembelian" data-lookup-url="<?= site_url('transaksi/pembelian/pembelian/lookup-pembelian') ?>">
  <input type="hidden" id="modal_lookup_csrf" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Lookup Pembelian</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-striped table-md" id="myTableLookup" width="100%">
            <thead>
              <tr class="eureeka-table-header">
                <th>Tanggal</th>
                <th>Nota</th>
                <th>Supplier</th>
                <th>Tgl. Jatuh Tempo</th>
                <th>Tgl. Invoice</th>
                <th>No. Invoice</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
      <div class="modal-footer bg-whitesmoke br">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('pageScript') ?>
<script src="<?= base_url('assets/js/views/transaksi/pembelian/pembelian.js') ?>"></script>
<?= $this->endSection(); ?>