<?= $this->extend("layout/backend") ?>;

<?= $this->section("content") ?>

<section class="section">
    <div class="section-header">
        <!-- <h1>APA INI</h1> -->
        <a href="<?= site_url('setup/supplier') ?>" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali </a>
    </div>

    <div class="section-body">
        <!-- HALAMAN DINAMIS -->
        <div class="card">
            <div class="card-header">
                <h4>Tambah Supplier</h4>
            </div>
            <div class="card-body">
                <div class="form-container">
                    <form method="post" action="<?= site_url('setup/supplier') ?> ">
                        <?= csrf_field() ?>

                        <div class="form-group">
                            <label>Kode</label>
                            <input type="text" class="form-control" name="kode" placeholder="Kode" value="<?= $kode_supplier ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" class="form-control" name="nama" placeholder="Nama" required>
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <input type="text" class="form-control" name="alamat" placeholder="Alamat" required>
                        </div>
                        <div class="form-group">
                            <label>Telepon</label>
                            <input type="text" class="form-control" name="telepon" placeholder="Telepon" required>
                        </div>
                        <div class="form-group">
                            <label>Contact Person</label>
                            <input type="text" class="form-control" name="contact_person" placeholder="Contact Person" required>
                        </div>
                        <div class="form-group">
                            <label>NPWP</label>
                            <input type="text" class="form-control" name="npwp" placeholder="NPWP" required>
                        </div>
                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipe" id="inlineRadio1" value="exclude">
                                <label class="form-check-label" for="inlineRadio1">Exclude</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipe" id="inlineRadio2" value="include">
                                <label class="form-check-label" for="inlineRadio2">Include</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipe" id="inlineRadio3" value="non_ppn">
                                <label class="form-check-label" for="inlineRadio3">Non-PPN</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">Simpan Data</button>
                            <button type="reset" class="btn btn-danger">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection(); ?>