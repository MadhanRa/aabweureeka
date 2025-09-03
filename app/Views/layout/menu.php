<!-- DASHBOARD SETUP -->
<li class="menu-header">Dashboard Setup</li>
<li class="nav-item dropdown">
  <a href="#" class="nav-link text-dark has-dropdown"><i class="fas fa-cogs"></i><span>Setup</span></a>
  <ul class="dropdown-menu">
    <li><a class="nav-link text-dark" href="<?= site_url('setup/periode') ?>">Periode Akuntansi</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('setup/antarmuka') ?>">Interface</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('setup/klasifikasi') ?>">Klasifikasi</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('setup/posneraca') ?>">Pos Laporan Keuangan</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('setup/buku') ?>">Buku Besar</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('setup/salesman') ?>">Salesman</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('setup/pelanggan') ?>">Pelanggan</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('setup/supplier') ?>">Supplier</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('setup/biaya') ?>">Klasifikasi Biaya dan Pendapatan</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('setup/kelompokproduksi') ?>">Kelompok Produksi</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('setup/bank') ?>">Bank</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('setup/useropname') ?>">User Stock Opname</a></li>
  </ul>
</li>
<li class="nav-item dropdown">
  <a href="#" class="nav-link text-dark has-dropdown"><i class="fas fa-box-open"></i><span>Setup Persediaan</span></a>
  <ul class="dropdown-menu">
    <li><a class="nav-link text-dark" href="<?= site_url('setup_persediaan/satuan') ?>">Satuan</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('setup_persediaan/lokasi') ?>">Lokasi</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('setup_persediaan/group') ?>">Group</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('setup_persediaan/kelompok') ?>">Kelompok</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('setup_persediaan/stock') ?>">Stock</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('setup_persediaan/harga') ?>">Harga Jual/Beli</a></li>
  </ul>
</li>

<!-- DASHBOARD TRANSAKSI -->
<li class="menu-header">Dashboard Transaksi</li>
<li class="nav-item dropdown">
  <a href="#" class="nav-link text-dark has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i> <span>Transaksi</span></a>
  <ul class="dropdown-menu">
    <li><a class="nav-link text-dark" href="<?= site_url('transaksi/pembelian/pembelian') ?>">Pembelian</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('transaksi/pembelian/returpembelian') ?>">Retur Pembelian</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('transaksi/penjualan/penjualan') ?>">Penjualan</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('transaksi/penjualan/returpenjualan') ?>">Retur Penjualan</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('penyesuaianstock') ?>">Penyesuaian Stock</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('pindahlokasi') ?>">Pindah Lokasi</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('bahansablon') ?>">Bahan Di Sablon</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('hasilsablon') ?>">Hasil Sablon</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('pemakaianbahan') ?>">Pemakaian Bahan</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('hasilproduksi') ?>">Hasil Produksi</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('tutangusaha') ?>">Pelunasan Piutang Usaha</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('lunassalesman') ?>">Pelunasan Piutang Sales</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('pelunasanhutang') ?>">Pelunasan Hutang</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('mutasikasbank') ?>">Mutasi Kas dan Bank</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('jurnalumum') ?>">Jurnal Umum</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('kaskecil') ?>">Pengeluaran Kas Kecil</a></li>
    <!-- <li><a class="nav-link text-dark" href="<?= site_url('posting') ?>">Posting</a></li>  -->
    <li><a class="nav-link text-dark" href="<?= site_url('close-period') ?>">Tutup Buku</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('stockopname') ?>">Stock Opname</a></li>
  </ul>
</li>

<!-- DASHBOARD LAPORAN -->
<li class="menu-header">Dashboard Laporan</li>
<li class="nav-item dropdown">
  <a href="#" class="nav-link text-dark has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i> <span>Laporan</span></a>
  <ul class="dropdown-menu">
    <li><a class="nav-link text-dark" href="<?= site_url('laporanpembelian') ?>">Pembelian</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('laporanreturpembelian') ?>">Retur Pembelian</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('laporanpenjualan') ?>">Penjualan</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('laporanpenjualan_p') ?>">Penjualan Per Salesman Per Pelanggan Per Barang</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('laporanpenjualan_pt') ?>">Penjualan Per Salesman Per Pelanggan Per Barang (Tahun)</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('laporanpenjualan_ptb') ?>">Penjualan Per Barang (Tahun)</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('laporanpenjualan_s') ?>">Penjualan Per Salesman</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('laporanpenjualan_st') ?>">Penjualan Per Salesman (Tahun)</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('laporanpenjualan_pp') ?>">Penjualan Per Pelanggan</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('laporanpenjualan_ppt') ?>">Penjualan Per Pelanggan (Tahun)</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('laporanpenjualan_sb') ?>">Penjualan Per Supplier Per Barang</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('laporanreturpenjualan') ?>">ReturPenjualan</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('laporanpenyesuaianstock') ?>">Penyesuaian Stock</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('laporanpindahlokasi') ?>">Pindah Lokasi</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('laporanbahansablon') ?>">Bahan di Sablon</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('laporanhasilsablon') ?>">Hasil Sablon</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('laporanpemakaianbahan') ?>">Pemakaian Bahan</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('laporanhasilproduksi') ?>">Hasil Produksi</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('laporan_kas_keluar') ?>">Kas Keluar</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('laporankaskecil') ?>">Pengeluaran Kas Kecil</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('laporanjurnalumum') ?>">Jurnal Umum</a></li>
  </ul>
</li>
<li class="nav-item dropdown">
  <a href="#" class="nav-link text-dark has-dropdown"><i class="fas fa-fire"></i><span>Bank</span></a>
  <ul class="dropdown-menu">
    <li><a class="nav-link text-dark" href="<?= site_url('laporanbankkartu') ?>">Kartu Bank</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('laporanbankdaftar') ?>">Daftar Bank</a></li>
  </ul>
</li>

<li class="nav-item dropdown">
  <a href="#" class="nav-link text-dark has-dropdown"><i class="fas fa-fire"></i><span>Piutang Usaha</span></a>
  <ul class="dropdown-menu">
    <li><a class="nav-link text-dark" href="<?= site_url('laporankartupiutangusaha') ?>">Kartu Piutang Usaha</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('laporandaftarpiutangusaha') ?>">Daftar Piutang Usaha</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('laporandaftarpiutangusahanota') ?>">Daftar Piutang Usaha Per Nota</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('laporanumurpiutang') ?>">Laporan Umur Piutang</a></li>
  </ul>
</li>
<li class="nav-item dropdown">
  <a href="#" class="nav-link text-dark has-dropdown"><i class="fas fa-fire"></i><span>Piutang Salesman</span></a>
  <ul class="dropdown-menu">
    <li><a class="nav-link text-dark" href="<?= site_url('laporankartupiutangsalesman') ?>">Kartu Piutang Salesman</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('laporandaftarpiutangsalesman') ?>">Daftar Piutang Salesman</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('laporandaftarpiutangsalesmannota') ?>">Daftar Piutang Salesman Per Nota</a></li>
  </ul>
</li>

<li class="nav-item dropdown">
  <a href="#" class="nav-link text-dark has-dropdown"><i class="fas fa-fire"></i><span>Supplier</span></a>
  <ul class="dropdown-menu">
    <li><a class="nav-link text-dark" href="<?= site_url('laporankartuhutangsupplier') ?>">Kartu Hutang Usaha</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('laporandaftarhutangsupplier') ?>">Daftar Hutang Usaha</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('laporandaftarhutangsuppliernota') ?>">Daftar Hutang Per Nota</a></li>
  </ul>
</li>
<li class="nav-item dropdown">
  <a href="#" class="nav-link text-dark has-dropdown"><i class="fas fa-fire"></i><span>Persedian</span></a>
  <ul class="dropdown-menu">
    <li><a class="nav-link text-dark" href="<?= site_url('laporankartustock') ?>">Kartu Stock</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('laporandaftarstock_rp') ?>">Daftar Stock (Rp)</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('laporandaftarstock_qty') ?>">Daftar Stock (Qty)</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('laporandaftarstock_kosong') ?>">Daftar Stock Akhir Kosong</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('laporandaftarstock_minimal') ?>">Daftar Stock Akhir Minimal</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('laporanstock_opname') ?>">Stock Opname</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('laporanstock_opname_perbandingan') ?>">Perbandingan Stock Opname</a></li>
  </ul>
</li>

<li class="nav-item dropdown">
  <a href="#" class="nav-link text-dark has-dropdown"><i class="fas fa-fire"></i><span>Keuangan</span></a>
  <ul class="dropdown-menu">
    <li><a class="nav-link text-dark" href="<?= site_url('laporan_biaya_daftar') ?>">Daftar Biaya</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('laporan_biaya_kartu') ?>">Kartu Biaya</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('laporan_buku_besar') ?>">Buku Besar</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('laporan_neraca_lajur') ?>">Neraca Lajur</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('laporan_neraca') ?>">Neraca</a></li>
    <li><a class="nav-link text-dark" href="<?= site_url('laporan_rugi_laba') ?>">Rugi Laba</a></li>
  </ul>
</li>

<!-- DASHBOARD SUPERVISOR -->
<li class="menu-header">Dashboard Supervisor</li>
<li class="nav-item dropdown">
  <a href="#" class="nav-link text-dark has-dropdown"><i class="far fa-file-alt"></i> <span>Supervisor</span></a>
  <ul class="dropdown-menu">
    <li><a class="nav-link text-dark" href="forms-advanced-form.html">Advanced Form</a></li>
    <li><a class="nav-link text-dark" href="forms-editor.html">Editor</a></li>
    <li><a class="nav-link text-dark" href="forms-validation.html">Validation</a></li>
  </ul>
</li>
</li>

<li><a class="nav-link text-dark" href="<?= site_url('user') ?>"><i class="fas fa-users"></i> <span>User</span></a></li>