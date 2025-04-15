<div class="card">
    <div class="card-header">
        <h4>Detail Pelanggan</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <table class="table w-auto">
                    <tr>
                        <td><strong>Kode Pelanggan</strong></td>
                        <td>:</td>
                        <td><?= $data->kode_pelanggan ?></td>
                    </tr>
                    <tr>
                        <td><strong>Nama Pelanggan</strong></td>
                        <td>:</td>
                        <td><?= $data->nama_pelanggan ?></td>
                    </tr>
                    <tr>
                        <td><strong>Alamat/Kota</strong></td>
                        <td>:</td>
                        <td><?= $data->alamat_pelanggan . '/' . $data->kota_pelanggan ?></td>
                    </tr>
                    <tr>
                        <td><strong>Nomor Telpon</strong></td>
                        <td>:</td>
                        <td><?= $data->telp_pelanggan ?></td>
                    </tr>
                    <tr>
                        <td><strong>Plafond</strong></td>
                        <td>:</td>
                        <td><?= $data->plafond ?></td>
                    </tr>
                    <tr>
                        <td><strong>NPWP</strong></td>
                        <td>:</td>
                        <td><?= $data->npwp . ' (' . $data->tipe . ')' ?></td>
                    </tr>
                    <tr>
                        <td><strong>Saldo</strong></td>
                        <td>:</td>
                        <td class="saldo-detail-<?= $data->id_pelanggan ?>"><strong><?= 'Rp ' . number_format($data->saldo, 0, ',', '.') ?></strong></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-8">
                <!-- Tombol Tambah Piutang Salesman -->
                <button class="btn btn-primary mt-3" id="btn-tambah-piutang"><i class="fas fa-plus"></i> Tambah Piutang</button>
                <div class="table-responsive mt-3" id="piutang-table">

                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function reload_table() {
        $.ajax({
            url: "<?= site_url('setup/pelanggan/') ?>" + <?= $data->id_pelanggan ?> + '/piutang',
            type: "GET",
            success: function(response) {
                $('#piutang-table').html(response.data);
            }
        });
    }


    $(document).ready(function() {
        reload_table();

        $("#btn-tambah-piutang").click(function() {
            $.ajax({
                url: "<?= site_url('setup/pelanggan/' . $data->id_pelanggan . '/piutang/new') ?>",
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