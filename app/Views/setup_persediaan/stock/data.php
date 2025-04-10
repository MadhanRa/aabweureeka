<table class="table table-striped display eureeka-table nowrap compact" id="myTable">
    <thead>
        <tr class="eureeka-table-header">
            <th>#</th>
            <th>Kode</th>
            <th>Nama Barang</th>
            <th>Group</th>
            <th>Kelompok</th>
            <th>Satuan</th>
            <th>Conv. Rate</th>
            <th>Satuan2</th>
            <th>Supplier</th>
            <th>Minimum</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <!-- TEMPAT FOREACH -->
        <?php
        foreach ($dtstock as $key => $data) :
        ?>
            <tr>
                <td><?= $key + 1 ?></td>
                <td><?= $data->kode ?></td>
                <td><?= $data->nama_barang ?></td>
                <td><?= $data->nama_group ?></td>
                <td><?= $data->nama_kelompok ?></td>
                <td><?= $data->kode_satuan ?></td>
                <td><?= $data->conv_factor ?></td>
                <td><?= $data->kode_satuan2 ?></td>
                <td><?= $data->nama_setupsupplier ?></td>
                <td><?= $data->min_stock ?></td>

                <td class="text-center">
                    <!-- Tombol Edit Data -->
                    <button type='button' class="btn btn-warning btn-action mr-1" onclick="edit('<?= $data->id_stock ?>')"><i class="fas fa-pencil-alt"></i></button>
                    <!-- Tombol Hapus Data -->
                    <button type='button' class="btn btn-danger btn-action" onclick="hapus(<?= $data->id_stock ?>)">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            columnDefs: [{
                targets: 10,
                orderable: false,
                searchable: false
            }],
        });
    });

    function edit(id) {
        $.ajax({
            url: "<?= site_url('setup_persediaan/stock') ?>/" + id + "/edit",
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
                        url: "<?= site_url('setup_persediaan/stock') ?>/" + id,
                        type: "DELETE",
                        headers: {
                            'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                        },
                        dataType: "json",
                        success: function(response) {
                            console.log(response);
                            if (response.success) {
                                iziToast.success({
                                    title: 'Sukses',
                                    message: response.success,
                                    position: 'topCenter',
                                    titleSize: '20',
                                    messageSize: '20',
                                    layout: 2,
                                });
                                reload_table();
                            }
                        }
                    });
                }
            });
    }
</script>