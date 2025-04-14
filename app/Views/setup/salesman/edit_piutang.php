<div class="modal fade" tabindex="-1" role="dialog" id="modalEdit">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Piutang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('setup/salesman/piutang/' . $data->id_hutang_piutang . '/edit', ['id' => 'form-edit-piutang']) ?>
            <input type="hidden" name="_method" value="PUT">
            <div class="modal-body">
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" class="form-control" name="tanggal" placeholder="Tanggal" value="<?= $data->tanggal ?>" required>
                </div>
                <div class="form-group">
                    <label>Nota</label>
                    <input type="text" class="form-control" name="nota" placeholder="No. Nota" value="<?= $data->nota ?>" required>
                </div>
                <div class="form-group">
                    <label>Tanggal Jatuh Tempo</label>
                    <input type="date" class="form-control" name="tanggal_jt" placeholder="Tanggal JT" value="<?= $data->tanggal_jt ?>" required>
                </div>
                <div class="form-group">
                    <label>Saldo</label>
                    <input type="text" class="form-control display-price" id="display_saldo" placeholder="Saldo" oninput="formatHarga(this, 'saldo')" value="Rp <?= number_format(floatval($data->saldo), 0, ',', '.') ?>" required>
                    <input type="hidden" name="saldo" id="saldo" value="<?= $data->saldo ?>">
                </div>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success btn-edit">Update</button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#form-edit-piutang').submit(function(e) {
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
                    // Update saldo detail
                    if (response.updatedSaldo !== undefined) {
                        // Format the saldo with Indonesian formatting
                        const formattedSaldo = 'Rp ' +
                            new Intl.NumberFormat('id-ID', {
                                maximumFractionDigits: 0,
                                useGrouping: true
                            }).format(response.updatedSaldo);
                        $('#saldo-detail').text(formattedSaldo);
                    }
                    reload_table();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + '\n' + xhr.responseText + '\n' + thrownError);
                }
            })
        });
    });
</script>