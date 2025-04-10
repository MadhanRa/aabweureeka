<?= $this->extend("/layout/backend") ?>;

<?= $this->section("content") ?>
<title>Setup Buku - Akuntansi Eureeka</title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<section class="section">
    <div class="section-header">
        <h1>Buku Besar</h1>
    </div>

    <!-- untuk menangkap session success dengan bawaan with -->

    <?php if (session()->getFlashdata('Sukses')) : ?>
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
                <?= session()->getFlashdata('Sukses') ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="section-body">
        <!-- HALAMAN DINAMIS -->
        <div class="card">
            <div class="card-header">
                <h4>Setup Buku Besar</h4>
                <div class="card-header-action">
                    <a href="<?= site_url('setup/buku/new') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Data</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped display eureeka-table nowrap compact" id="myTable">
                        <thead>
                            <tr class="eureeka-table-header">
                                <th>No</th>
                                <th>Kode Akun</th>
                                <th>Nama Akun</th>
                                <th>Pos Neraca</th>
                                <th>Tanggal</th>
                                <th>Saldo (Debit/Kredit)</th>
                                <th>action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dtsetupbuku as $key => $value) : ?>
                                <tr>
                                    <td><?= $key + 1 ?></td>
                                    <td><?= $value->kode_setupbuku ?></td>
                                    <td><?= $value->nama_setupbuku ?></td>
                                    <td><?= $value->nama_posneraca ?></td>
                                    <td><?= $value->tanggal_awal_saldo ?></td>
                                    <td><?= 'Rp ' . number_format($value->saldo_setupbuku, 0, ',', '.') ?></td>

                                    <td class="text-center">
                                        <!-- Tombol Edit Data -->
                                        <a href="<?= site_url('setup/buku/' . $value->id_setupbuku . '/edit') ?>" class="btn btn-warning"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        <!-- Tombol Hapus Data -->
                                        <form action="<?= site_url('setup/buku/' . $value->id_setupbuku) ?>" method="post" id="del-<?= $value->id_setupbuku ?>" class="d-inline">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button class="btn btn-danger btn-small" data-confirm="Hapus Data....?" data-confirm-yes="hapus(<?= $value->id_setupbuku ?>)"><i class="fas fa-trash"></i> Hapus</button>
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
                targets: 6,
                orderable: false,
                searchable: false
            }],
        });
    });
</script>

<?= $this->endSection(); ?>