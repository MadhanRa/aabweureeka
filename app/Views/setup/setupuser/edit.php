<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>

<section class="section">
    <div class="section-header">
        <a href="<?= site_url('setupuser') ?>" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali </a>
    </div>

    <div class="section-body">
        <!-- HALAMAN DINAMIS -->
        <div class="card">
            <div class="card-header">
                <h4>Edit Setup User Stock Opname</h4>
            </div>
            <div class="card-body">
                <div class="form-container">
                    <form method="post" action="<?= site_url('setup/useropname/' . $dtsetupuser->id_user) ?>">
                        <input type="hidden" name="_method" value="PUT">
                        <?= csrf_field() ?>

                        <div class="form-group">
                            <label>Kode</label>
                            <input type="text" class="form-control" name="kode_user" value="<?= esc($dtsetupuser->kode_user) ?>" placeholder="Kode" required>
                        </div>

                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" class="form-control" name="nama_user" value="<?= esc($dtsetupuser->nama_user) ?>" placeholder="Nama" required>
                        </div>

                        <div class="form-group">
                            <label>Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" placeholder="(Kosongkan jika tidak diganti)">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Kode Aktivasi</label>
                            <input type="text" class="form-control" name="kode_aktivasi" value="<?= esc($dtsetupuser->kode_aktivasi) ?>" placeholder="Masukkan Kode Aktivasi" required>
                        </div>

                        <div class="form-group">
                            <label class="custom-switch mt-2">
                                <input type="hidden" name="nonaktif" value="0" <?= $dtsetupuser->nonaktif ? 'checked' : '' ?>>
                                <input type="checkbox" name="nonaktif" id="nonaktif" class="custom-switch-input" value="1" <?= $dtsetupuser->nonaktif ? 'checked' : '' ?>>
                                <span class="custom-switch-indicator"></span>
                                <span class="custom-switch-description">Non Aktif</span>
                            </label>
                        </div>

                        <div class="form-group">
                            <h4>Pilih Lokasi:</h4>
                            <div class="custom-switches-stacked mt-2">
                                <?php foreach ($dtlokasi as $lok) : ?>
                                    <label class="custom-switch mt-2">
                                        <input type="checkbox" name="lokasi[]" class="custom-switch-input" value="<?= $lok->id_lokasi; ?>" <?= in_array($lok->id_lokasi, $dtuserlokasi) ? 'checked' : '' ?>>
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description"><?= $lok->nama_lokasi; ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <a href="<?= site_url('setup/useropname') ?>" class="btn btn-danger">Batal</a>
                            <button type="submit" class="btn btn-success ml-3">Update Data</button>
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