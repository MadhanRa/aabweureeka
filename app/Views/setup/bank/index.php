<?= $this->extend("layout/backend") ?>;

<?= $this->section("content") ?>

<section class="section">
    <div class="section-header">
        <h1>Bank</h1>
    </div>

    <div class="section-body">
        <!-- HALAMAN DINAMIS -->
        <div class="card">
            <div class="card-header">
                <h4>Setup Bank</h4>
                <div class="card-header-action">
                    <a href="<?= site_url('setup/bank/new') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Data</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped display eureeka-table nowrap compact" id="myTable">
                        <thead>
                            <tr class="eureeka-table-header">
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama Bank</th>
                                <th>Rekening</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dtsetupbank as $key => $value) : ?>
                                <tr>
                                    <td><?= $key + 1 ?></td>
                                    <td><?= $value->kode_setupbank ?></td>
                                    <td><?= $value->nama_setupbank ?></td>
                                    <td><?= $value->nama_setupbuku ?></td>

                                    <td class="text-center">
                                        <!-- Tombol Edit Data -->
                                        <a href="<?= site_url('setup/bank/' . $value->id_setupbank . '/edit') ?>" class="btn btn-warning"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        <!-- Tombol Hapus Data -->
                                        <form action="<?= site_url('setup/bank/' . $value->id_setupbank) ?>" method="post" id="del-<?= $value->id_setupbank ?>" class="d-inline">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button class="btn btn-danger btn-small" data-confirm="Hapus Data....?" data-confirm-yes="hapus(<?= $value->id_setupbank ?>)"><i class="fas fa-trash"></i> Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>


<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            columnDefs: [{
                targets: 4,
                orderable: false,
                searchable: false
            }],
        });
    });
</script>

<?= $this->endSection(); ?>