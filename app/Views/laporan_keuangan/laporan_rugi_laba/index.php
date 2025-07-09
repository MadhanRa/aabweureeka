<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>
<title>Akuntansi Eureeka &mdash; Laporan Rugi Laba</title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <h1>Laporan Rugi Laba</h1>
  </div>

  <div class="card">
    <div class="card-header">
      <div class="card-header-action">
        <a href="<?= base_url('laporan_rugi_laba/printPDF?pertanggal=' . $pertanggal) ?>" class="btn btn-success" target="_blank">
          <i class="fas fa-print"></i> Cetak PDF
        </a>
      </div>
    </div>
    <div class="card-body">
      <form method="GET" action="<?= base_url('laporan_rugi_laba') ?>">
        <div class="d-flex align-items-end">
          <div class="col-md-3">
            <label for="pertanggal">Per Tanggal</label>
            <input type="date" name="pertanggal" class="form-control" value="<?= $pertanggal ?>">
          </div>
        </div>
        <div class="d-flex align-items-end mt-3">
          <div class="col-md-auto">
            <button type="submit" class="btn btn-primary mt-4">Filter</button>
          </div>
        </div>
      </form>

      <div class="table-responsive mt-5">
        <div class="container my-4 neraca">
          <div class="row">
            <!-- AKTIVA -->
            <div class="col-md-6">
              <table class="table table-bordered table-sm">
                <thead class="bg-header">
                  <tr>
                    <th colspan="2">Keterangan</th>
                  </tr>
                </thead>
                <tbody>
                  <tr class="text-bold">
                    <td>Penjualan</td>
                    <td></td>
                  </tr>
                  <tr>
                    <td>PENJUALAN USAHA</td>
                    <td class="text-right"><?= "Rp " . number_format($penjualan_usaha, 0, ',', '.') ?></td>
                  </tr>
                  <tr class="table-total">
                    <td>Jumlah</td>
                    <td class="text-right"><?= "Rp " . number_format($total_penjualan, 0, ',', '.') ?></td>
                  </tr>
                  <tr class="text-bold">
                    <td>Pengurangan Penjualan</td>
                    <td></td>
                  </tr>
                  <tr>
                    <td>PENGURANGAN PENJUALAN</td>
                    <td class="text-right"><?= "Rp " . number_format($pengurangan_penjualan, 0, ',', '.') ?></td>
                  </tr>
                  <tr class="table-total">
                    <td>Jumlah</td>
                    <td class="text-right"><?= "Rp " . number_format($total_pengurangan_penjualan, 0, ',', '.') ?></td>
                  </tr>
                  <tr class="text-bold">
                    <td>Harga Pokok Penjualan</td>
                    <td></td>
                  </tr>
                  <tr>
                    <td>HARGA POKOK PENJUALAN</td>
                    <td class="text-right"><?= "Rp " . number_format($hpp, 0, ',', '.') ?></td>
                  </tr>
                  <tr class="table-total">
                    <td>Jumlah</td>
                    <td class="text-right"><?= "Rp " . number_format($total_hpp, 0, ',', '.') ?></td>
                  </tr>
                  <tr class="text-bold">
                    <td>Biaya Operasional</td>
                    <td></td>
                  </tr>
                  <tr>
                    <td>BIAYA PENJUALAN</td>
                    <td class="text-right"><?= "Rp " . number_format($biaya_penjualan, 0, ',', '.') ?></td>
                  </tr>
                  <tr>
                    <td>BIAYA ADMINISTRASI & UMUM</td>
                    <td class="text-right"><?= "Rp " . number_format($biaya_administrasi, 0, ',', '.') ?></td>
                  </tr>
                  <tr class="table-total">
                    <td>Jumlah</td>
                    <td class="text-right"><?= "Rp " . number_format($total_biaya_operasional, 0, ',', '.') ?></td>
                  </tr>
                  <tr class="text-bold">
                    <td>Biaya Produksi</td>
                    <td></td>
                  </tr>
                  <tr>
                    <td>BIAYA PRODUKSI</td>
                    <td class="text-right"><?= "Rp " . number_format($biaya_produksi, 0, ',', '.') ?></td>
                  </tr>
                  <tr class="table-total">
                    <td>Jumlah</td>
                    <td class="text-right"><?= "Rp " . number_format($total_biaya_produksi, 0, ',', '.') ?></td>
                  </tr>
                  <tr class="text-bold">
                    <td>Pendapatan (Biaya) Non Operasional</td>
                    <td></td>
                  </tr>
                  <tr>
                    <td>PNO - PENDAPATAN NON OPERASIONAL</td>
                    <td class="text-right"><?= "Rp " . number_format($pno, 0, ',', '.') ?></td>
                  </tr>
                  <tr>
                    <td>BNO - BIAYA NON OPERASIONAL</td>
                    <td class="text-right"><?= "Rp " . number_format($bno, 0, ',', '.') ?></td>
                  </tr>
                  <tr class="table-total">
                    <td>Jumlah</td>
                    <td class="text-right"><?= "Rp " . number_format($total_non_operasional, 0, ',', '.') ?></td>
                  </tr>
                  <tr class="text-bold">
                    <td>Pajak Penghasilan</td>
                    <td></td>
                  </tr>
                  <tr>
                    <td>PAJAK PENGHASILAN</td>
                    <td class="text-right"><?= "Rp " . number_format($pajak_penghasilan, 0, ',', '.') ?></td>
                  </tr>
                  <tr class="table-total">
                    <td>Jumlah</td>
                    <td class="text-right"><?= "Rp " . number_format($total_pajak_penghasilan, 0, ',', '.') ?></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?= $this->endSection(); ?>