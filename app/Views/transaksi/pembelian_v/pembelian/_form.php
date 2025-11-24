<form id="formPembelian" action="<?= $formAction ?>" data-stock-url="<?= site_url('setup_persediaan/stock/pilihItem') ?>" method="POST">
    <?= $formMethod ?>
    <input type="hidden" id="main_csrf" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

    <div class="row">
        <div class="col-lg-2">
            <div class="form-group">
                <!-- Tanggal -->
                <label for="tanggal">Tanggal</label>
                <input type="date" class="form-control" name="tanggal" value="<?= old('tanggal') ?>" required>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group">
                <!-- Supplier -->
                <label for="id_setupsupplier">Supplier</label>
                <select class="form-control" name="id_setupsupplier" id="id_setupsupplier" required>
                    <option value="" hidden>-- Pilih Supplier --</option>
                    <?php foreach ($dtsetupsupplier as $key => $value) : ?>
                        <option value="<?= esc($value->id_setupsupplier) ?>"
                            data-ppn="<?= esc($value->tipe) ?>"
                            <?= ($data->id_setupsupplier ?? old('id_setupsupplier')) == $value->id_setupsupplier ? 'selected' : '' ?>>
                            <?= esc($value->kode . ' - ' . $value->nama) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="col-lg-1">
            <div class="form-group">
                <!-- TOP -->
                <label for="TOP">TOP</label>
                <input type="text" class="form-control" name="TOP" value="<?= ($data->TOP ?? old('TOP')) ?>" required>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="form-group">
                <!-- Tanggal Jatuh Tempo -->
                <label for="tgl_jatuhtempo">Tanggal Jatuh Tempo</label>
                <input type="date" class="form-control" name="tgl_jatuhtempo" value="<?= ($data->tgl_jatuhtempo ?? old('tgl_jatuhtempo')) ?>" readonly>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="form-group">
                <!-- Tanggal Invoice -->
                <label for="tgl_invoice">Tanggal Invoice</label>
                <input type="date" class="form-control" name="tgl_invoice" value="<?= ($data->tgl_invoice ?? old('tgl_invoice')) ?>" required>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="form-group">
                <!-- No Invoice -->
                <label for="no_invoice">No Invoice</label>
                <input type="text" class="form-control" name="no_invoice" value="<?= ($data->no_invoice ?? old('no_invoice')) ?>" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="nota">Nota</label>
                <input type="text" class="form-control" name="nota" value="<?= ($data->nota ?? old('nota')) ?>" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="id_lokasi">Lokasi</label>
                <select class="form-control" name="id_lokasi" required>
                    <option value="" hidden>-- Pilih Lokasi --</option>
                    <?php foreach ($dtlokasi as $key => $value) : ?>
                        <option value="<?= esc($value->id_lokasi) ?>"
                            <?= ($data->id_lokasi ?? old('id_lokasi')) == $value->id_lokasi ? 'selected' : '' ?>>
                            <?= esc($value->kode_lokasi . ' - ' . $value->nama_lokasi) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    <hr>
    <div class="row mt-3">
        <div class="col-12">
            <button type="button" class="btn btn-sm btn-primary mb-3" id="btnAddItem" data-toggle="modal" data-target="#modalTambahItem">Tambah Item</button>
        </div>
        <div class="col-12">
            <div class="responsive-table" style="width: 100%; overflow-x: auto;">
                <table class="table table-bordered table-sm w-100" id="tabelDetail">
                    <thead>
                        <tr>
                            <th class="col-stock">Stock#</th>
                            <th class="col-nama">Nama Stock</th>
                            <th class="col-satuan">Satuan</th>
                            <th class="col-hrg">Hrg.Sat</th>
                            <th class="col-qty1">Qty1</th>
                            <th class="col-qty2">Qty2</th>
                            <th class="col-jmlhrg">Jml.Harga</th>
                            <th class="col-dis1p">Dis.1(%)</th>
                            <th class="col-dis1r">Dis.1(Rp.)</th>
                            <th class="col-dis2p">Dis.2(%)</th>
                            <th class="col-dis2r">Dis.2(Rp.)</th>
                            <th class="col-total">Total</th>
                            <th class="col-action">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (isset($dtdetail) && $dtdetail): ?>
                            <?php foreach ($dtdetail as $key => $value) : ?>
                                <!-- Edit mode: populate with existing data -->
                                <tr>
                                    <td>
                                        <input name="detail[<?= $key ?>][id_detail]" value="<?= $value->id ?>" hidden>
                                        <input name="detail[<?= $key ?>][id_stock]" value="<?= $value->id_stock ?>" hidden>
                                        <input name="detail[<?= $key ?>][kode]" class="form-control form-control-sm" value="<?= $value->kode ?>" readonly>
                                        <input name="detail[<?= $key ?>][conv_factor]" hidden class="form-control form-control-sm" value="<?= $value->conv_factor ?>">
                                    </td>
                                    <td><input name="detail[<?= $key ?>][nama_barang]" class="form-control form-control-sm" value="<?= $value->nama_barang ?>" readonly></td>
                                    <td><input name="detail[<?= $key ?>][harga_satuan]" class="form-control form-control-sm" value="<?= $value->harga_satuan ?>"></td>
                                    <td><input name="detail[<?= $key ?>][satuan]" class="form-control form-control-sm" value="<?= $value->satuan ?>" readonly></td>
                                    <td><input name="detail[<?= $key ?>][qty1]" class="form-control form-control-sm" value="<?= $value->qty1 ?>"></td>
                                    <td><input name="detail[<?= $key ?>][qty2]" class="form-control form-control-sm" value="<?= $value->qty2 ?>"></td>
                                    <td><input name="detail[<?= $key ?>][jml_harga]" class="form-control form-control-sm" value="<?= $value->jml_harga ?>" readonly></td>
                                    <td><input name="detail[<?= $key ?>][disc_1_perc]" class="form-control form-control-sm" value="<?= $value->disc_1_perc ?>"></td>
                                    <td><input name="detail[<?= $key ?>][disc_1_rp]" class="form-control form-control-sm" value="<?= $value->disc_1_rp ?>"></td>
                                    <td><input name="detail[<?= $key ?>][disc_2_perc]" class="form-control form-control-sm" value="<?= $value->disc_2_perc ?>"></td>
                                    <td><input name="detail[<?= $key ?>][disc_2_rp]" class="form-control form-control-sm" value="<?= $value->disc_2_rp ?>"></td>
                                    <td><input name="detail[<?= $key ?>][total]" class="form-control form-control-sm" value="<?= $value->total ?>" readonly></td>
                                    <td><button type="button" class="btn btn-danger btnRemove">X</button></td>
                                </tr>
                            <?php endforeach ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <hr>
    <div class="row mt-3 justify-content-between">
        <div class="col-md-4">
            <div class="form-group">
                <label for="id_setupbuku">Rekening</label>
                <select class="form-control" name="id_setupbuku" required>
                    <option value="" hidden>-- Pilih Rekening --</option>
                    <?php foreach ($dtrekening as $key => $value) : ?>
                        <option value="<?= esc($value->id_setupbuku) ?>" <?= ($data->id_setupbuku ?? old('id_setupbuku')) == $value->id_setupbuku ? 'selected' : '' ?>>
                            <?= esc($value->kode_setupbuku . '-' . $value->nama_setupbuku) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="sub_total">Sub Total</label>
                <input type="text" id="sub_total" class="form-control" name="sub_total" value="<?= number_format(($data->sub_total ?? old('sub_total')) ?: 0, 0, ',', '.') ?>" readonly>
            </div>
            <div class="form-row">
                <div class="form-group col-lg-6">
                    <label for="disc_cash">Disc Cash %</label>
                    <input type="number" id="disc_cash" class="form-control " name="disc_cash" value="<?= ($data->disc_cash ?? old('disc_cash')) ?>">
                </div>
                <div class="form-group col-lg-6">
                    <label for="disc_cash_rp">Disc</label>
                    <input type="text" class="form-control" id="disc_cash_rp" name="disc_cash_rp" readonly value="<?= number_format(($data->disc_cash_rp ?? old('disc_cash_rp')) ?: 0, 0, ',', '.') ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="dpp">DPP</label>
                <input type="text" class="form-control" readonly name="dpp" value="<?= $data->dpp ?? 0 ?>">
            </div>

            <div class="form-row justify-content-between">
                <div class="form-group col-lg-4 ">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="ppn_option" value="exclude" <?= ($data->ppn_option ?? '' == 'exclude') ? 'checked' : '' ?>> Exclude
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="ppn_option" value="include" <?= ($data->ppn_option ?? '' == 'include') ? 'checked' : '' ?>> Include
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="ppn_option" value="non_ppn" <?= ($data->ppn_option ?? '' == 'non_ppn') ? 'checked' : '' ?>> Non PPN
                    </div>
                </div>
                <div class="form-group col-lg-8">
                    <label for="ppn">PPN (%)</label>
                    <input type="number" id="ppn" class="form-control" name="ppn" value="<?= ($data->ppn ?? old('ppn')) ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="grand_total">Grand Total</label>
                <input type="text" id="grand_total" class="form-control" name="grand_total" value="<?= number_format(($data->grand_total ?? old('grand_total')) ?: 0, 0, ',', '.') ?>" readonly>
            </div>

            <div class="form-group">
                <label>Tunai</label>
                <input type="text" id="tunai" class="form-control" name="tunai" value="<?= number_format(($data->tunai ?? old('tunai')) ?: 0, 0, ',', '.') ?>">
            </div>

            <div class="form-group">
                <label for="hutang">Hutang</label>
                <input type="text" id="hutang" class="form-control" name="hutang" value="<?= number_format(($data->hutang ?? old('hutang')) ?: 0, 0, ',', '.') ?>" readonly>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?php if ($isEdit ?? false): ?>
            <button type="submit" class="btn btn-success">Update</button>
        <?php else: ?>
            <button type="reset" class="btn btn-danger mr-3">Reset</button>
            <button type="submit" class="btn btn-success">Simpan</button>
        <?php endif; ?>
    </div>
</form>