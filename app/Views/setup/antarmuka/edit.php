<?= $this->extend("/layout/backend") ?>;

<?= $this->section("content") ?>

<section class="section">
    <div class="section-header">
        <!-- <h1>APA INI</h1> -->
        <a href="<?= site_url('setup/antarmuka') ?>" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali </a>
    </div>

    <div class="section-body">
        <!-- HALAMAN DINAMIS -->
        <div class="card">
            <div class="card-header">
                <h4>Edit Data Interface</h4>
            </div>
            <div class="card-body">
                <form method="post" action="<?= site_url('setup/antarmuka/') . $dtantarmuka->id_interface ?> ">
                    <input type="hidden" name="_method" value="PUT">

                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Kas dan Setara Kas</label>
                                <input type="text" class="form-control" name="kas_setara" value="<?= $dtantarmuka->kas_setara ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Biaya</label>
                                <input type="text" class="form-control" name="biaya" value="<?= $dtantarmuka->biaya ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Hutang Dagang</label>
                                <input type="text" class="form-control" name="hutang" value="<?= $dtantarmuka->hutang ?>" required>
                            </div>
                            <div class="form-group">
                                <label>HPP</label>
                                <input type="text" class="form-control" name="hpp" value="<?= $dtantarmuka->hpp ?>" required>
                            </div>
                            <div class="form-group">
                                <label>BG Terima Mundur</label>
                                <input type="text" class="form-control" name="terima_mundur" value="<?= $dtantarmuka->terima_mundur ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Klasifikasi Laba Ditahan</label>
                                <input type="text" class="form-control" name="kl_laba_ditahan" value="<?= $dtantarmuka->kl_laba_ditahan ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Klasifikasi Hutang Lancar</label>
                                <input type="text" class="form-control" name="hutang_lancar" value="<?= $dtantarmuka->hutang_lancar ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Neraca Laba Bulan Berjalan</label>
                                <input type="text" class="form-control" name="neraca_laba" value="<?= $dtantarmuka->neraca_laba ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Piutang Salesman</label>
                                <input type="text" class="form-control" name="piutang_salesman" value="<?= $dtantarmuka->piutang_salesman ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Rekening Biaya Produksi</label>
                                <input type="text" class="form-control" name="rekening_biaya" value="<?= $dtantarmuka->rekening_biaya ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Piutang Dagang</label>
                                <input type="text" class="form-control" name="piutang_dagang" value="<?= $dtantarmuka->piutang_dagang ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Penjualan</label>
                                <input type="text" class="form-control" name="penjualan" value="<?= $dtantarmuka->penjualan ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Retur Penjualan</label>
                                <input type="text" class="form-control" name="retur_penjualan" value="<?= $dtantarmuka->retur_penjualan ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Disc. Penjualan</label>
                                <input type="text" class="form-control" name="diskon_penjualan" value="<?= $dtantarmuka->diskon_penjualan ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Laba Bulan Berjalan</label>
                                <input type="text" class="form-control" name="laba_bulan" value="<?= $dtantarmuka->laba_bulan ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Laba Tahun Berjalan</label>
                                <input type="text" class="form-control" name="laba_tahun" value="<?= $dtantarmuka->laba_tahun ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Laba Ditahan</label>
                                <input type="text" class="form-control" name="laba_ditahan" value="<?= $dtantarmuka->laba_ditahan ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Potongan Pembelian</label>
                                <input type="text" class="form-control" name="potongan_pembelian" value="<?= $dtantarmuka->potongan_pembelian ?>" required>
                            </div>
                            <div class="form-group">
                                <label>PPN Masukan</label>
                                <input type="text" class="form-control" name="ppn_masukan" value="<?= $dtantarmuka->ppn_masukan ?>" required>
                            </div>
                            <div class="form-group">
                                <label>PPN Keluaran</label>
                                <input type="text" class="form-control" name="ppn_keluaran" value="<?= $dtantarmuka->ppn_keluaran ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Potongan Penjualan</label>
                                <input type="text" class="form-control" name="potongan_penjualan" value="<?= $dtantarmuka->potongan_penjualan ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Bank</label>
                                <input type="text" class="form-control" name="bank" value="<?= $dtantarmuka->bank ?>" required>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <a href="<?= site_url('setup/antarmuka') ?>" class="btn btn-danger">Batal</a>
                        <button type="submit" class="btn btn-success">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
    // Fungsi untuk menangkap tombol Enter
    document.addEventListener("DOMContentLoaded", function() {
        // Ambil semua elemen input di dalam form
        const inputs = document.querySelectorAll('input.form-control');

        inputs.forEach((input, index) => {
            input.addEventListener('keydown', function(event) {
                if (event.key === "Enter") {
                    event.preventDefault(); // Mencegah submit form saat tekan Enter
                    const nextInput = inputs[index + 1]; // Ambil input berikutnya
                    if (nextInput) {
                        nextInput.focus(); // Pindahkan fokus ke input berikutnya
                    }
                }
            });
        });
    });
</script>

<?= $this->endSection(); ?>