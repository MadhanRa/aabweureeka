<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <!-- <h1>APA INI</h1> -->
    <a href="<?= site_url('transaksi/penjualan/penjualan') ?>" class="btn btn-primary">
      <i class="fas fa-arrow-left"></i> Kembali
    </a>
  </div>

  <div class="section-body">
    <!-- HALAMAN DINAMIS -->
    <div class="card">
      <div class="card-header">
        <h4>Transaksi Penjualan</h4>
      </div>
      <div class="card-body">
        <div class="form-container">
          <form method="post" action="<?= site_url('penjualan') ?>">
            <?= csrf_field() ?>

            <div class="form-group">
              <label>Tanggal</label>
              <input type="date" class="form-control" name="tanggal" value="<?= old('tanggal') ?>" required>
            </div>

            <div class="form-group">
              <label>Nota</label>
              <input type="text" class="form-control" name="nota" value="<?= old('nota') ?>" required>
            </div>

            <div class="form-group">
              <label>Pelanggan</label>
              <select class="form-control" name="id_pelanggan" required>
                <option value="" hidden>-- Pilih Pelanggan --</option>
                <?php foreach ($dtpelanggan as $key => $value) : ?>
                  <option value="<?= esc($value->id_pelanggan) ?>" <?= old('id_pelanggan') == $value->id_pelanggan ? 'selected' : '' ?>>
                    <?= esc($value->nama_pelanggan) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-group">
              <label>TOP</label>
              <input type="text" class="form-control" name="TOP" value="<?= old('TOP') ?>" required>
            </div>

            <div class="form-group">
              <label>Tanggal Jatuh Tempo</label>
              <input type="date" class="form-control" name="tgl_jatuhtempo" value="<?= old('tgl_jatuhtempo') ?>" readonly>
            </div>

            <div class="form-group">
              <label>Salesaman</label>
              <select class="form-control" name="id_salesman" required>
                <option value="" hidden>-- Pilih Salesman --</option>
                <?php foreach ($dtsalesman as $key => $value) : ?>
                  <option value="<?= esc($value->id_salesman) ?>" <?= old('id_salesman') == $value->id_salesman ? 'selected' : '' ?>>
                    <?= esc($value->nama_salesman) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-group">
              <label>Lokasi</label>
              <select class="form-control" name="id_lokasi" required>
                <option value="" hidden>-- Pilih Lokasi --</option>
                <?php foreach ($dtlokasi as $key => $value) : ?>
                  <option value="<?= esc($value->id_lokasi) ?>" <?= old('id_lokasi') == $value->id_lokasi ? 'selected' : '' ?>>
                    <?= esc($value->nama_lokasi) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-group">
              <label>No.FP</label>
              <input type="number" class="form-control" name="no_fp" value="<?= old('no_fp') ?>" required>
            </div>


            <div class="form-group">
              <input type="text" class="form-control" name="id_stock" hidden>
              <div class="row">
                <div class="col-3">
                  <label>kode Stock</label>
                  <input type="text" class="form-control" name="kode_stock" value="<?= old('kode_stock') ?>" required>
                </div>
                <div class="col">
                  <label>Nama Stock</label>
                  <input type="text" class="form-control" name="nama_stock" value="<?= old('nama_stock') ?>" required>
                </div>
              </div>
            </div>


            <div class="form-group">
              <label>Satuan</label>
              <select class="form-control" name="id_satuan" required>
                <option value="" hidden>-- Pilih Satuan --</option>
                <?php foreach ($dtsatuan as $key => $value) : ?>
                  <option value="<?= esc($value->id_satuan) ?>" <?= old('id_satuan') == $value->id_satuan ? 'selected' : '' ?>>
                    <?= esc($value->kode_satuan) ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <!-- <input type="text" class="form-control" name="satuan" value="<?= old('satuan') ?>" readonly> -->
            </div>


            <div class="form-group">
              <div class="row">
                <div class="col">
                  <label>Qty 1</label>
                  <input type="number" id="qty_1" class="form-control" name="qty_1" value="<?= old('qty_1') ?>" required>
                </div>
                <div class="col">
                  <label>Qty 2</label>
                  <input type="number" id="qty_2" class="form-control" name="qty_2" value="<?= old('qty_2') ?>">
                </div>
              </div>
            </div>

            <div class="form-group">
              <label>Harga Satuan</label>
              <input type="number" id="harga_satuan" class="form-control" name="harga_satuan" value="<?= old('harga_satuan') ?>" readonly>
            </div>

            <div class="form-group">
              <label>Jumlah Harga</label>
              <input type="text" id="jml_harga" class="form-control" name="jml_harga" value="<?= number_format(old('jml_harga') ?: 0, 0, ',', '.') ?>" readonly>
            </div>

            <div class="form-group">
              <label>Disc 1 (%)</label>
              <input type="number" id="disc_1" class="form-control" name="disc_1" value="<?= old('disc_1') ?>">
            </div>

            <div class="form-group">
              <label>Disc 2 (%)</label>
              <input type="number" id="disc_2" class="form-control" name="disc_2" value="<?= old('disc_2') ?>">
            </div>

            <div class="form-group">
              <label>Total</label>
              <input type="text" id="total" class="form-control" name="total" value="<?= number_format(old('total') ?: 0, 0, ',', '.') ?>" readonly>
            </div>

            <div class="form-group">
              <label for="tipe">Pembayaran</label>
              <select class="form-control" name="pembayaran" id="pembayaran" required>
                <option value="" disabled selected>Pilih Tipe</option>
                <option value="kredit" <?= old('pembayaran') == 'kredit' ? 'selected' : '' ?>>Kredit</option>
                <option value="tunai" <?= old('pembayaran') == 'tunai' ? 'selected' : '' ?>>Tunai</option>
              </select>
            </div>


            <div class="form-group">
              <div class="row">
                <div class="col-3">
                  <label for="tipe">Tipe</label>
                  <select class="form-control" name="tipe" id="tipe" required>
                    <option value="" disabled selected>Pilih Tipe</option>
                    <option value="exclude" <?= old('tipe') == 'exclude' ? 'selected' : '' ?>>Exclude</option>
                    <option value="include" <?= old('tipe') == 'include' ? 'selected' : '' ?>>Include</option>
                    <option value="non_ppn" <?= old('tipe') == 'non_ppn' ? 'selected' : '' ?>>Non PPN</option>
                  </select>
                </div>
                <div class="col">
                  <label>PPN (%)</label>
                  <input type="number" id="ppn" class="form-control" name="ppn" value="<?= old('ppn') ?>">
                </div>
              </div>
            </div>

            <div class="form-group">
              <label>Sub Total</label>
              <input type="text" id="sub_total" class="form-control" name="sub_total" value="<?= number_format(old('sub_total') ?: 0, 0, ',', '.') ?>" readonly>
            </div>

            <div class="form-group">
              <label>Discount Cash</label>
              <input type="number" id="disc_cash" class="form-control" name="disc_cash" value="<?= old('disc_cash') ?>">
            </div>

            <div class="form-group">
              <label>Grand Total</label>
              <input type="text" id="grand_total" class="form-control" name="grand_total" value="<?= number_format(old('grand_total') ?: 0, 0, ',', '.') ?>" readonly>
            </div>

            <div class="form-group">
              <label>NPWP</label>
              <input type="text" id="npwp" class="form-control" name="npwp" value="<?= old('npwp') ?>">
            </div>

            <div class="form-group">
              <label>Terbilang</label>
              <input type="text" id="terbilang" class="form-control" name="terbilang" value="<?= old('terbilang') ?>">
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
  $(document).ready(function() {
    // Initialize date handling
    initializeDateHandling();
  });

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

  /**
   * Initialize date calculation for payment terms
   */
  function initializeDateHandling() {
    const tanggalInput = document.querySelector('input[name="tanggal"]');
    const jatuhTempoInput = document.querySelector('input[name="tgl_jatuhtempo"]');
    const topInput = document.querySelector('input[name="TOP"]');

    // Set up event listeners
    if (tanggalInput && jatuhTempoInput && topInput) {
      tanggalInput.addEventListener('change', updateJatuhTempo);
      topInput.addEventListener('change', updateJatuhTempo);
      updateJatuhTempo(); // Initial calculation
    }

    function updateJatuhTempo() {
      if (!tanggalInput.value) return;

      try {
        // Parse the input date
        const tanggal = new Date(tanggalInput.value);

        // Get TOP value (default to 0 if not a number)
        const topValue = parseInt(topInput.value) || 0;

        // Add TOP days to the date
        tanggal.setDate(tanggal.getDate() + topValue);

        // Format the date as YYYY-MM-DD for the input
        const year = tanggal.getFullYear();
        const month = String(tanggal.getMonth() + 1).padStart(2, '0');
        const day = String(tanggal.getDate()).padStart(2, '0');

        // Update the due date field
        jatuhTempoInput.value = `${year}-${month}-${day}`;
      } catch (error) {
        console.error('Error calculating due date:', error);
      }
    }
  }
</script>

<?= $this->endSection(); ?>