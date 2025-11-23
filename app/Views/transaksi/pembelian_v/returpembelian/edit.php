<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>

<section class="section">
    <div class="section-header">
        <a href="<?= site_url('returpembelian') ?>" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>Edit Transaksi Retur Pembelian</h4>
                <div class="card-header-action">
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalLookupReturPembelian">Lookup</button>
                </div>
            </div>
            <div class="card-body">
                <?= $this->include('transaksi/pembelian_v/returpembelian/_form') ?>
            </div>
        </div>
    </div>
</section>

<?= $this->include('transaksi/pembelian_v/returpembelian/_modals') ?>

<script>
    document.addEventListener("input", function() {
        const qty1 = parseFloat(document.getElementById("qty_1").value) || 0;
        const qty2 = parseFloat(document.getElementById("qty_2").value) || 0;
        const hargaSatuan = parseFloat(document.getElementById("harga_satuan").value) || 0;
        const disc1 = parseFloat(document.getElementById("disc_1").value) || 0;
        const disc2 = parseFloat(document.getElementById("disc_2").value) || 0;
        const discCash = parseFloat(document.getElementById("disc_cash").value) || 0;
        const ppn = parseFloat(document.getElementById("ppn").value) || 0;

        // Kalkulasi Jumlah Harga
        const jmlHarga = hargaSatuan * (qty1 + qty2);
        document.getElementById("jml_harga").value = formatRupiah(jmlHarga);

        // Kalkulasi Total setelah diskon bertingkat
        let totalAfterDisc1 = jmlHarga - (jmlHarga * disc1 / 100); // Diskon pertama
        let totalAfterDisc2 = totalAfterDisc1 - (totalAfterDisc1 * disc2 / 100); // Diskon kedua

        // Update total setelah diskon bertingkat
        const total = totalAfterDisc2; // Total setelah diskon bertingkat
        document.getElementById("total").value = formatRupiah(total);

        // Kalkulasi Sub Total setelah diskon cash
        const subTotal = total - (total * discCash / 100); // Sub total setelah diskon cash
        document.getElementById("sub_total").value = formatRupiah(subTotal);

        // Kalkulasi Grand Total setelah PPN
        const grandTotal = subTotal + (subTotal * ppn / 100); // Grand total dengan PPN
        document.getElementById("grand_total").value = formatRupiah(grandTotal);
    });

    // Fungsi untuk format angka ke Rupiah
    function formatRupiah(angka) {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR"
        }).format(angka);
    }
</script>

<?= $this->endSection(); ?>