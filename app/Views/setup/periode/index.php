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
                <!-- Tampilkan pesan error -->
                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                <?php endif ?>
                <form method="post" action="<?= site_url('setup/periode') ?> ">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label>Bulan</label>
                        <select type="text" class="form-control" name="periode_bulan" required>
                            <option value="Januari">Januari</option>
                            <option value="Februari">Februari</option>
                            <option value="Maret">Maret</option>
                            <option value="April">April</option>
                            <option value="Mei">Mei</option>
                            <option value="Juni">Juni</option>
                            <option value="Juli">Juli</option>
                            <option value="Agustus">Agustus</option>
                            <option value="September">September</option>
                            <option value="Oktober">Oktober</option>
                            <option value="November">November</option>
                            <option value="Desember">Desember</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tahun</label>
                        <input type="text" class="form-control" name="periode_tahun" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Simpan Data</button>
                        <button type="reset" class="btn btn-danger">Reset</button>
                    </div>
                </form>
                <?php if (empty($dtperiode)) : ?>
                    <div class="alert alert-danger">
                        <h4 class="alert-heading">Data Kosong</h4>
                        <p>Silahkan tambahkan data periode terlebih dahulu.</p>
                    </div>
                <?php else : ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-md display nowrap compact eureeka-table" id="myTable">
                            <thead>
                                <tr class="eureeka-table-header">
                                    <th>No</th>
                                    <th>Bulan</th>
                                    <th>Tahun</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dtperiode as $key => $value) : ?>
                                    <tr>
                                        <td><?= $key + 1 ?></td>
                                        <td><?= $value->periode_bulan ?></td>
                                        <td><?= $value->periode_tahun ?></td>
                                        <td><?= $value->status ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                    </div>

            </div>
        </div>
    </div>

    </div>
</section>

<?= $this->endSection(); ?>