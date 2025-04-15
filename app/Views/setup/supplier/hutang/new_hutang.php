<div class="modal fade" tabindex="-1" role="dialog" id="modalTambah">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Hutang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('setup/supplier/' . $id . '/hutang', ['id' => 'form-tambah-hutang']) ?>
            <div class="modal-body">
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" class="form-control" name="tanggal" placeholder="Tanggal" required>
                </div>
                <div class="form-group">
                    <label>Nota</label>
                    <input type="text" class="form-control" name="nota" placeholder="No. Nota" required>
                </div>
                <div class="form-group">
                    <label>Tanggal Jatuh Tempo</label>
                    <input type="date" class="form-control" name="tanggal_jt" placeholder="Tanggal JT" required>
                </div>
                <div class="form-group">
                    <label>Saldo</label>
                    <input type="text" class="form-control display-price" id="display_saldo" placeholder="Saldo" oninput="formatHarga(this, 'saldo')" required>
                    <input type="hidden" name="saldo" id="saldo">
                </div>

            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="reset" class="btn btn-danger">Reset</button>
                <button type="submit" class="btn btn-success btn-simpan">Simpan Data</button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<script>
    // Fungsi untuk format angka menjadi Rupiah
    function formatRupiah(angka) {
        if (!angka) return '';
        let numberString = String(angka);
        let formattedNumber = numberString.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        return 'Rp ' + formattedNumber;
    }
    // Fungsi untuk memformat angka ke dalam format Rupiah
    function formatHarga(input, hiddenFieldId) {
        // Strip formatting characters first
        let rawValue = $(input).val().replace(/[^\d]/g, '');

        // Update the hidden field with the raw numeric value
        $('#' + hiddenFieldId).val(rawValue);

        // Format the display value
        let formattedValue = formatRupiah(rawValue);
        $(input).val(formattedValue);
    }

    // Make sure form submission includes the hidden values
    $('form').on('submit', function(e) {
        // Ensure hidden fields have values before submission
        $('.display-price').each(function() {
            const displayId = $(this).attr('id');
            const hiddenId = displayId.replace('display_', '');
            const rawValue = $(this).val().replace(/[^\d]/g, '');
            $('#' + hiddenId).val(rawValue);
        });
    });

    $(document).ready(function() {
        $('#form-tambah-hutang').submit(function(e) {
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
                        return;
                    } else {
                        // Update saldo detail
                        if (response.updatedSaldo !== undefined) {
                            // Format the saldo with Indonesian formatting
                            const formattedSaldo = 'Rp ' +
                                new Intl.NumberFormat('id-ID', {
                                    maximumFractionDigits: 0,
                                    useGrouping: true
                                }).format(response.updatedSaldo);
                            $('.saldo-detail-' + response.id).text(formattedSaldo);
                        }

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