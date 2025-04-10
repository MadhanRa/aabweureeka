<?= $this->extend("layout/backend") ?>;

<?= $this->section("content") ?>

<section class="section">
    <div class="section-header">
        <a href="<?= site_url('setup/salesman') ?>" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali </a>
    </div>

    <div class="section-body">
        <!-- HALAMAN DINAMIS -->
        <div class="card">
            <div class="card-header">
                <h4>Edit Salesman</h4>
            </div>
            <div class="card-body">
                <div class="form-container">
                    <form method="post" action="<?= site_url('setup/salesman/' . $dtsalesman->id_salesman) ?>">
                        <input type="hidden" name="_method" value="PUT">
                        <?= csrf_field() ?>

                        <div class="form-group">
                            <label>Kode</label>
                            <input type="text" class="form-control" name="kode_salesman" placeholder="Kode" value="<?= isset($dtsalesman->kode_salesman) ? $dtsalesman->kode_salesman : old('kode_salesman') ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" class="form-control" name="nama_salesman" value="<?= esc($dtsalesman->nama_salesman) ?>" placeholder="Nama" required>
                        </div>
                        <div class="form-group">
                            <label>Lokasi</label>
                            <select type="text" class="form-control" name="id_lokasi" required>
                                <option value="">-- Pilih Lokasi --</option>
                                <?php foreach ($dtlokasi as $lokasi) : ?>
                                    <option value="<?= $lokasi->id_lokasi ?>" <?= ($lokasi->id_lokasi == $dtsalesman->id_lokasi) ? 'selected' : '' ?>><?= $lokasi->nama_lokasi ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-success">Update Data</button>
                            <a href="<?= site_url('setup/salesman') ?>" class="btn btn-danger">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</section>



<?= $this->endSection(); ?>