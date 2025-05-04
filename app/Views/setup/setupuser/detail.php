<div class="card">
    <div class="card-header">
        <h4>Detail User Stock Opname</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-4">
                <table class="table w-auto">
                    <tr>
                        <td><strong>Kode User</strong></td>
                        <td>:</td>
                        <td><?= $data->kode_user ?></td>
                    </tr>
                    <tr>
                        <td><strong>Nama User</strong></td>
                        <td>:</td>
                        <td><?= $data->nama_user ?></td>
                    </tr>
                    <tr>
                        <td><strong>Kode Aktivasi</strong></td>
                        <td>:</td>
                        <td><?= $data->kode_aktivasi ?></td>
                    </tr>
                    <tr>
                        <td><strong>Status</strong></td>
                        <td>:</td>
                        <td><span class="badge badge-<?= ($data->nonaktif == 0) ? 'info' : 'warning' ?>"><?= ($data->nonaktif == 0) ? 'Aktif' : 'Nonaktif' ?></span></td>
                    </tr>
                </table>
            </div>
            <div class="col-lg-4">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr class="eureeka-table-header">
                                <th>Kode</th>
                                <th>Nama Lokasi</th>
                                <th>Check</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dtlokasi as $key => $value) : ?>
                                <tr>
                                    <td><?= $value->kode_lokasi ?></td>
                                    <td><?= $value->nama_lokasi ?></td>
                                    <td>
                                        <?= in_array($value->id_lokasi, $dtuserlokasi) ? '<i class="fas fa-check-square"></i>' : '' ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>