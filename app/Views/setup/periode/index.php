<?= $this->extend("/layout/backend") ?>;

<?= $this->section("content") ?>
<title>Setup Periode Akuntansi &mdash; Akuntansi Eureeka</title>
<?= $this->endSection(); ?>

<!-- untuk menangkap session success dengan bawaan with -->


<?= $this->section("content") ?>

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

<section class="section">
    <div class="section-header">
        <h1>Setup Periode</h1>
    </div>

    <div class="section-body">
        <!-- HALAMAN DINAMIS -->
        <div class="card">
            <?php if (!empty($dtperiode)) : ?>
                <div class="card-header">
                    <h4>Setup Periode</h4>
                </div>
            <?php endif; ?>
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
</section>

<?= $this->endSection(); ?>