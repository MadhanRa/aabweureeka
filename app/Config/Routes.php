<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('setup', ['namespace' => 'App\Controllers\setup'], static function ($routes) {
    //routes periode
    $routes->resource('periode');

    //routes interface
    $routes->resource('antarmuka');

    //routes klasifikasi
    $routes->resource('klasifikasi');

    // routes pos neraca
    $routes->resource('posneraca');

    //routes setup buku besar
    $routes->resource('buku', ['controller' => 'SetupBuku']);

    //routes salesman
    $routes->get('salesman/(:segment)/piutang', 'SetupSalesman::getPiutang/$1');
    $routes->post('salesman/(:segment)/piutang', 'SetupSalesman::addPiutang/$1');
    $routes->get('salesman/piutang/(:segment)/edit', 'SetupSalesman::editPiutang/$1');
    $routes->put('salesman/piutang/(:segment)/edit', 'SetupSalesman::updatePiutang/$1');
    $routes->delete('salesman/piutang/(:segment)', 'SetupSalesman::deletePiutang/$1');
    $routes->resource('salesman', ['controller' => 'SetupSalesman']);

    //routes setuppelanggan
    $routes->get('pelanggan/(:segment)/piutang', 'SetupPelanggan::getPiutang/$1');
    $routes->get('pelanggan/(:segment)/piutang/new', 'SetupPelanggan::newPiutang/$1');
    $routes->post('pelanggan/(:segment)/piutang', 'SetupPelanggan::createPiutang/$1');
    $routes->get('pelanggan/piutang/(:segment)/edit', 'SetupPelanggan::editPiutang/$1');
    $routes->put('pelanggan/piutang/(:segment)/edit', 'SetupPelanggan::updatePiutang/$1');
    $routes->delete('pelanggan/piutang/(:segment)', 'SetupPelanggan::deletePiutang/$1');
    $routes->resource('pelanggan', ['controller' => 'SetupPelanggan']);

    //routes setupsupplier
    $routes->get('supplier/(:segment)/hutang', 'SetupSupplier::getHutang/$1');
    $routes->get('supplier/(:segment)/hutang/new', 'SetupSupplier::newHutang/$1');
    $routes->post('supplier/(:segment)/hutang', 'SetupSupplier::createHutang/$1');
    $routes->get('supplier/hutang/(:segment)/edit', 'SetupSupplier::editHutang/$1');
    $routes->put('supplier/hutang/(:segment)/edit', 'SetupSupplier::updateHutang/$1');
    $routes->delete('supplier/hutang/(:segment)', 'SetupSupplier::deleteHutang/$1');
    $routes->resource('supplier', ['controller' => 'SetupSupplier']);

    //routes setup biaya
    $routes->resource('biaya', ['controller' => 'SetupBiaya']);

    //routes Kelompokproduksi
    $routes->resource('kelompokproduksi');

    //routes setup bank
    $routes->resource('bank', ['controller' => 'SetupBank']);

    //routes setupuser
    $routes->resource('useropname', ['controller' => 'SetupUserOpname']);
});

$routes->group('setup_persediaan', ['namespace' => 'App\Controllers\setup_persediaan'], static function ($routes) {

    //routes setup lokasi
    $routes->resource('satuan');

    // routes setup lokasi
    $routes->resource('lokasi');

    // routes setup group
    $routes->resource('group');

    // routes setup kelompok
    $routes->resource('kelompok');

    //routes setup stock
    $routes->get('stock/pilihItemGudang/(:num)/(:num)', 'Stock::pilihItemGudang/$1/$2');
    $routes->get('stock/pilihItem/(:num)', 'Stock::pilihItem/$1');
    $routes->post('stock/lookup-stock', 'Stock::lookupStock');
    $routes->get('stock/getStock', 'Stock::getStock');
    $routes->resource('stock');

    $routes->resource('harga');
});

$routes->group('transaksi', static function ($routes) {
    $routes->group('pembelian', ['namespace' => 'App\Controllers\transaksi\pembelian'], static function ($routes) {
        //routes untuk pembelian
        $routes->post('pembelian/lookup-pembelian', 'Pembelian::lookupPembelian');
        $routes->post('pembelian/hutang', 'Pembelian::lookupPembelianHutang');
        $routes->get('pembelian/printPDF/(:num)', 'Pembelian::printPDF/$1');
        $routes->get('pembelian/printPDF', 'Pembelian::printPDF');
        $routes->put('pembelian/(:segment)', 'Pembelian::update/$1', ['filter' => 'role:admin']);
        $routes->put('pembelian/(:segment)/edit', 'Pembelian::edit/$1', ['filter' => 'role:admin']);
        $routes->resource('pembelian');

        //routes untuk returpembelian
        $routes->post('returpembelian/lookup-returpembelian', 'ReturPembelian::lookupReturPembelian');
        $routes->get('returpembelian/printPDF/(:num)', 'ReturPembelian::printPDF/$1');
        $routes->get('returpembelian/printPDF', 'ReturPembelian::printPDF');
        $routes->put('returpembelian/(:segment)', 'ReturPembelian::update/$1', ['filter' => 'role:admin']);
        $routes->put('/returpembelian/(:segment)/edit', 'ReturPembelian::edit/$1', ['filter' => 'role:admin']);
        $routes->resource('returpembelian');
    });

    $routes->group('penjualan', ['namespace' => 'App\Controllers\transaksi\penjualan'], static function ($routes) {
        $routes->post('penjualan/lookup-penjualan', 'Penjualan::lookupPenjualan');
        $routes->post('penjualan/lookup-stock', 'Penjualan::lookupStock');
        $routes->get('penjualan/printPDF/(:num)', 'Penjualan::printPDF/$1');
        $routes->get('penjualan/printPDF', 'Penjualan::printPDF');
        $routes->put('/penjualan/(:segment)', 'Penjualan::update/$1', ['filter' => 'role:admin']);
        $routes->put('/penjualan/(:segment)/edit', 'Penjualan::edit/$1', ['filter' => 'role:admin']);
        $routes->resource('penjualan');

        //routes untuk returpenjualan
        $routes->get('returpenjualan/printPDF/(:num)', 'ReturPenjualan::printPDF/$1');
        $routes->get('returpenjualan/printPDF', 'ReturPenjualan::printPDF');
        $routes->put('/returpenjualan/(:segment)', 'ReturPenjualan::update/$1', ['filter' => 'role:admin']);
        $routes->put('/returpenjualan/(:segment)/edit', 'ReturPenjualan::edit/$1', ['filter' => 'role:admin']);
        $routes->resource('returpenjualan');
    });
});
$routes->group('', ['namespace' => 'App\Controllers\transaksi'], function ($routes) {
    //routes untuk penyesuaianstock
    $routes->get('penyesuaianstock/printPDF/(:num)', 'PenyesuaianStock::printPDF/$1');
    $routes->get('PenyesuaianStock/printPDF/(:num)', 'PenyesuaianStock::printPDF/$1');
    $routes->get('penyesuaianstock/printPDF', 'PenyesuaianStock::printPDF');
    $routes->get('PenyesuaianStock/printPDF', 'PenyesuaianStock::printPDF');
    $routes->put('/penyesuaianstock/(:segment)', 'PenyesuaianStock::update/$1', ['filter' => 'role:admin']);
    $routes->put('/penyesuaianstock/(:segment)/edit', 'PenyesuaianStock::edit/$1', ['filter' => 'role:admin']);
    $routes->resource('penyesuaianstock');

    //routes pindahlokasi
    $routes->get('pindahlokasi/printPDF/(:num)', 'PindahLokasi::printPDF/$1');
    $routes->get('PindahLokasi/printPDF/(:num)', 'PindahLokasi::printPDF/$1');
    $routes->get('pindahlokasi/printPDF', 'PindahLokasi::printPDF');
    $routes->get('PindahLokasi/printPDF', 'PindahLokasi::printPDF');
    $routes->put('/pindahlokasi/(:segment)', 'PindahLokasi::update/$1', ['filter' => 'role:admin']);
    $routes->put('/pindahlokasi/(:segment)/edit', 'PindahLokasi::edit/$1', ['filter' => 'role:admin']);
    $routes->resource('pindahlokasi');

    //routes bahansablon
    $routes->get('bahansablon/printPDF/(:num)', 'BahanSablon::printPDF/$1');
    $routes->get('BahanSablon/printPDF/(:num)', 'BahanSablon::printPDF/$1');
    $routes->get('bahansablon/printPDF', 'BahanSablon::printPDF');
    $routes->get('BahanSablon/printPDF', 'BahanSablon::printPDF');
    $routes->put('/bahansablon/(:segment)', 'BahanSablon::update/$1', ['filter' => 'role:admin']);
    $routes->put('/bahansablon/(:segment)/edit', 'BahanSablon::edit/$1', ['filter' => 'role:admin']);
    $routes->resource('bahansablon');

    // Routes untuk hasilsablon
    $routes->get('hasilsablon/printPDF/(:num)', 'HasilSablon::printPDF/$1'); // Print dengan ID
    $routes->get('hasilsablon/printPDF', 'HasilSablon::printPDF'); // Print tanpa ID
    $routes->put('/hasilsablon/(:segment)', 'HasilSablon::update/$1', ['filter' => 'role:admin']);
    $routes->put('/hasilsablon/(:segment)/edit', 'HasilSablon::edit/$1', ['filter' => 'role:admin']);
    $routes->resource('hasilsablon');

    //routes pemakaianbahan
    $routes->get('pemakaianbahan/printPDF/(:num)', 'PemakaianBahan::printPDF/$1');
    $routes->get('PemakaianBahan/printPDF/(:num)', 'PemakaianBahan::printPDF/$1');
    $routes->get('pemakaianbahan/printPDF', 'PemakaianBahan::printPDF');
    $routes->get('PemakaianBahan/printPDF', 'PemakaianBahan::printPDF');
    $routes->put('/pemakaianbahan/(:segment)', 'PemakaianBahan::update/$1', ['filter' => 'role:admin']);
    $routes->put('/pemakaianbahan/(:segment)/edit', 'PemakaianBahan::edit/$1', ['filter' => 'role:admin']);
    $routes->resource('pemakaianbahan');

    //routes hasil produksi
    $routes->get('hasilproduksi/printPDF/(:num)', 'HasilProduksi::printPDF/$1');
    $routes->get('HasilProduksi/printPDF/(:num)', 'HasilProduksi::printPDF/$1');
    $routes->get('hasilproduksi/printPDF', 'HasilProduksi::printPDF');
    $routes->get('HasilProduksi/printPDF', 'HasilProduksi::printPDF');
    $routes->resource('hasilproduksi');

    //routes t_utangusaha
    $routes->get('tutangusaha/printPDF/(:num)', 'TutangUsaha::printPDF/$1');
    $routes->get('TutangUsaha/printPDF/(:num)', 'TutangUsaha::printPDF/$1');
    $routes->get('tutangusaha/printPDF', 'TutangUsaha::printPDF');
    $routes->get('TutangUsaha/printPDF', 'TutangUsaha::printPDF');
    $routes->put('/tutangusaha/(:segment)', 'TutangUsaha::update/$1', ['filter' => 'role:admin']);
    $routes->put('/tutangusaha/(:segment)/edit', 'TutangUsaha::edit/$1', ['filter' => 'role:admin']);
    $routes->resource('tutangusaha');

    //routes lunassalesman
    $routes->get('lunassalesman/printPDF/(:num)', 'LunasSalesman::printPDF/$1');
    $routes->get('LunasSalesman/printPDF/(:num)', 'LunasSalesman::printPDF/$1');
    $routes->get('lunassalesman/printPDF', 'LunasSalesman::printPDF');
    $routes->get('LunasSalesman/printPDF', 'LunasSalesman::printPDF');
    $routes->put('/lunassalesman/(:segment)', 'LunasSalesman::update/$1', ['filter' => 'role:admin']);
    $routes->put('/lunassalesman/(:segment)/edit', 'LunasSalesman::edit/$1', ['filter' => 'role:admin']);
    $routes->resource('lunassalesman');

    //routes pelunasanhutang
    $routes->get('pelunasanhutang/printPDF/(:num)', 'PelunasanHutang::printPDF/$1');
    $routes->get('PelunasanHutang/printPDF/(:num)', 'PelunasanHutang::printPDF/$1');
    $routes->get('pelunasanhutang/printPDF', 'PelunasanHutang::printPDF');
    $routes->get('PelunasanHutang/printPDF', 'PelunasanHutang::printPDF');
    $routes->put('/pelunasanHutang/(:segment)', 'PelunasanHutang::update/$1', ['filter' => 'role:admin']);
    $routes->put('/pelunasanhutang/(:segment)/edit', 'PelunasanHutang::edit/$1', ['filter' => 'role:admin']);
    $routes->resource('pelunasanhutang');
});

$routes->group('', ['namespace' => 'App\Controllers\transaksi\akuntansi'], function ($routes) {
    //routes jurnalumum
    $routes->get('/jurnalumum/new', 'JurnalUmum::new');
    // $routes->get('/jurnalumum/(:segment)/new', 'JurnalUmum::edit/$1');
    $routes->resource('jurnalumum');
    $routes->post('/jurnalumum', 'JurnalUmum::create');
    $routes->post('/jurnalumum/(:any)', 'JurnalUmum::delete/$1');
    // $routes->put('/jurnalumum/(:segment)/edit', 'JurnalUmum::edit/$1');
    $routes->get('jurnalumum', 'JurnalUmum::index');
    $routes->get('jurnalumum/printPDF/(:num)', 'JurnalUmum::printPDF/$1');
    $routes->get('JurnalUmum/printPDF/(:num)', 'JurnalUmum::printPDF/$1');
    $routes->get('jurnalumum/printPDF', 'JurnalUmum::printPDF');
    $routes->get('JurnalUmum/printPDF', 'JurnalUmum::printPDF');
    $routes->put('/jurnalumum/(:segment)', 'JurnalUmum::update/$1', ['filter' => 'role:admin']);
    $routes->put('/jurnalumum/(:segment)/edit', 'JurnalUmum::edit/$1', ['filter' => 'role:admin']);

    //routes mutasikasbank
    $routes->get('/mutasikasbank/new', 'MutasiKasBank::new');
    // $routes->get('/mutasikasbank/(:segment)/new', 'MutasiKasBank::edit/$1');
    $routes->resource('mutasikasbank');
    $routes->post('/mutasikasbank', 'MutasiKasBank::create');
    $routes->post('/mutasikasbank/(:any)', 'MutasiKasBank::delete/$1');
    // $routes->put('/mutasikasbank/(:segment)/edit', 'MutasiKasBank::edit/$1');
    $routes->get('mutasikasbank', 'MutasiKasBank::index');
    $routes->get('mutasikasbank/printPDF/(:num)', 'MutasiKasBank::printPDF/$1');
    $routes->get('MutasiKasBank/printPDF/(:num)', 'MutasiKasBank::printPDF/$1');
    $routes->get('mutasikasbank/printPDF', 'MutasiKasBank::printPDF');
    $routes->get('MutasiKasBank/printPDF', 'MutasiKasBank::printPDF');
    $routes->put('/mutasiKasBank/(:segment)', 'MutasiKasBank::update/$1', ['filter' => 'role:admin']);
    $routes->put('/mutasiKasBank/(:segment)/edit', 'MutasiKasBank::edit/$1', ['filter' => 'role:admin']);

    //routes kaskecil
    $routes->get('/kaskecil/new', 'KasKecil::new');
    // $routes->get('/kaskecil/(:segment)/new', 'KasKecil::edit/$1');
    $routes->resource('kaskecil');
    $routes->post('/kaskecil', 'KasKecil::create');
    $routes->post('/kaskecil/(:any)', 'KasKecil::delete/$1');
    // $routes->put('/kaskecil/(:segment)/edit', 'KasKecil::edit/$1');
    $routes->get('kaskecil', 'KasKecil::index');
    $routes->get('kaskecil/printPDF/(:num)', 'KasKecil::printPDF/$1');
    $routes->get('KasKecil/printPDF/(:num)', 'KasKecil::printPDF/$1');
    $routes->get('kaskecil/printPDF', 'KasKecil::printPDF');
    $routes->get('KasKecil/printPDF', 'KasKecil::printPDF');
    $routes->put('/kaskecil/(:segment)', 'KasKecil::update/$1', ['filter' => 'role:admin']);
    $routes->put('/kaskecil/(:segment)/edit', 'KasKecil::edit/$1', ['filter' => 'role:admin']);
});

//routes stockopname
$routes->get('stockopname/autocomplete', 'StockOpname::autocomplete');
$routes->get('stockopname/printPDF/(:num)', 'StockOpname::printPDF/$1');
$routes->get('StockOpname/printPDF/(:num)', 'StockOpname::printPDF/$1');
$routes->get('stockopname/printPDF', 'StockOpname::printPDF');
$routes->get('StockOpname/printPDF', 'StockOpname::printPDF');
$routes->put('/stockopname/(:segment)', 'StockOpname::update/$1', ['filter' => 'role:admin']);
$routes->put('/stokopname/(:segment)/edit', 'StockOpname::edit/$1', ['filter' => 'role:admin']);
$routes->resource('stockopname');

//routes posting dan tutup buku
$routes->get('/transaksi/posting', 'TransaksiController::posting');
$routes->get('/transaksi/tutup_buku', 'TransaksiController::tutupBuku');
$routes->post('/transaksi/posting/proses', 'TransaksiController::prosesPosting');
$routes->post('/transaksi/tutup_buku/proses', 'TransaksiController::prosesTutupBuku');
$routes->post('/transaksi/tutup-buku/proses', 'TransaksiController::prosesTutupBuku');

//routes untuk user
$routes->get('/user', 'User::index', ['filter' => 'role:admin']);
$routes->get('/user/index', 'User::index', ['filter' => 'role:admin']);
$routes->get('/user/edit/(:num)', 'User::edit/$1');
$routes->post('/user/update/(:num)', 'User::update/$1'); // Hanya menggunakan POST untuk update

//routes untuk tutupbuku
$routes->get('closebook', 'TutupBukuController::index');

// Route untuk menampilkan halaman closeBook
$routes->get('accounting/closeBook', 'Accounting::index');

// Route untuk menjalankan proses penutupan periode
$routes->post('accounting/closeBook/closePeriod', 'Accounting::closePeriod');

$routes->group('', ['namespace' => 'App\Controllers\transaksi'], function ($routes) {
    $routes->get('close-period', 'PeriodsController::index');
    $routes->get('period-add', 'PeriodsController::add');
    $routes->post('/close-period/close', 'PeriodsController::close');
    $routes->post('/close-period/open/(:num)', 'PeriodsController::open/$1', ['filter' => 'role:admin']);
    $routes->get('close-period/close_book/(:num)', 'PeriodsController::closeBook/$1');
    $routes->get('close-period/report/(:num)', 'PeriodsController::report/$1');
    $routes->get('close-period/print/(:num)', 'PeriodsController::printPDF/$1');
    $routes->delete('close-period/(:num)', 'PeriodsController::delete/$1');
});

//routes untuk laporanpembelian
$routes->get('/laporanpembelian', 'LaporanPembelian::index');
$routes->get('/laporanpembelian/printPDF/(:num)', 'LaporanPembelian::printPDF/$1');
$routes->get('/LaporanPembelian/printPDF/(:num)', 'LaporanPembelian::printPDF/$1');
$routes->get('/laporanpembelian/printPDF', 'LaporanPembelian::printPDF');
$routes->get('/LaporanPembelian/printPDF', 'LaporanPembelian::printPDF');
$routes->post('/laporanpembelian', 'LaporanPembelian::index');

//routes untuk laporanreturpembelian
$routes->get('/laporanreturpembelian', 'LaporanReturPembelian::index');
$routes->get('/laporanreturpembelian/printPDF/(:num)', 'LaporanReturPembelian::printPDF/$1');
$routes->get('/LaporanReturPembelian/printPDF/(:num)', 'LaporanReturPembelian::printPDF/$1');
$routes->get('/laporanreturpembelian/printPDF', 'LaporanReturPembelian::printPDF');
$routes->get('/LaporanReturPembelian/printPDF', 'LaporanReturPembelian::printPDF');
$routes->post('/laporanreturpembelian', 'LaporanReturPembelian::index');

//laporanpenyesuaianstock
$routes->get('/laporanpenyesuaianstock', 'LaporanPenyesuaianStock::index');
$routes->get('/laporanpenyesuaianstock/printPDF/(:num)', 'LaporanPenyesuaianStock::printPDF/$1');
$routes->get('/LaporanPenyesuaianStock/printPDF/(:num)', 'LaporanPenyesuaianStock::printPDF/$1');
$routes->get('/laporanpenyesuaianstock/printPDF', 'LaporanPenyesuaianStock::printPDF');
$routes->get('/LaporanPenyesuaianStock/printPDF', 'LaporanPenyesuaianStock::printPDF');
$routes->post('/laporanpenyesuaianstock', 'LaporanPenyesuaianStock::index');

//laporanpindahlokasi
$routes->get('/laporanpindahlokasi', 'LaporanPindahLokasi::index');
$routes->get('/laporanpindahlokasi/printPDF/(:num)', 'LaporanPindahLokasi::printPDF/$1');
$routes->get('/LaporanPindahLokasi/printPDF/(:num)', 'LaporanPindahLokasi::printPDF/$1');
$routes->get('/laporanpindahlokasi/printPDF', 'LaporanPindahLokasi::printPDF');
$routes->get('/LaporanPindahLokasi/printPDF', 'LaporanPindahLokasi::printPDF');
$routes->get('LaporanPindahLokasi/printPDF/(:any)/(:any)', 'LaporanPindahLokasi::printPDF/$1/$2');
$routes->post('/laporanpindahlokasi', 'LaporanPindahLokasi::index');

//laporankartubank
$routes->get('/laporanbankkartu', 'LaporanBankKartu::index');
$routes->get('/laporanbankkartu/printPDF', 'LaporanBankKartu::printPDF');

//laporandaftarbank
$routes->get('/laporanbankdaftar', 'LaporanBankDaftar::index');
$routes->get('/laporanbankdaftar/printPDF', 'LaporanBankDaftar::printPDF');

//laporan kartu piutang usaha
$routes->get('/laporankartupiutangusaha', 'LaporanPiutangUsahaKartu::index');
$routes->get('/laporankartupiutangusaha/printPDF', 'LaporanPiutangUsahaKartu::printPDF');

//laporan daftar piutang usaha
$routes->get('/laporandaftarpiutangusaha', 'LaporanPiutangUsahaDaftar::index');
$routes->get('/laporandaftarpiutangusaha/printPDF', 'LaporanPiutangUsahaDaftar::printPDF');

//laporan daftar piutang usaha nota
$routes->get('/laporandaftarpiutangusahanota', 'LaporanPiutangUsahaDaftarNota::index');
$routes->get('/laporandaftarpiutangusahanota/printPDF', 'LaporanPiutangUsahaDaftarNota::printPDF');

//laporan umur piutang usaha
$routes->get('/laporanumurpiutang', 'LaporanPiutangUsahaUmur::index');
$routes->get('/laporanumurpiutang/printPDF', 'LaporanPiutangUsahaUmur::printPDF');

$routes->group('', ['namespace' => 'App\Controllers\laporan_salesman'], function ($routes) {
    //laporan kartu piutang salesman
    $routes->get('/laporankartupiutangsalesman', 'LaporanPiutangSalesmanKartu::index');
    $routes->get('/laporankartupiutangsalesman/printPDF', 'LaporanPiutangSalesmanKartu::printPDF');

    //laporan daftar piutang salesman
    $routes->get('/laporandaftarpiutangsalesman', 'LaporanPiutangSalesmanDaftar::index');
    $routes->get('/laporandaftarpiutangsalesman/printPDF', 'LaporanPiutangSalesmanDaftar::printPDF');

    //laporan daftar piutang salesman nota
    $routes->get('/laporandaftarpiutangsalesmannota', 'LaporanPiutangSalesmanDaftarNota::index');
    $routes->get('/laporandaftarpiutangsalesmannota/printPDF', 'LaporanPiutangSalesmanDaftarNota::printPDF');
});

$routes->group('', ['namespace' => 'App\Controllers\laporan_supplier'], function ($routes) {
    //laporan kartu hutang supplier
    $routes->get('/laporankartuhutangsupplier', 'LaporanHutangSupplierKartu::index');
    $routes->get('/laporankartuhutangsupplier/printPDF', 'LaporanHutangSupplierKartu::printPDF');

    //laporan daftar hutang supplier
    $routes->get('/laporandaftarhutangsupplier', 'LaporanHutangSupplierDaftar::index');
    $routes->get('/laporandaftarhutangsupplier/printPDF', 'LaporanHutangSupplierDaftar::printPDF');

    //laporan daftar hutang supplier per nota
    $routes->get('/laporandaftarhutangsuppliernota', 'LaporanHutangSupplierDaftarNota::index');
    $routes->get('/laporandaftarhutangsuppliernota/printPDF', 'LaporanHutangSupplierDaftarNota::printPDF');
});




//laporan kartu stock
$routes->get('/laporankartustock', 'LaporanStockKartu::index');
$routes->get('/laporankartustock/cari-stock/(:any)', 'LaporanStockKartu::getStockData/$1');
$routes->get('/laporankartustock/printPDF', 'LaporanStockKartu::printPDF');

//laporan daftar stock rp
$routes->get('/laporandaftarstock_rp', 'LaporanStockDaftarRP::index');
$routes->get('/laporandaftarstock_rp/printPDF', 'LaporanStockDaftarRP::printPDF');

//laporan daftar stock qty
$routes->get('/laporandaftarstock_qty', 'LaporanStockDaftarQTY::index');
$routes->get('/laporandaftarstock_qty/printPDF', 'LaporanStockDaftarQTY::printPDF');

//laporan daftar stock kosong
$routes->get('/laporandaftarstock_kosong', 'LaporanStockDaftarKosong::index');
$routes->get('/laporandaftarstock_kosong/printPDF', 'LaporanStockDaftarKosong::printPDF');

//laporan daftar stock minimal
$routes->get('/laporandaftarstock_minimal', 'LaporanStockDaftarMinimal::index');
$routes->get('/laporandaftarstock_minimal/printPDF', 'LaporanStockDaftarMinimal::printPDF');

//laporan Stock opname
$routes->get('/laporanstock_opname', 'LaporanStockOpname::index');
$routes->get('/laporanstock_opname/printPDF', 'LaporanStockOpname::printPDF');

//laporan Perbandingan Stock opname
$routes->get('/laporanstock_opname_perbandingan', 'LaporanStockOpnamePerbandingan::index');
$routes->get('/laporanstock_opname_perbandingan/printPDF', 'LaporanStockOpnamePerbandingan::printPDF');
$routes->get('/laporanstock_opname_perbandingan/cari-nota/(:any)', 'LaporanStockOpnamePerbandingan::cariNotaOpname/$1');

// ROUTES UNTUK LAPORAN KEUANGAN
//laporan Daftar Biaya Pendapatan
$routes->get('/laporan_biaya_daftar', 'LaporanBiayaDaftar::index');
$routes->get('/laporan_biaya_daftar/printPDF', 'LaporanBiayaDaftar::printPDF');

//laporan Kartu Biaya Pendapatan
$routes->get('/laporan_biaya_kartu', 'LaporanBiayaKartu::index');
$routes->get('/laporan_biaya_kartu/printPDF', 'LaporanBiayaKartu::printPDF');
$routes->get('/laporan_biaya_kartu/cari-biaya/(:any)', 'LaporanBiayaKartu::cariBiaya/$1');

//laporan Buku Besar
$routes->get('/laporan_buku_besar', 'LaporanBukuBesar::index');
$routes->get('/laporan_buku_besar/printPDF', 'LaporanBukuBesar::printPDF');
$routes->get('/laporan_buku_besar/cari-buku/(:any)', 'LaporanBukuBesar::cariBuku/$1');

//laporan Neraca Lajur
$routes->get('/laporan_neraca_lajur', 'LaporanNeracaLajur::index');
$routes->get('/laporan_neraca_lajur/printPDF', 'LaporanNeracaLajur::printPDF');

//laporan Neraca
$routes->get('/laporan_neraca', 'LaporanNeraca::index');
$routes->get('/laporan_neraca/printPDF', 'LaporanNeraca::printPDF');

//laporan Rugi Laba
$routes->get('/laporan_rugi_laba', 'LaporanRugiLaba::index');
$routes->get('/laporan_rugi_laba/printPDF', 'LaporanRugiLaba::printPDF');



//laporanjurnalumum
$routes->get('/laporanjurnalumum', 'LaporanJurnalUmum::index');
$routes->get('/laporanjurnalumum/printPDF/(:num)', 'LaporanJurnalUmum::printPDF/$1');
$routes->get('/LaporanJurnalUmum/printPDF/(:num)', 'LaporanJurnalUmum::printPDF/$1');
$routes->get('/laporanjurnalumum/printPDF', 'LaporanJurnalUmum::printPDF');
$routes->get('/LaporanJurnalUmum/printPDF', 'LaporanJurnalUmum::printPDF');
$routes->post('/laporanjurnalumum', 'LaporanJurnalUmum::index');

//laporanbahansablon
$routes->get('/laporanbahansablon', 'LaporanBahanSablon::index');
$routes->get('/laporanbahansablon/printPDF/(:num)', 'LaporanBahanSablon::printPDF/$1');
$routes->get('/LaporanBahanSablon/printPDF/(:num)', 'LaporanBahanSablon::printPDF/$1');
$routes->get('/laporanbahansablon/printPDF', 'LaporanBahanSablon::printPDF');
$routes->get('/LaporanBahanSablon/printPDF', 'LaporanBahanSablon::printPDF');
$routes->post('/laporanbahansablon', 'LaporanBahanSablon::index');

//laporanhasilsablon
$routes->get('/laporanhasilsablon', 'LaporanHasilSablon::index');
$routes->get('/laporanhasilsablon/printPDF/(:num)', 'LaporanHasilSablon::printPDF/$1');
$routes->get('/LaporanHasilSablon/printPDF/(:num)', 'LaporanHasilSablon::printPDF/$1');
$routes->get('/laporanhasilsablon/printPDF', 'LaporanHasilSablon::printPDF');
$routes->get('/LaporanHasilSablon/printPDF', 'LaporanHasilSablon::printPDF');
$routes->post('/laporanhasilsablon', 'LaporanHasilSablon::index');

//laporanpemakaianbahan
$routes->get('/laporanpemakaianbahan', 'LaporanPemakaianBahan::index');
$routes->get('/laporanpemakaianbahan/printPDF/(:num)', 'LaporanPemakaianBahan::printPDF/$1');
$routes->get('/LaporanPemakaianBahan/printPDF/(:num)', 'LaporanPemakaianBahan::printPDF/$1');
$routes->get('/laporanpemakaianbahan/printPDF', 'LaporanPemakaianBahan::printPDF');
$routes->get('/LaporanPemakaianBahan/printPDF', 'LaporanPemakaianBahan::printPDF');
$routes->post('/laporanpemakaianbahan', 'LaporanPemakaianBahan::index');

//laporanhasilproduksi
$routes->get('/laporanhasilproduksi', 'LaporanHasilProduksi::index');
$routes->get('/laporanhasilproduksi/printPDF/(:num)', 'LaporanHasilProduksi::printPDF/$1');
$routes->get('/LaporanHasilProduksi/printPDF/(:num)', 'LaporanHasilProduksi::printPDF/$1');
$routes->get('/laporanhasilproduksi/printPDF', 'LaporanHasilProduksi::printPDF');
$routes->get('/LaporanHasilProduksi/printPDF', 'LaporanHasilProduksi::printPDF');
$routes->post('/laporanhasilproduksi', 'LaporanHasilProduksi::index');

//laporankaskecil
$routes->get('/laporankaskecil', 'LaporanKasKecil::index');
$routes->get('/laporankaskecil/printPDF/(:num)', 'LaporanKasKecil::printPDF/$1');
$routes->get('/LaporanKasKecil/printPDF/(:num)', 'LaporanKasKecil::printPDF/$1');
$routes->get('/laporankaskecil/printPDF', 'LaporanKasKecil::printPDF');
$routes->get('/LaporanKasKecil/printPDF', 'LaporanKasKecil::printPDF');
$routes->post('/laporankaskecil', 'LaporanKasKecil::index');

//laporanpenjualan
$routes->get('/laporanpenjualan/printPDF', 'LaporanPenjualan::printPDF');
$routes->get('/laporanpenjualan', 'LaporanPenjualan::index');


//routes untuk penjualan per salesman per pelanggan per barang
$routes->get('laporanpenjualan_p', 'LaporanPenjualanP::index');
$routes->get('/laporanpenjualan_p/printPDF', 'LaporanPenjualanP::printPDF');

//routes untuk penjualan per salesman per pelanggan per barang (tahun)
$routes->get('laporanpenjualan_pt', 'LaporanPenjualanPT::index');
$routes->get('/laporanpenjualan_pt/printPDF', 'LaporanPenjualanPT::printPDF');

//routes untuk penjualan per barang (tahun)
$routes->get('laporanpenjualan_ptb', 'LaporanPenjualanPTB::index');
$routes->get('laporanpenjualan_ptb/printPDF', 'LaporanPenjualanPTB::printPDF');

//routes untuk penjualan per salesman
$routes->get('laporanpenjualan_s', 'LaporanPenjualanS::index');
$routes->get('laporanpenjualan_s/printPDF', 'LaporanPenjualanS::printPDF');

//routes untuk penjualan per salesman (tahun)
$routes->get('laporanpenjualan_st', 'LaporanPenjualanST::index');
$routes->get('laporanpenjualan_st/printPDF', 'LaporanPenjualanST::printPDF');

//routes untuk penjualan per pelanggan
$routes->get('laporanpenjualan_pp', 'LaporanPenjualanPP::index');
$routes->get('laporanpenjualan_pp/printPDF', 'LaporanPenjualanPP::printPDF');

//routes untuk penjualan per pelanggan (tahun)
$routes->get('laporanpenjualan_ppt', 'LaporanPenjualanPPT::index');
$routes->get('laporanpenjualan_ppt/printPDF', 'LaporanPenjualanPPT::printPDF');

//routes untuk penjualan per supplier per barang
$routes->get('laporanpenjualan_sb', 'LaporanPenjualanSB::index');
$routes->get('laporanpenjualan_sb/printPDF', 'LaporanPenjualanSB::printPDF');


//laporanreturpenjualan
$routes->get('/laporanreturpenjualan', 'LaporanReturPenjualan::index');
$routes->get('/laporanreturpenjualan/printPDF', 'LaporanReturPenjualan::printPDF');

//laporan kas keluar
$routes->get('/laporan_kas_keluar', 'LaporanKasKeluar::index');
$routes->get('/laporan_kas_keluar/printPDF', 'LaporanKasKeluar::printPDF');
