<div class="card">
    <div class="card-header">
        <h4>Detail Supplier</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-4">
                <table class="table w-auto">
                    <tr>
                        <td><strong>Kode Supplier</strong></td>
                        <td>:</td>
                        <td><?= $data->kode ?></td>
                    </tr>
                    <tr>
                        <td><strong>Nama Supplier</strong></td>
                        <td>:</td>
                        <td><?= $data->nama ?></td>
                    </tr>
                    <tr>
                        <td><strong>Alamat</strong></td>
                        <td>:</td>
                        <td><?= $data->alamat ?></td>
                    </tr>
                    <tr>
                        <td><strong>Nomor Telpon</strong></td>
                        <td>:</td>
                        <td><?= $data->telepon ?></td>
                    </tr>
                    <tr>
                        <td><strong>Contact Person</strong></td>
                        <td>:</td>
                        <td><?= $data->contact_person ?></td>
                    </tr>
                    <tr>
                        <td><strong>NPWP</strong></td>
                        <td>:</td>
                        <td><?= $data->npwp . ' (' . $data->tipe . ')' ?></td>
                    </tr>
                    <tr>
                        <td><strong>Saldo</strong></td>
                        <td>:</td>
                        <td class="saldo-detail-<?= $data->id_setupsupplier ?>"><strong><?= 'Rp ' . number_format($data->saldo, 0, ',', '.') ?></strong></td>
                    </tr>
                </table>
            </div>
            <div class="col-lg-8">
                <!-- Tombol Tambah Hutang Supplier -->
                <button class="btn btn-primary mt-3" id="btn-tambah-hutang"><i class="fas fa-plus"></i> Tambah Hutang</button>
                <div class="table-responsive mt-3" id="hutang-table">

                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function reload_table() {
        $.ajax({
            url: "<?= site_url('setup/supplier/') ?>" + <?= $data->id_setupsupplier ?> + '/hutang',
            type: "GET",
            success: function(response) {
                $('#hutang-table').html(response.data);
            }
        });
    }


    $(document).ready(function() {
        reload_table();

        $("#btn-tambah-hutang").click(function() {
            $.ajax({
                url: "<?= site_url('setup/supplier/' . $data->id_setupsupplier . '/hutang/new') ?>",
                type: "GET",
                dataType: "json",
                success: function(response) {
                    $('#modalPlace').html(response.data).show();
                    $('#modalTambah').modal('show');
                }
            });
        });
    });
</script>