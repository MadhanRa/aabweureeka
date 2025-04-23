<?= $this->extend("layout/backend") ?>;

<?= $this->section("content") ?>

<section class="section">
    <div class="section-header">
        <!-- <h1>APA INI</h1> -->
        <a href="<?= site_url('setup/useropname') ?>" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali </a>
    </div>

    <div class="section-body">
        <!-- HALAMAN DINAMIS -->
        <div class="card">
            <div class="card-header">
                <h4>Tambah User Stock Opname</h4>
            </div>
            <div class="card-body">
                <div class="form-container">
                    <form method="post" action="<?= site_url('setup/useropname') ?> ">
                        <?= csrf_field() ?>

                        <div class="form-group">
                            <label>Kode</label>
                            <input type="text" class="form-control" name="kode_user" placeholder="Masukkan Kode User" required>
                        </div>

                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" class="form-control" name="nama_user" placeholder="Masukkan Nama User" required>
                        </div>

                        <div class="form-group">
                            <label>Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password" required>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Kode Aktivasi</label>
                            <input type="text" class="form-control" name="kode_aktivasi" placeholder="Masukkan Kode Aktivasi" required>
                        </div>

                        <div class="form-group">
                            <label class="custom-switch mt-2">
                                <input type="hidden" name="nonaktif" value="0">
                                <input type="checkbox" name="nonaktif" id="nonaktif" class="custom-switch-input" value="1">
                                <span class="custom-switch-indicator"></span>
                                <span class="custom-switch-description">Non Aktif</span>
                            </label>
                        </div>

                        <div class="form-group">
                            <h4>Pilih Lokasi:</h4>
                            <div class="custom-switches-stacked mt-2">
                                <?php foreach ($dtlokasi as $lok) : ?>
                                    <label class="custom-switch mt-2">
                                        <input type="checkbox" name="lokasi[]" class="custom-switch-input" value="<?= $lok->id_lokasi; ?>">
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description"><?= $lok->nama_lokasi; ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="reset" class="btn btn-danger mr-3">Reset</button>
                            <button type="submit" class="btn btn-success">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const icon = this.querySelector('i');

        // Toggle the password visibility
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
</script>


<?= $this->endSection(); ?>