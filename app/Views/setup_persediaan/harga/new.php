<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>

<section class="section">
    <div class="section-header">
        <!-- <h1>APA INI</h1> -->
        <a href="<?= site_url('setup_persediaan/harga') ?>" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali </a>
    </div>

    <div class="section-body">
        <!-- HALAMAN DINAMIS -->
        <div class="card">
            <div class="card-header">
                <h4>Setup Harga</h4>
            </div>
            <div class="card-body">
                <div class="form-container">
                    <form method="post" action="<?= site_url('setup_persediaan/harga') ?>">
                        <?= csrf_field() ?>

                        <div class="form-group">
                            <label>Nama Barang</label>
                            <select class="form-control" name="id_stock" required>
                                <option value="" hidden>Pilih Nama Barang</option>
                                <?php foreach ($dtstock as $key => $value) : ?>
                                    <option value="<?= $value->id_stock ?>"><?= $value->kode . ' - ' . $value->nama_barang ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Harga Jual (EXC)</label>
                            <input type="text" class="form-control display-price" id="display_harga_jualexc" placeholder="Harga Jual (EXC)" oninput="formatHarga(this, 'harga_jualexc')" required>
                            <input type="hidden" name="harga_jualexc" id="harga_jualexc">
                        </div>
                        <div class="form-group">
                            <label>Harga Jual (INC)</label>
                            <input type="text" class="form-control display-price" id="display_harga_jualinc" placeholder="Harga Jual (INC)" oninput="formatHarga(this, 'harga_jualinc')" required>
                            <input type="hidden" name="harga_jualinc" id="harga_jualinc">
                        </div>
                        <div class="form-group">
                            <label>Harga Beli</label>
                            <input type="text" class="form-control display-price" id="display_harga_beli" placeholder="Harga Beli" oninput="formatHarga(this, 'harga_beli')" required>
                            <input type="hidden" name="harga_beli" id="harga_beli">
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