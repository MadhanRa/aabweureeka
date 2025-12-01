<div class="card">
    <div class="card-header">
        <h4>Detail Salesman</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <table class="table w-auto">
                    <tr>
                        <td><strong>Kode Salesman</strong></td>
                        <td>:</td>
                        <td><?= $dtsalesman->kode_salesman ?></td>
                    </tr>
                    <tr>
                        <td><strong>Nama Salesman</strong></td>
                        <td>:</td>
                        <td><?= $dtsalesman->nama_salesman ?></td>
                    </tr>
                    <tr>
                        <td><strong>Lokasi</strong></td>
                        <td>:</td>
                        <td><?= $dtsalesman->nama_lokasi ?></td>
                    </tr>
                    <tr>
                        <td><strong>Saldo</strong></td>
                        <td>:</td>
                        <td class="saldo-detail-<?= $dtsalesman->id_salesman ?>"><strong><?= 'Rp ' . number_format($dtsalesman->saldo, 0, ',', '.') ?></strong></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-8">
                <!-- Tombol Tambah Piutang Salesman -->
                <button class="btn btn-primary mt-3" id="btn-modal-piutang"><i class="fas fa-plus"></i> Tambah Piutang</button>
                <div class="table-responsive mt-3" id="piutang-table">

                </div>
            </div>
        </div>
    </div>
</div>


<form method="post" class="modal-part" id="piutang-modal-body" action="<?= site_url('setup/salesman/' . $dtsalesman->id_salesman . '/piutang') ?> ">
    <?= csrf_field() ?>

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
        <input type="date" class="form-control" name="tgl_jatuhtempo" placeholder="Tanggal JT" required>
    </div>
    <div class="form-group">
        <label>Saldo</label>
        <input type="text" class="form-control display-price" id="display_saldo" placeholder="Saldo" oninput="formatHarga(this, 'saldo')" required>
        <input type="hidden" name="saldo" id="saldo">
    </div>
</form>

<script>
    function reload_table() {
        $.ajax({
            url: "<?= site_url('setup/salesman/') ?>" + <?= $dtsalesman->id_salesman ?> + '/piutang',
            type: "GET",
            success: function(response) {
                $('#piutang-table').html(response.data);
            }
        });
    }
    $(document).ready(function() {
        reload_table();

        $("#btn-modal-piutang").fireModal({
            title: 'Tambah Piutang',
            body: $('#piutang-modal-body'),
            autoFocus: true,
            onFormSubmit: function(modal, e, form) {
                $.ajax({
                    url: $(e.target).attr('action'), // Use the form's action attribute
                    type: 'POST',
                    data: $(e.target).serialize(), // Serialize the form data
                    success: function(response) {
                        if (response.success) {
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

                            form.stopProgress();

                            $.destroyModal(modal);
                            reload_table();
                        } else {
                            // Handle error response
                            alert(response.message || 'Gagal menyimpan data');
                            form.stopProgress();
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        console.error('Error:', error);
                        form.stopProgress();
                        alert('Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
                    }
                })
                e.preventDefault(); // Prevent default form submission
            },
            buttons: [{
                    text: 'Batal',
                    class: 'btn btn-secondary btn-shadow',
                    handler: function(modal) {
                        $.destroyModal(modal);
                    }
                },
                {
                    text: 'Simpan',
                    submit: true,
                    class: 'btn btn-success btn-shadow',
                    handler: function(modal) {}
                }
            ]
        });
    });

    // Fungsi untuk memformat angka ke dalam format Rupiah
    function formatHarga(input, hiddenFieldId) {
        // Strip formatting characters first
        let rawValue = input.value.replace(/[^\d]/g, '');

        // Update the hidden field with the raw numeric value
        document.getElementById(hiddenFieldId).value = rawValue;

        // Format the display value
        let formattedValue = formatRupiah(rawValue);
        input.value = formattedValue;
    }

    // Fungsi untuk format angka menjadi Rupiah
    function formatRupiah(angka) {
        if (!angka) return '';
        let numberString = String(angka);
        let formattedNumber = numberString.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        return 'Rp ' + formattedNumber;
    }

    // Make sure form submission includes the hidden values
    document.querySelector('form').addEventListener('submit', function(e) {
        // Ensure hidden fields have values before submission
        document.querySelectorAll('.display-price').forEach(function(input) {
            const displayId = input.id;
            const hiddenId = displayId.replace('display_', '');
            const rawValue = input.value.replace(/[^\d]/g, '');
            document.getElementById(hiddenId).value = rawValue;
        });
    });
</script>