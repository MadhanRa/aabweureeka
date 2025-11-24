<form id="formPenjualan" action="<?= $formAction ?>" data-stock-url="<?= site_url('setup_persediaan/stock/pilihItemGudang') ?>" method="POST">
    <?= $formMethod ?>
    <input type="hidden" id="main_csrf" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

    <div class="row">
        <div class="col-md-3 col-xl-2">
            <div class="form-group">
                <!-- Tanggal -->
                <label>Tanggal</label>
                <input type="date" class="form-control form-control" name="tanggal" value="<?= $data->tanggal ?? old('tanggal') ?>" required>
            </div>
        </div>
        <div class="col-md-3 col-xl-3">
            <div class="form-group">
                <label>Pelanggan</label>
                <select class="form-control form-control" name="id_pelanggan" id="id_pelanggan" required>
                    <option value="" hidden>-- Pilih Pelanggan --</option>
                    <?php foreach ($dtpelanggan as $key => $value) : ?>
                        <option value="<?= esc($value->id_pelanggan) ?>" <?= ($data->id_pelanggan ?? old('id_pelanggan')) == $value->id_pelanggan ? 'selected' : '' ?>>
                            <?= esc($value->kode_pelanggan . ' - ' . $value->nama_pelanggan) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="col-md-3 col-xl-1">
            <div class="form-group">
                <!-- TOP -->
                <label>TOP</label>
                <input type="text" class="form-control form-control" name="TOP" value="<?= $data->TOP ?? old('TOP') ?>" required>
            </div>
        </div>
        <div class="col-md-3 col-xl-2">
            <div class="form-group">
                <!-- Tanggal Jatuh Tempo -->
                <label>Tanggal Jatuh Tempo</label>
                <input type="date" class="form-control form-control" name="tgl_jatuhtempo" value="<?= $data->tgl_jatuhtempo ?? old('tgl_jatuhtempo') ?>" readonly>
            </div>
        </div>
        <div class="col-md-3 col-xl-4">
            <div class="form-group">
                <label>Salesman</label>
                <select class="form-control form-control" name="id_salesman" id="id_salesman" required>
                    <option value="" hidden>-- Pilih Salesman --</option>
                    <?php foreach ($dtsalesman as $key => $value) : ?>
                        <option value="<?= esc($value->id_salesman) ?>" <?= ($data->id_salesman ?? old('id_salesman')) == $value->id_salesman ? 'selected' : '' ?>>
                            <?= esc($value->kode_salesman . ' - ' . $value->nama_salesman) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label>Nota</label>
                <input type="text" class="form-control form-control" name="nota" value="<?= $data->nota ?? old('nota') ?>" required>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Lokasi</label>
                <select class="form-control form-control" name="id_lokasi" id="id_lokasi" required>
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
            <div class="form-group p-3">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="ppn_option" value="exclude" id="inlineRadio1" <?= ($data->ppn_option ?? old('ppn_option')) == 'exclude' ? 'checked' : '' ?>>
                    <label class="form-check-label" for="inlineRadio1">Exclude</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="ppn_option" value="include" id="inlineRadio2" <?= ($data->ppn_option ?? old('ppn_option')) == 'include' ? 'checked' : '' ?>>
                    <label class="form-check-label" for="inlineRadio2">Include</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="ppn_option" value="non_ppn" id="inlineRadio3" <?= ($data->ppn_option ?? old('ppn_option')) == 'non_ppn' ? 'checked' : '' ?>>
                    <label class="form-check-label" for="inlineRadio3">Non PPN</label>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>No. FP</label>
                <input type="text" class="form-control form-control" name="no_fp" value="<?= $data->no_fp ?? old('no_fp') ?>">
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
                                <tr>
                                    <td>
                                        <input name="detail[<?= $key ?>][id_detail]" value="<?= $value->id ?>" hidden>
                                        <input name="detail[<?= $key ?>][id_stock]" value="<?= $value->id_stock ?>" hidden>
                                        <input name="detail[<?= $key ?>][kode]" class="form-control form-control-sm" value="<?= $value->kode ?>">
                                        <input name="detail[<?= $key ?>][conv_factor]" hidden class="form-control form-control-sm" value="<?= $value->conv_factor ?>">
                                    </td>
                                    <td><input name="detail[<?= $key ?>][nama_barang]" class="form-control form-control-sm" value="<?= $value->nama_barang ?>" readonly></td>
                                    <td><input name="detail[<?= $key ?>][satuan]" class="form-control form-control-sm" value="<?= $value->satuan ?>" readonly></td>
                                    <td>
                                        <input name="detail[<?= $key ?>][harga_satuan]" class="form-control form-control-sm" value="<?= $value->harga_satuan ?>" readonly>
                                        <input name="detail[${rowIndex}][harga_satuan_include]" type="hidden" value="<?= $value->harga_jualinc ?>">
                                        <input name="detail[${rowIndex}][harga_satuan_exclude]" type="hidden" value="<?= $value->harga_jualexc ?>">
                                    </td>
                                    <td><input name="detail[<?= $key ?>][qty1]" class="form-control form-control-sm" value="<?= $value->qty1 ?>"></td>
                                    <td><input name="detail[<?= $key ?>][qty2]" class="form-control form-control-sm" value="<?= $value->qty2 ?>"></td>
                                    <td><input name="detail[<?= $key ?>][jml_harga]" class="form-control form-control-sm" value="<?= $value->jml_harga ?>" readonly></td>
                                    <td><input name="detail[<?= $key ?>][disc_1_perc]" class="form-control form-control-sm" value="<?= $value->disc_1_perc ?>"></td>
                                    <td><input name="detail[<?= $key ?>][disc_1_rp]" class="form-control form-control-sm" value="<?= $value->disc_1_rp ?>"></td>
                                    <td><input name="detail[<?= $key ?>][disc_2_perc]" class="form-control form-control-sm" value="<?= $value->disc_2_perc ?>" readonly></td>
                                    <td><input name="detail[<?= $key ?>][disc_2_rp]" class="form-control form-control-sm" value="<?= $value->disc_2_rp ?>" readonly></td>
                                    <td><input name="detail[<?= $key ?>][total]" class="form-control form-control-sm" value="<?= $value->total ?>" readonly></td>
                                    <td><button type="button" class="btn btn-danger btnRemove">X</button></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <hr>
    <div class="row mt-3 justify-content-between">
        <div class="col-md-4">
            <div class="form-group p-3 w-50 border">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="opsi_pembayaran" id="inlineRadio1" value="kredit" <?= ($data->opsi_pembayaran ?? old('opsi_pembayaran')) == 'kredit' ? 'checked' : '' ?>>
                    <label class="form-check-label" for="inlineRadio1">Kredit</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="opsi_pembayaran" id="inlineRadio2" value="tunai" <?= ($data->opsi_pembayaran ?? old('opsi_pembayaran')) == 'tunai' ? 'checked' : '' ?>>
                    <label class="form-check-label" for="inlineRadio2">Tunai</label>
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
                    <input type="text" class="form-control" id="disc_cash_rp" name="disc_cash_rp" value="<?= number_format($data->disc_cash_rp ?? old('disc_cash_rp') ?: 0, 0, ',', '.') ?>" readonly>
                </div>
            </div>
            <div class="form-group">
                <label>Netto</label>
                <input type="text" class="form-control" readonly name="netto" value="<?= number_format($data->netto ?? old('netto') ?: 0, 0, ',', '.') ?>">
            </div>
            <div class="form-group">
                <label>PPN %</label>
                <input type="number" id="ppn" class="form-control" name="ppn" value="<?= $data->ppn ?? old('ppn') ?>" readonly>
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