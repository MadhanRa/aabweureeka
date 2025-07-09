<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>
<title>Akuntansi Eureeka &mdash; Laporan Neraca</title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <h1>Laporan Neraca</h1>
  </div>

  <div class="card">
    <div class="card-header">
      <div class="card-header-action">
        <a href="<?= base_url('laporan_neraca/printPDF?pertanggal=' . $pertanggal) ?>" class="btn btn-success" target="_blank">
          <i class="fas fa-print"></i> Cetak PDF
        </a>
      </div>
    </div>
    <div class="card-body">
      <form method="GET" action="<?= base_url('laporan_neraca') ?>">
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
                    <th colspan="2">Aktiva</th>
                  </tr>
                </thead>
                <tbody>
                  <tr class="text-bold">
                    <td>Aktiva Lancar</td>
                    <td></td>
                  </tr>
                  <tr>
                    <td>KAS</td>
                    <td class="text-right"><?= "Rp " . number_format($kas, 0, ',', '.') ?></td>
                  </tr>
                  <tr>
                    <td>BANK</td>
                    <td class="text-right text-danger"><?= "Rp " . number_format($bank, 0, ',', '.') ?></td>
                  </tr>
                  <tr>
                    <td>PIUTANG USAHA - CUSTOMER</td>
                    <td class="text-right"><?= "Rp " . number_format($piutang_usaha, 0, ',', '.') ?></td>
                  </tr>
                  <tr>
                    <td>PIUTANG USAHA - PRINCIPAL</td>
                    <td class="text-right"><?= "Rp " . number_format($piutang_usaha_principal, 0, ',', '.') ?></td>
                  </tr>
                  <tr>
                    <td>PIUTANG BG/CEK MUNDUR</td>
                    <td class="text-right"><?= "Rp " . number_format($piutang_bg_mundur, 0, ',', '.') ?></td>
                  </tr>
                  <tr>
                    <td>PIUTANG KARYAWAN</td>
                    <td class="text-right"><?= "Rp " . number_format($piutang_karyawan, 0, ',', '.') ?></td>
                  </tr>
                  <tr>
                    <td>PIUTANG LAIN-LAIN</td>
                    <td class="text-right"><?= "Rp " . number_format($piutang_lain, 0, ',', '.') ?></td>
                  </tr>
                  <tr>
                    <td>PERSEDIAAN BAHAN</td>
                    <td class="text-right"><?= "Rp " . number_format($persediaan_bahan, 0, ',', '.') ?></td>
                  </tr>
                  <tr>
                    <td>PERSEDIAAN PACKAGING</td>
                    <td class="text-right"><?= "Rp " . number_format($persediaan_packaging, 0, ',', '.') ?></td>
                  </tr>
                  <tr>
                    <td>PERSEDIAAN BARANG JADI</td>
                    <td class="text-right"><?= "Rp " . number_format($persediaan_barang_jadi, 0, ',', '.') ?></td>
                  </tr>
                  <tr>
                    <td>PERSEDIAAN DLM PROSES</td>
                    <td class="text-right"><?= "Rp " . number_format($persediaan_barang_proses, 0, ',', '.') ?></td>
                  </tr>
                  <tr>
                    <td>PERSEDIAAN MATERIAL</td>
                    <td class="text-right"><?= "Rp " . number_format($persediaan_material, 0, ',', '.') ?></td>
                  </tr>
                  <tr>
                    <td>UANG MUKA PEMBELIAN</td>
                    <td class="text-right"><?= "Rp " . number_format($uang_muka_pembelian, 0, ',', '.') ?></td>
                  </tr>
                  <tr>
                    <td>PAJAK DIBAYAR DIMUKA</td>
                    <td class="text-right"><?= "Rp " . number_format($pajak_dimuka, 0, ',', '.') ?></td>
                  </tr>
                  <tr>
                    <td>BIAYA DIBAYAR DIMUKA</td>
                    <td class="text-right"><?= "Rp " . number_format($biaya_dimuka, 0, ',', '.') ?></td>
                  </tr>
                  <tr>
                    <td>BIAYA PRA OPERASIONAL</td>
                    <td class="text-right"><?= "Rp " . number_format($biaya_praoperasional, 0, ',', '.') ?></td>
                  </tr>
                  <tr class="table-total">
                    <td>Jumlah</td>
                    <td class="text-right"><?= "Rp " . number_format($total_aktiva_lancar, 0, ',', '.') ?></td>
                  </tr>
                  <tr class="text-bold">
                    <td>Aktiva Tetap</td>
                    <td></td>
                  </tr>
                  <tr>
                    <td>HARGA PEROLEHAN</td>
                    <td class="text-right"><?= "Rp " . number_format($harga_perolehan, 0, ',', '.') ?></td>
                  </tr>
                  <tr class="table-total">
                    <td>Jumlah</td>
                    <td class="text-right"><?= "Rp " . number_format($total_aktiva_tetap, 0, ',', '.') ?></td>
                  </tr>
                  <tr class="text-bold">
                    <td>Aktiva Akumulasi Penyusutan</td>
                    <td></td>
                  </tr>
                  <tr>
                    <td>AKUMULASI PENYUSUTAN</td>
                    <td class="text-right"><?= "Rp " . number_format($akumulasi_penyusutan, 0, ',', '.') ?></td>
                  </tr>
                  <tr class="table-total">
                    <td>Jumlah</td>
                    <td class="text-right"><?= "Rp " . number_format($total_akumulasi_penyusutan, 0, ',', '.') ?></td>
                  </tr>
                  <tr class="text-bold">
                    <td>Aktiva Lainnya</td>
                    <td></td>
                  </tr>
                  <tr>
                    <td>AKTIVA TETAP LAIN-LAIN</td>
                    <td class="text-right"><?= "Rp " . number_format($aktiva_tetap_lain, 0, ',', '.') ?></td>
                  </tr>
                  <tr class="table-total">
                    <td>Jumlah</td>
                    <td class="text-right"><?= "Rp " . number_format($total_aktiva_lainnya, 0, ',', '.') ?></td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- PASIVA -->
            <div class="col-md-6">
              <table class="table table-bordered table-sm">
                <thead class="bg-header">
                  <tr>
                    <th colspan="2">Pasiva</th>
                  </tr>
                </thead>
                <tbody>
                  <tr class="text-bold">
                    <td>Hutang Lancar</td>
                    <td></td>
                  </tr>
                  <tr>
                    <td>UANG MUKA PENJUALAN</td>
                    <td class="text-right"><?= "Rp " . number_format($uang_muka_penjualan, 0, ',', '.') ?></td>
                  </tr>
                  <tr>
                    <td>HUTANG USAHA</td>
                    <td class="text-right"><?= "Rp " . number_format($hutang_usaha, 0, ',', '.') ?></td>
                  </tr>
                  <tr>
                    <td>HUTANG RETUR PENJUALAN</td>
                    <td class="text-right"><?= "Rp " . number_format($hutang_retur_penjualan, 0, ',', '.') ?></td>
                  </tr>
                  <tr>
                    <td>HUTANG PAJAK</td>
                    <td class="text-right"><?= "Rp " . number_format($hutang_pajak, 0, ',', '.') ?></td>
                  </tr>
                  <tr>
                    <td>HUTANG BG/CEK MUNDUR</td>
                    <td class="text-right"><?= "Rp " . number_format($hutang_bg_mundur, 0, ',', '.') ?></td>
                  </tr>
                  <tr>
                    <td>BIAYA YMH DIBAYAR</td>
                    <td class="text-right"><?= "Rp " . number_format($biaya_ymh, 0, ',', '.') ?></td>
                  </tr>
                  <tr>
                    <td>HUTANG LAIN-LAIN</td>
                    <td class="text-right"><?= "Rp " . number_format($hutang_lain, 0, ',', '.') ?></td>
                  </tr>
                  <tr class="table-total">
                    <td>Jumlah</td>
                    <td class="text-right"><?= "Rp " . number_format($total_hutang_lancar, 0, ',', '.') ?></td>
                  </tr>
                  <tr class="text-bold">
                    <td>Hutang Jangka Panjang</td>
                    <td></td>
                  </tr>
                  <tr>
                    <td>HUTANG BANK</td>
                    <td class="text-right"><?= "Rp " . number_format($hutang_bank, 0, ',', '.') ?></td>
                  </tr>
                  <tr class="table-total">
                    <td>Jumlah</td>
                    <td class="text-right"><?= "Rp " . number_format($total_hutang_panjang, 0, ',', '.') ?></td>
                  </tr>
                  <tr class="text-bold">
                    <td>Modal</td>
                    <td></td>
                  </tr>
                  <tr>
                    <td>MODAL DISETOR</td>
                    <td class="text-right"><?= "Rp " . number_format($modal_disetor, 0, ',', '.') ?></td>
                  </tr>
                  <tr class="table-total">
                    <td>Jumlah</td>
                    <td class="text-right"><?= "Rp " . number_format($total_modal, 0, ',', '.') ?></td>
                  </tr>
                  <tr class="text-bold">
                    <td>Saldo Laba</td>
                    <td></td>
                  </tr>
                  <tr>
                    <td>SALDO LABA</td>
                    <td class="text-right"><?= "Rp " . number_format($saldo_laba, 0, ',', '.') ?></td>
                  </tr>
                  <tr>
                    <td>LABA TAHUN BERJALAN</td>
                    <td class="text-right"><?= "Rp " . number_format($laba_tahun, 0, ',', '.') ?></td>
                  </tr>
                  <tr>
                    <td>LABA BULAN BERJALAN</td>
                    <td class="text-right"><?= "Rp " . number_format($laba_bulan, 0, ',', '.') ?></td>
                  </tr>
                  <tr class="table-total">
                    <td>Jumlah</td>
                    <td class="text-right"><?= "Rp " . number_format($total_saldo_laba, 0, ',', '.') ?></td>
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