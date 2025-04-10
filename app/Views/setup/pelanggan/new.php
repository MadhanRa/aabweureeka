<?= $this->extend("layout/backend") ?>;

<?= $this->section("content") ?>

<section class="section">
    <div class="section-header">
        <a href="<?= site_url('setup/pelanggan') ?>" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali </a>
    </div>

    <div class="section-body">
        <!-- HALAMAN DINAMIS -->
        <div class="card">
            <div class="card-header">
                <h4>Tambah Pelanggan</h4>
            </div>
            <div class="card-body">
                <div class="form-container">
                    <form method="post" action="<?= site_url('setup/pelanggan') ?> ">
                        <?= csrf_field() ?>

                        <div class="form-group">
                            <label>Kode</label>
                            <input type="text" class="form-control" name="kode_pelanggan" placeholder="Kode" value="<?= $kode_pelanggan ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" class="form-control" name="nama_pelanggan" placeholder="Nama" required>
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <input type="text" class="form-control" name="alamat_pelanggan" placeholder="Alamat" required>
                        </div>
                        <div class="form-group">
                            <label>Kota</label>
                            <input type="text" class="form-control" name="kota_pelanggan" placeholder="Kota" required>
                        </div>
                        <div class="form-group">
                            <label>Telepon</label>
                            <input type="text" class="form-control" name="telp_pelanggan" placeholder="Telepon" required>
                        </div>
                        <div class="form-group">
                            <label>Class</label>
                            <input type="text" class="form-control" name="class_pelanggan" placeholder="Class" required>
                        </div>
                        <div class="form-group">
                            <label>Plafond</label>
                            <input type="text" class="form-control" placeholder="Plafond" oninput="formatHarga(this, 'plafond')" required>
                            <input type="hidden" name="plafond" id="plafond">
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

<script>
    // Fungsi untuk memformat angka ke dalam format Rupiah
    function formatHarga(input, hiddenFieldId) {
        // Strip formatting characters first
        let rawValue = input.value.replace(/[^\d]/g, '');

        // Update the hidden field with the raw numeric value
        document.getElementById(hiddenFieldId).value = rawValue;

        // Format the display value
        let formattedValue = formatRupiah(rawValue);
        input.value = formattedValue;
    }

    // Fungsi untuk format angka menjadi Rupiah
    function formatRupiah(angka) {
        if (!angka) return '';
        let numberString = String(angka);
        let formattedNumber = numberString.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        return 'Rp ' + formattedNumber;
    }

    // Make sure form submission includes the hidden values
    document.querySelector('form').addEventListener('submit', function(e) {
        // Ensure hidden fields have values before submission
        document.querySelectorAll('.display-price').forEach(function(input) {
            const displayId = input.id;
            const hiddenId = displayId.replace('display_', '');
            const rawValue = input.value.replace(/[^\d]/g, '');
            document.getElementById(hiddenId).value = rawValue;
        });
    });
</script>

<?= $this->endSection(); ?>