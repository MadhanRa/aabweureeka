<table class="table table-striped display eureeka-table nowrap compact" id="piutang-data-table">
    <thead>
        <tr class="eureeka-table-header">
            <th>Tanggal</th>
            <th>Nota</th>
            <th>Tanggal. JT</th>
            <th>Saldo</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <!-- TEMPAT FOREACH -->
        <?php
        foreach ($dtpiutang as $key => $data) :
        ?>
            <tr>
                <td><?= $data->tanggal ?></td>
                <td><?= $data->nota ?></td>
                <td><?= $data->tanggal_jt ?></td>
                <td>Rp <?= number_format((float)$data->saldo, 0, ',', '.') ?></td>

                <td class="text-center">
                    <!-- Tombol Edit Data -->
                    <button type='button' class="btn btn-warning btn-action mr-1" onclick="edit('<?= $data->id_hutang_piutang ?>')"><i class="fas fa-pencil-alt"></i></button>
                    <!-- Tombol Hapus Data -->
                    <button type='button' class="btn btn-danger btn-action" onclick="hapus(<?= $data->id_hutang_piutang ?>)">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
    $(document).ready(function() {
        $('#piutang-data-table').DataTable({
            columnDefs: [{
                targets: 4,
                orderable: false,
                searchable: false
            }],
        });
    });

    function edit(id) {
        $.ajax({
            url: "<?= site_url('setup/pelanggan/piutang') ?>/" + id + "/edit",
            type: "GET",
            dataType: "json",
            success: function(response) {
                $('#modalPlace').html(response.data).show();
                $('#modalEdit').modal('show');
            }
        });
    }

    function hapus(id) {
        swal({
                title: 'Hapus data?',
                text: 'Yakin ingin menghapus data ini?',
                icon: 'warning',
                buttons: ["Tidak", "Hapus"],
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: "<?= site_url('setup/pelanggan/piutang') ?>/" + id,
                        type: "DELETE",
                        headers: {
                            'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.success) {
                                iziToast.success({
                                    title: 'Sukses',
                                    message: response.message,
                                    position: 'topCenter',
                                    titleSize: '20',
                                    messageSize: '20',
                                    layout: 2,
                                });
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

                                reload_table();
                            }
                        }
                    });
                }
            });
    }
</script>