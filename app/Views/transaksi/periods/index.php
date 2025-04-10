<?= $this->extend("layout/backend") ?>;

<?= $this->section("content") ?>
<title>Akuntansi Eureeka &mdash; Close Books</title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<section class="section">
    <div class="section-header">
        <h1>Fitur Tutup Buku Per Periode</h1>

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

    <?php if (session()->getFlashdata('Error')) : ?>
        <div class="alert alert-danger alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
                <?= session()->getFlashdata('Error') ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="section-body">
        <!-- HALAMAN DINAMIS -->
        <div class="card">
            <div class="card-header">
                <h4>Close Books Per Periode</h4>
                <!-- <div class="card-header-form">
                    <form action="<?= site_url('PeriodsController') ?>" method="POST">
                        <?= csrf_field() ?>
                        <div class="row">
                            <div class="col">
                                <a href="<?= base_url('PeriodsController/printPDF') ?>" class="btn btn-success" target="_blank">
                                    <i class="fas fa-print"></i> Cetak PDF
                                </a>
                            </div>
                        </div>
                    </form>
                </div> -->
                <div class="card-header-form" style="margin-left: 5px;">
                    <a href="<?= site_url('period-add') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Data</a>
                </div>


            </div>
            <div class="section-body">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-md display nowrap compact eureeka-table" id="myTable">
                            <thead>
                                <tr class="eureeka-table-header">
                                    <th>No</th>
                                    <th>Periode Bulan</th>
                                    <th>Periode Tahun</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- TEMPAT FOREACH -->
                                <?php foreach ($periods as $value) : ?>
                                    <tr>
                                        <td class="text-center"><?= $value->id ?></td>
                                        <td class="text-center"><?= $value->month ?></td>
                                        <td class="text-center"><?= $value->year ?></td>
                                        <td class="text-center">
                                            <?php if ($value->is_closed == 1) : ?>
                                                <span class="badge badge-danger">Closed</span>
                                            <?php else: ?>
                                                <span class="badge badge-success">Open</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-center">
                                            <?php if ($value->is_closed == 1) : ?>

                                            <?php else: ?>
                                                <a href="<?= site_url('close-period/close_book/' . $value->id)  ?>" class="btn btn-primary"><i class="fas fa-eye-alt btn-small"></i> Tutup</a>
                                            <?php endif; ?>
                                            <!-- Tombol Edit Data -->
                                            <a href="<?= site_url('close-period/report/' . $value->id) ?>" class="btn btn-warning"><i class="fas fa-eye-alt btn-small"></i> Detail</a>
                                            <!-- <input type="hidden" name="_method" value="PUT"> -->

                                            <!-- Tombol Hapus Data -->
                                            <form action="<?= site_url('close-period/' . $value->id) ?>" method="post" id="del-<?= $value->id ?>" class="d-inline">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button class="btn btn-danger btn-small" data-confirm="Hapus Data....?" data-confirm-yes="hapus(<?= $value->id ?>)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
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

    </div>
</section>

<?= $this->endSection(); ?>