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
    $routes->get('stock/getStock', 'Stock::getStock');
    $routes->resource('stock');

    $routes->resource('harga');
});


//routes pindahlokasi
$routes->get('/pindahlokasi/new', 'PindahLokasi::new');
$routes->get('/pindahlokasi/(:segment)/new', 'PindahLokasi::edit/$1');
$routes->resource('pindahlokasi');
$routes->post('/pindahlokasi', 'PindahLokasi::create');
$routes->post('/pindahlokasi/(:any)', 'PindahLokasi::delete/$1');
// $routes->put('/pindahlokasi/(:segment)/edit', 'PindahLokasi::edit/$1');
$routes->get('pindahlokasi', 'PindahLokasi::index');
$routes->get('pindahlokasi/printPDF/(:num)', 'PindahLokasi::printPDF/$1');
$routes->get('PindahLokasi/printPDF/(:num)', 'PindahLokasi::printPDF/$1');
$routes->get('pindahlokasi/printPDF', 'PindahLokasi::printPDF');
$routes->get('PindahLokasi/printPDF', 'PindahLokasi::printPDF');
$routes->put('/pindahlokasi/(:segment)', 'PindahLokasi::update/$1', ['filter' => 'role:admin']);
$routes->put('/pindahlokasi/(:segment)/edit', 'PindahLokasi::edit/$1', ['filter' => 'role:admin']);

//routes bahansablon
$routes->get('/bahansablon/new', 'BahanSablon::new');
// $routes->get('/bahansablon/(:segment)/new', 'BahanSablon::edit/$1');
$routes->resource('bahansablon');
$routes->post('/bahansablon', 'BahanSablon::create');
$routes->post('/bahansablon/(:any)', 'BahanSablon::delete/$1');
// $routes->put('/bahansablon/(:segment)/edit', 'BahanSablon::edit/$1');
$routes->get('bahansablon', 'BahanSablon::index');
$routes->get('bahansablon/printPDF/(:num)', 'BahanSablon::printPDF/$1');
$routes->get('BahanSablon/printPDF/(:num)', 'BahanSablon::printPDF/$1');
$routes->get('bahansablon/printPDF', 'BahanSablon::printPDF');
$routes->get('BahanSablon/printPDF', 'BahanSablon::printPDF');
$routes->put('/bahansablon/(:segment)', 'BahanSablon::update/$1', ['filter' => 'role:admin']);
$routes->put('/bahansablon/(:segment)/edit', 'BahanSablon::edit/$1', ['filter' => 'role:admin']);

// Routes untuk hasilsablon
$routes->resource('hasilsablon'); // Untuk route dasar seperti index, show, new, create, update, delete
// $routes->get('hasilsablon/edit/(:num)', 'HasilSablon::edit/$1'); // Form edit
// $routes->post('hasilsablon/update/(:num)', 'HasilSablon::update/$1', ['filter' => 'role:admin']); // Aksi update
$routes->delete('hasilsablon/(:num)', 'HasilSablon::delete/$1'); // Route delete
$routes->get('hasilsablon/printPDF/(:num)', 'HasilSablon::printPDF/$1'); // Print dengan ID
$routes->get('hasilsablon/printPDF', 'HasilSablon::printPDF'); // Print tanpa ID
// $routes->get('hasilsablon/edit/(:num)', 'HasilSablon::edit/$1');
$routes->put('/hasilsablon/(:segment)', 'HasilSablon::update/$1', ['filter' => 'role:admin']);
$routes->put('/hasilsablon/(:segment)/edit', 'HasilSablon::edit/$1', ['filter' => 'role:admin']);

//routes hasil produksi
$routes->get('/hasilproduksi/new', 'HasilProduksi::new');
$routes->get('/hasilproduksi/(:segment)/new', 'HasilProduksi::edit/$1');
$routes->resource('hasilproduksi');
$routes->post('/hasilproduksi', 'HasilProduksi::create');
$routes->post('/hasilproduksi/(:any)', 'HasilProduksi::delete/$1');
$routes->put('/hasilsablon/(:segment)', 'HasilSablon::update/$1');
$routes->put('/hasilproduksi/(:segment)/edit', 'HasilProduksi::edit/$1');
$routes->get('hasilproduksi', 'HasilProduksi::index');
$routes->get('hasilproduksi/printPDF/(:num)', 'HasilProduksi::printPDF/$1');
$routes->get('HasilProduksi/printPDF/(:num)', 'HasilProduksi::printPDF/$1');
$routes->get('hasilproduksi/printPDF', 'HasilProduksi::printPDF');
$routes->get('HasilProduksi/printPDF', 'HasilProduksi::printPDF');

//routes pemakaianbahan
$routes->get('/pemakaianbahan/new', 'PemakaianBahan::new');
// $routes->get('/pemakaianbahan/(:segment)/new', 'PemakaianBahan::edit/$1');
$routes->resource('pemakaianbahan');
$routes->post('/pemakaianbahan', 'PemakaianBahan::create');
$routes->post('/pemakaianbahan/(:any)', 'PemakaianBahan::delete/$1');
// $routes->put('/pemakaianbahan/(:segment)/edit', 'PemakaianBahan::edit/$1');
$routes->get('pemakaianbahan', 'PemakaianBahan::index');
$routes->get('pemakaianbahan/printPDF/(:num)', 'PemakaianBahan::printPDF/$1');
$routes->get('PemakaianBahan/printPDF/(:num)', 'PemakaianBahan::printPDF/$1');
$routes->get('pemakaianbahan/printPDF', 'PemakaianBahan::printPDF');
$routes->get('PemakaianBahan/printPDF', 'PemakaianBahan::printPDF');
$routes->put('/pemakaianbahan/(:segment)', 'PemakaianBahan::update/$1', ['filter' => 'role:admin']);
$routes->put('/pemakaianbahan/(:segment)/edit', 'PemakaianBahan::edit/$1', ['filter' => 'role:admin']);

//routes t_utangusaha
$routes->get('/tutangusaha/new', 'TutangUsaha::new');
// $routes->get('/tutangusaha/(:segment)/new', 'TutangUsaha::edit/$1');
$routes->resource('tutangusaha');
$routes->post('/tutangusaha', 'TutangUsaha::create');
$routes->post('/tutangusaha/(:any)', 'TutangUsaha::delete/$1');
// $routes->put('/tutangusaha/(:segment)/edit', 'TutangUsaha::edit/$1');
$routes->get('tutangusaha', 'TutangUsaha::index');
$routes->get('tutangusaha/printPDF/(:num)', 'TutangUsaha::printPDF/$1');
$routes->get('TutangUsaha/printPDF/(:num)', 'TutangUsaha::printPDF/$1');
$routes->get('tutangusaha/printPDF', 'TutangUsaha::printPDF');
$routes->get('TutangUsaha/printPDF', 'TutangUsaha::printPDF');
$routes->put('/tutangusaha/(:segment)', 'TutangUsaha::update/$1', ['filter' => 'role:admin']);
$routes->put('/tutangusaha/(:segment)/edit', 'TutangUsaha::edit/$1', ['filter' => 'role:admin']);

//routes lunassalesman
$routes->get('/lunassalesman/new', 'LunasSalesman::new');
// $routes->get('/lunassalesman/(:segment)/new', 'LunasSalesman::edit/$1');    
$routes->resource('lunassalesman');
$routes->post('/lunassalesman', 'LunasSalesman::create');
$routes->post('/lunassalesman/(:any)', 'LunasSalesman::delete/$1');
// $routes->put('/lunassalesman/(:segment)/edit', 'LunasSalesman::edit/$1');
$routes->get('lunassalesman', 'LunasSalesman::index');
$routes->get('lunassalesman/printPDF/(:num)', 'LunasSalesman::printPDF/$1');
$routes->get('LunasSalesman/printPDF/(:num)', 'LunasSalesman::printPDF/$1');
$routes->get('lunassalesman/printPDF', 'LunasSalesman::printPDF');
$routes->get('LunasSalesman/printPDF', 'LunasSalesman::printPDF');
$routes->put('/lunassalesman/(:segment)', 'LunasSalesman::update/$1', ['filter' => 'role:admin']);
$routes->put('/lunassalesman/(:segment)/edit', 'LunasSalesman::edit/$1', ['filter' => 'role:admin']);

//routes pelunasanhutang
$routes->get('/pelunasanhutang/new', 'PelunasanHutang::new');
// $routes->get('/pelunasanhutang/(:segment)/new', 'PelunasanHutang::edit/$1');
$routes->resource('pelunasanhutang');
$routes->post('/pelunasanhutang', 'PelunasanHutang::create');
$routes->post('/pelunasanhutang/(:any)', 'PelunasanHutang::delete/$1');
// $routes->put('/pelunasanhutang/(:segment)/edit', 'PelunasanHutang::edit/$1');
$routes->get('pelunasanhutang', 'PelunasanHutang::index');
$routes->get('pelunasanhutang/printPDF/(:num)', 'PelunasanHutang::printPDF/$1');
$routes->get('PelunasanHutang/printPDF/(:num)', 'PelunasanHutang::printPDF/$1');
$routes->get('pelunasanhutang/printPDF', 'PelunasanHutang::printPDF');
$routes->get('PelunasanHutang/printPDF', 'PelunasanHutang::printPDF');
$routes->put('/pelunasanHutang/(:segment)', 'PelunasanHutang::update/$1', ['filter' => 'role:admin']);
$routes->put('/pelunasanhutang/(:segment)/edit', 'PelunasanHutang::edit/$1', ['filter' => 'role:admin']);

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

//routes stockopname
$routes->get('/stockopname/new', 'StockOpname::new');
// $routes->get('/stockopname/(:segment)/new', 'StockOpname::edit/$1');
$routes->resource('stockopname');
$routes->post('/stockopname', 'StockOpname::create');
$routes->post('/stockopname/(:any)', 'StockOpname::delete/$1');
// $routes->put('/stockopname/(:segment)/edit', 'StockOpname::edit/$1');
$routes->get('stockopname', 'StockOpname::index');
$routes->get('stockopname/printPDF/(:num)', 'StockOpname::printPDF/$1');
$routes->get('StockOpname/printPDF/(:num)', 'StockOpname::printPDF/$1');
$routes->get('stockopname/printPDF', 'StockOpname::printPDF');
$routes->get('StockOpname/printPDF', 'StockOpname::printPDF');
$routes->put('/stockopname/(:segment)', 'StockOpname::update/$1', ['filter' => 'role:admin']);
$routes->put('/stokopname/(:segment)/edit', 'StockOpname::edit/$1', ['filter' => 'role:admin']);

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

//routes untuk pembelian
$routes->get('/pembelian/new', 'Pembelian::new');
$routes->resource('pembelian');
$routes->post('/pembelian', 'Pembelian::create');
$routes->post('/pembelian/(:any)', 'Pembelian::delete/$1');
$routes->get('pembelian', 'Pembelian::index');
$routes->get('pembelian/printPDF/(:num)', 'Pembelian::printPDF/$1');
$routes->get('Pembelian/printPDF/(:num)', 'Pembelian::printPDF/$1');
$routes->get('pembelian/printPDF', 'Pembelian::printPDF');
$routes->get('Pembelian/printPDF', 'Pembelian::printPDF');
$routes->put('/pembelian/(:segment)', 'Pembelian::update/$1', ['filter' => 'role:admin']);
$routes->put('/pembelian/(:segment)/edit', 'Pembelian::edit/$1', ['filter' => 'role:admin']);

//routes untuk returpembelian
$routes->get('/returpembelian/new', 'ReturPembelian::new');
$routes->resource('returpembelian');
$routes->post('/returpembelian', 'ReturPembelian::create');
$routes->post('/returpembelian/(:any)', 'ReturPembelian::delete/$1');
$routes->get('returpembelian', 'ReturPembelian::index');
$routes->get('returpembelian/printPDF/(:num)', 'ReturPembelian::printPDF/$1');
$routes->get('ReturPembelian/printPDF/(:num)', 'ReturPembelian::printPDF/$1');
$routes->get('returpembelian/printPDF', 'ReturPembelian::printPDF');
$routes->get('ReturPembelian/printPDF', 'ReturPembelian::printPDF');
$routes->put('/returpembelian/(:segment)', 'ReturPembelian::update/$1', ['filter' => 'role:admin']);
$routes->put('/returpembelian/(:segment)/edit', 'ReturPembelian::edit/$1', ['filter' => 'role:admin']);

//routes untuk penjualan
$routes->get('/penjualan/new', 'Penjualan::new');
$routes->resource('penjualan');
$routes->post('/penjualan', 'Penjualan::create');
$routes->post('/penjualan/(:any)', 'Penjualan::delete/$1');
$routes->get('penjualan', 'Penjualan::index');
$routes->get('penjualan/printPDF/(:num)', 'Penjualan::printPDF/$1');
$routes->get('Penjualan/printPDF/(:num)', 'Penjualan::printPDF/$1');
$routes->get('penjualan/printPDF', 'Penjualan::printPDF');
$routes->get('Penjualan/printPDF', 'Penjualan::printPDF');
$routes->put('/penjualan/(:segment)', 'Penjualan::update/$1', ['filter' => 'role:admin']);
$routes->put('/penjualan/(:segment)/edit', 'Penjualan::edit/$1', ['filter' => 'role:admin']);


//routes untuk returpenjualan
$routes->get('/returpenjualan/new', 'ReturPenjualan::new');
$routes->resource('returpenjualan');
$routes->post('/returpenjualan', 'ReturPenjualan::create');
$routes->post('/returpenjualan/(:any)', 'ReturPenjualan::delete/$1');
$routes->get('returpenjualan', 'ReturPenjualan::index');
$routes->get('returpenjualan/printPDF/(:num)', 'ReturPenjualan::printPDF/$1');
$routes->get('ReturPenjualan/printPDF/(:num)', 'ReturPenjualan::printPDF/$1');
$routes->get('returpenjualan/printPDF', 'ReturPenjualan::printPDF');
$routes->get('ReturPenjualan/printPDF', 'ReturPenjualan::printPDF');
$routes->put('/returpenjualan/(:segment)', 'ReturPenjualan::update/$1', ['filter' => 'role:admin']);
$routes->put('/returpenjualan/(:segment)/edit', 'ReturPenjualan::edit/$1', ['filter' => 'role:admin']);

//routes untuk penyesuaianstock
$routes->get('/penyesuaianstock/new', 'PenyesuaianStock::new');
$routes->resource('penyesuaianstock');
$routes->post('/penyesuaianstock', 'PenyesuaianStock::create');
$routes->post('/penyesuaianstock/(:any)', 'PenyesuaianStock::delete/$1');
$routes->get('penyesuaianstock', 'PenyesuaianStock::index');
$routes->get('penyesuaianstock/printPDF/(:num)', 'PenyesuaianStock::printPDF/$1');
$routes->get('PenyesuaianStock/printPDF/(:num)', 'PenyesuaianStock::printPDF/$1');
$routes->get('penyesuaianstock/printPDF', 'PenyesuaianStock::printPDF');
$routes->get('PenyesuaianStock/printPDF', 'PenyesuaianStock::printPDF');
$routes->put('/penyesuaianstock/(:segment)', 'PenyesuaianStock::update/$1', ['filter' => 'role:admin']);
$routes->put('/penyesuaianstock/(:segment)/edit', 'PenyesuaianStock::edit/$1', ['filter' => 'role:admin']);


$routes->get('close-period', 'PeriodsController::index');
$routes->get('period-add', 'PeriodsController::add');
$routes->post('/close-period/close', 'PeriodsController::close');
$routes->post('/close-period/open/(:num)', 'PeriodsController::open/$1', ['filter' => 'role:admin']);
$routes->get('close-period/close_book/(:num)', 'PeriodsController::closeBook/$1');
$routes->get('close-period/report/(:num)', 'PeriodsController::report/$1');
$routes->get('close-period/print/(:num)', 'PeriodsController::printPDF/$1');
$routes->delete('close-period/(:num)', 'PeriodsController::delete/$1');

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
$routes->get('/laporanpenjualan', 'LaporanPenjualan::index');
$routes->get('/laporanpenjualan/printPDF/(:num)', 'LaporanPenjualan::printPDF/$1');
$routes->get('/LaporanPenjualan/printPDF/(:num)', 'LaporanPenjualan::printPDF/$1');
$routes->get('/laporanpenjualan/printPDF', 'LaporanPenjualan::printPDF');
$routes->get('/LaporanPenjualan/printPDF', 'LaporanPenjualan::printPDF');
$routes->post('/laporanpenjualan', 'LaporanPenjualan::index');
$routes->get('/LaporanPenjualan', 'LaporanPenjualan::index');
$routes->get('/LaporanPenjualan/printPDF/(:num)', 'LaporanPenjualan::printPDF/$1');
$routes->get('/LaporanPenjualan/printPDF', 'LaporanPenjualan::printPDF');
$routes->post('/LaporanPenjualan', 'LaporanPenjualan::index');


//routes untuk penjualan per salesman per pelanggan per barang
$routes->get('laporanPenjualanP', 'LaporanPenjualanP::index');

//laporanreturpenjualan
$routes->get('/laporanreturpenjualan', 'LaporanReturPenjualan::index');
$routes->get('/laporanreturpenjualan/printPDF/(:num)', 'LaporanReturPenjualan::printPDF/$1');
$routes->get('/LaporanReturPenjualan/printPDF/(:num)', 'LaporanReturPenjualan::printPDF/$1');
$routes->get('/laporanreturpenjualan/printPDF', 'LaporanReturPenjualan::printPDF');
$routes->get('/LaporanReturPenjualan/printPDF', 'LaporanReturPenjualan::printPDF');
$routes->post('/laporanreturpenjualan', 'LaporanReturPenjualan::index');
$routes->get('/LaporanReturPenjualan', 'LaporanReturPenjualan::index');
$routes->get('/LaporanReturPenjualan/printPDF/(:num)', 'LaporanReturPenjualan::printPDF/$1');
$routes->get('/LaporanReturPenjualan/printPDF', 'LaporanReturPenjualan::printPDF');
$routes->post('/LaporanReturPenjualan', 'LaporanReturPenjualan::index');
