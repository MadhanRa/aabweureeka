<form id="formReturPembelian" action="<?= $formAction ?>" data-stock-url="<?= site_url('setup_persediaan/stock/pilihItem') ?>" method="POST">
    <?= $formMethod ?>
    <input type="hidden" id="main_csrf" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <input type="hidden" name="id_pembelian" value="<?= $data->id_pembelian ?? '' ?>">

    <div class="row">
        <div class="col-lg-2">
            <div class="form-group">
                <!-- Tanggal -->
                <label>Tanggal</label>
                <input type="date" class="form-control" name="tanggal" value="<?= $data->tanggal ?? old('tanggal') ?>" required>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group">
                <!-- Supplier -->
                <label>Supplier</label>
                <select class="form-control" name="id_setupsupplier" id="id_setupsupplier" required>
                    <option value="" hidden>-- Pilih Supplier --</option>
                    <?php foreach ($dtsetupsupplier as $key => $value) : ?>
                        <option value="<?= esc($value->id_setupsupplier) ?>" <?= ($data->id_setupsupplier ?? old('id_setupsupplier')) == $value->id_setupsupplier ? 'selected' : '' ?>>
                            <?= esc($value->kode . ' - ' . $value->nama) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Lokasi</label>
                <select class="form-control" name="id_lokasi" required>
                    <option value="" hidden>-- Pilih Lokasi --</option>
                    <?php foreach ($dtlokasi as $key => $value) : ?>
                        <option value="<?= esc($value->id_lokasi) ?>" <?= ($data->id_lokasi ?? old('id_lokasi')) == $value->id_lokasi ? 'selected' : '' ?>>
                            <?= esc($value->kode_lokasi . ' - ' . $value->nama_lokasi) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Nota</label>
                <input type="text" class="form-control" name="nota" value="<?= old('nota') ?>" required>
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
                                    <td><input name="detail[<?= $key ?>][satuan]" class="form-control form-control-sm" value="<?= $value->satuan ?>" readonly></td>
                                    <td><input name="detail[<?= $key ?>][harga_satuan]" class="form-control form-control-sm" value="<?= $value->harga_satuan ?>"></td>
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
    <div class="row mt-5 justify-content-between">
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
                <!-- Tanggal Pembelian -->
                <label>Tanggal Pembelian</label>
                <input type="date" class="form-control" name="tanggal_pembelian" value="<?= ($data->tanggal_pembelian ?? old('tanggal_pembelian')) ?>" readonly>
            </div>
            <div class="form-group">
                <label>Nota Pembelian</label>
                <div class="form-row">
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="nota_pembelian" value="<?= ($data->nota_pembelian ?? old('nota_pembelian')) ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#modalNotaPembelian">cari</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Sub Total</label>
                <input type="text" id="sub_total" class="form-control" name="sub_total" value="<?= number_format($data->sub_total ?? old('sub_total') ?: 0, 0, ',', '.') ?>" readonly>
            </div>
            <div class="form-row">
                <div class="form-group col-lg-6">
                    <label>Disc Cash %</label>
                    <input type="number" id="disc_cash" class="form-control " name="disc_cash" value="<?= $data->disc_cash ?? old('disc_cash') ?>">
                </div>
                <div class="form-group col-lg-6">
                    <label>Disc Cash Rp</label>
                    <input type="text" class="form-control" id="disc_cash_rp" name="disc_cash_rp" value="<?= number_format(old('disc_cash_rp') ?: 0, 0, ',', '.') ?>">
                </div>
            </div>
            <div class="form-group">
                <label>DPP</label>
                <input type="text" class="form-control" readonly name="dpp" id="dpp" value="<?= number_format($data->dpp ?? old('dpp') ?: 0, 0, ',', '.') ?>">
            </div>

            <div class="form-row justify-content-between">
                <div class="form-group col-lg-4 ">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="ppn_option" value="exclude" <?= ($data->ppn_option ?? old('ppn_option')) == 'exclude' ? 'checked' : '' ?>> Exclude
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="ppn_option" value="include" <?= ($data->ppn_option ?? old('ppn_option')) == 'include' ? 'checked' : '' ?>> Include
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="ppn_option" value="non_ppn" <?= ($data->ppn_option ?? old('ppn_option')) == 'non_ppn' ? 'checked' : '' ?>> Non PPN
                    </div>
                </div>
                <div class="form-group col-lg-6">
                    <label>PPN (%)</label>
                    <input type="number" id="ppn" class="form-control" name="ppn" value="<?= $data->ppn ?? old('ppn') ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Grand Total</label>
                <input type="text" id="grand_total" class="form-control" name="grand_total" value="<?= number_format($data->grand_total ?? old('grand_total') ?: 0, 0, ',', '.') ?>" readonly>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?php if ($isEdit ?? false): ?>
            <button type="submit" class="btn btn-success">Update</button>
        <?php else: ?>
            <button type="submit" class="btn btn-success">Simpan</button>
        <?php endif; ?>
    </div>
</form>