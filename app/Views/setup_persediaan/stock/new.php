<div class="modal fade" tabindex="-1" role="dialog" id="modalTambah">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Stock</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('setup_persediaan/stock', ['id' => 'form-tambah-stock']) ?>
            <div class="modal-body">
                <div class="form-group row">
                    <label for="kode" class="col-sm-3 col-form-label">Kode</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="kode" placeholder="Kode" name="kode" required>
                        <div class="invalid-feedback errorKode">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Nama Barang </label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="nama_barang" placeholder="Nama Barang" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Group</label>
                    <div class="col-sm-9">
                        <select class="form-control" name="id_group" required>
                            <option value="" hidden>--Pilih Group--</option>
                            <?php foreach ($dtgroup as $group) : ?>
                                <option value="<?= $group->id_group ?>"><?= $group->kode_group . ' - ' . $group->nama_group ?></option>
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
                                <option value="<?= $kelompok->id_kelompok ?>"><?= $kelompok->kode_kelompok . ' - ' . $kelompok->nama_kelompok ?></option>
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
                                <option value="<?= $satuan->id_satuan ?>"><?= $satuan->kode_satuan . ' - ' . $satuan->nama_satuan ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Faktor Konversi</label>
                    <div class="col-sm-9"><input type="number" class="form-control" name="conv_factor" placeholder="Faktor Konversi" required></div>

                </div>

                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Satuan 2</label>
                    <div class="col-sm-9">
                        <select class="form-control" name="id_satuan2" required>
                            <option value="" hidden>--Pilih Satuan--</option>
                            <?php foreach ($dtsatuan as $satuan) : ?>
                                <option value="<?= $satuan->id_satuan ?>"><?= $satuan->kode_satuan . ' - ' . $satuan->nama_satuan ?></option>
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
                                <option value="<?= $supplier->id_setupsupplier ?>"><?= $supplier->kode . ' - ' . $supplier->nama ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Stock Minimal </label>
                    <div class="col-sm-9"><input type="text" class="form-control" name="min_stock" placeholder="Stock Minimal" required></div>
                </div>

            </div>
            <div class="modal-footer bg-whitesmoke br">
                <div class="form-group">
                    <button type="reset" class="btn btn-danger">Reset</button>
                    <button type="submit" class="btn btn-success btn-simpan">Simpan Data</button>
                </div>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#form-tambah-stock').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                beforeSend: function() {
                    $('.btn-simpan').addClass('disabled');
                    $('.btn-simpan').addClass('btn-progress');
                },
                complete: function() {
                    $('.btn-simpan').removeClass('disabled');
                    $('.btn-simpan').removeClass('btn-progress');
                },
                success: function(response) {
                    if (response.error) {
                        if (response.error.kode) {
                            $('#kode').addClass('is-invalid');
                            $('.errorKode').html(response.error.kode);
                        } else {
                            $('#kode').removeClass('is-invalid');
                            $('.errorKode').html('');
                        }
                    } else {
                        iziToast.success({
                            title: 'Sukses',
                            message: 'Data berhasil disimpan',
                            position: 'topCenter',
                            titleSize: '20',
                            messageSize: '20',
                            layout: 2,
                        });

                        $('#modalTambah').modal('hide');
                        reload_table();
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + '\n' + xhr.responseText + '\n' + thrownError);
                }
            })
        });
    });
</script>