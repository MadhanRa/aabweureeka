<?php

namespace App\Controllers\transaksi\pembelian;

use App\Models\transaksi\pembelian\ModelPembelian;
use App\Models\transaksi\pembelian\ModelReturPembelian;
use App\Models\transaksi\pembelian\ModelPembelianDetail;
use App\Models\transaksi\pembelian\ModelReturPembelianDetail;
use App\Models\transaksi\ModelRiwayatTransaksi;
use App\Models\transaksi\ModelRiwayatHutang;
use App\Models\transaksi\ModelHutang;
use App\Models\transaksi\ModelMutasiStock;
use App\Models\setup_persediaan\ModelSatuan;
use App\Models\setup_persediaan\ModelStockGudang;
use App\Models\setup_persediaan\ModelStock;
use App\Models\setup_persediaan\ModelLokasi;
use App\Models\setup\ModelAntarmuka;
use App\Models\setup\ModelSetupBank;
use App\Models\setup\ModelSetupBuku;
use App\Models\setup\ModelSetupsupplier;
use App\Services\ReturPembelianService;


use App\ValueObjects\DetailItem;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use TCPDF;

class ReturPembelian extends ResourceController
{
    protected $objLokasi, $objSatuan, $objSetupBank, $objPembelian, $objSetupsupplier, $objStock, $db, $objAntarmuka, $objSetupBuku, $objPembelianDetail, $objStockGudang, $objRiwayatTransaksi, $objReturPembelian, $objReturPembelianDetail;

    protected $returPembelianService;

    protected $detailItemPembelian;

    function __construct()
    {
        // Setup
        $this->objAntarmuka = new ModelAntarmuka();
        $this->objSetupBuku = new ModelSetupBuku();
        $this->objSetupsupplier = new ModelSetupsupplier();
        $this->objSetupBank = new ModelSetupBank();

        // Setup Persediaan
        $this->objLokasi = new ModelLokasi();
        $this->objSatuan = new ModelSatuan();
        $this->objStock = new ModelStock();
        $this->objStockGudang = new ModelStockGudang();

        // Transaksi Pembelian
        $this->objPembelian = new ModelPembelian();
        $this->objReturPembelian = new ModelReturPembelian();
        $this->objPembelianDetail = new ModelPembelianDetail();
        $this->objReturPembelianDetail = new ModelReturPembelianDetail();
        $this->db = \Config\Database::connect();

        $this->objRiwayatTransaksi = new ModelRiwayatTransaksi();

        $this->returPembelianService = new ReturPembelianService(
            $this->objPembelian,
            $this->objReturPembelian,
            $this->objReturPembelianDetail,
            $this->objStockGudang,
            $this->objSetupBuku,
            $this->objRiwayatTransaksi,
            new ModelRiwayatHutang(),
            new ModelHutang(),
            new ModelMutasiStock(),
            $this->objSetupsupplier,
            $this->db
        );
    }
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $month = date('m');
        $year = date('Y');

        if (!in_groups('admin')) {
            // Periksa apakah tutup buku periode bulan ini ada
            $cek = $this->db->table('closed_periods')->where('month', $month)->where('year', $year)->where('is_closed', 1)->get();
            $closeBookCheck = $cek->getResult();
            if ($closeBookCheck == TRUE) {
                $data['is_closed'] = 'TRUE';
            } else {
                $data['is_closed'] = 'FALSE';
            }
        } else {
            $data['is_closed'] = 'FALSE';
        }
        // Menggunakan Query Builder untuk join tabel lokasi1 dan satuan1
        $data['dtreturpembelian'] = $this->objReturPembelian->getAll();

        return view('transaksi/pembelian_v/returpembelian/index', $data);
    }

    public function printPDF($id = null)
    {
        // Jika $id tidak diberikan, ambil semua data
        if ($id === null) {
            $data['dtreturpembelian'] = $this->objReturPembelian->getAll();
        } else {
            // Jika $id diberikan, ambil data berdasarkan ID dengan join
            $data['dtreturpembelian'] = $this->objReturPembelian->getById($id);
            if (empty($data['dtreturpembelian'])) {
                return redirect()->back()->with('error', 'Data tidak ditemukan.');
            }
        }

        $data['dtlokasi'] = $this->objLokasi->getAll();
        $data['dtsatuan'] = $this->objSatuan->getAll();
        $data['dtsetupsupplier'] = $this->objSetupsupplier->getAll();
        $data['dtpembelian'] = $this->objPembelian->getAll();
        // Debugging: Tampilkan konten HTML sebelum PDF
        $html = view('transaksi/pembelian_v/returpembelian/printPDF', $data);
        // echo $html;
        // exit; // Jika perlu debugging

        // Buat PDF baru
        $pdf = new TCPDF('landscape', PDF_UNIT, 'A4', true, 'UTF-8', false);

        // Hapus header/footer default
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Set font
        $pdf->SetFont('helvetica', '', 12);

        // Tambah halaman baru
        $pdf->AddPage();

        // Cetak konten menggunakan WriteHTML
        $pdf->writeHTML($html, true, false, true, false, '');

        // Set tipe respons menjadi PDF
        $this->response->setContentType('application/pdf');
        $pdf->Output('retur_pembelian.pdf', 'D');
    }

    /**
     * Return the properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function show($id = null)
    {
        //
    }

    /**
     * Return a new resource object, with default properties.
     *
     * @return ResponseInterface
     */
    public function new()
    {
        // Ambil kode kas dan setara di interface
        $kodeKas = $this->objAntarmuka->getKodeKas();

        if ($kodeKas) {
            // pisah kodekas dengan koma
            $kodeKas = explode(',', $kodeKas);
        }


        // Menggunakan Query Builder untuk join tabel lokasi1 dan satuan1
        $data['dtlokasi'] = $this->objLokasi->getAll();
        $data['dtsetupsupplier'] = $this->objSetupsupplier->getAll();
        $data['dtrekening'] = $this->objSetupBuku->getRekeningKas($kodeKas);

        return view('transaksi/pembelian_v/returpembelian/new', $data);
    }

    protected function getHeaderDataFromRequest(): array
    {
        return [
            'tanggal' => $this->request->getVar('tanggal'),
            'nota' => $this->request->getVar('nota'),
            'id_setupsupplier' => $this->request->getVar('id_setupsupplier'),
            'id_lokasi' => $this->request->getVar('id_lokasi'),
            'id_pembelian' => $this->request->getVar('id_pembelian'),
            'opsi_return' => $this->request->getVar('opsi_return'),
            'disc_cash' => $this->request->getVar('disc_cash') ?? 0,
            'disc_cash_rp' => $this->request->getVar('disc_cash_rp_raw') ?? 0,
            'dpp' => $this->request->getVar('dpp_raw') ?? 0,
            'ppn' => $this->request->getVar('ppn') ?? 0,
            'ppn_option' => $this->request->getVar('ppn_option'),
            'sub_total' => $this->request->getVar('sub_total_raw') ?? 0,
            'grand_total' => $this->request->getVar('grand_total_raw') ?? 0,
        ];
    }

    protected function saveData($id = null)
    {
        $headerData = $this->getHeaderDataFromRequest();
        $detailData = $this->request->getVar('detail');

        try {

            $returPembelian_id = $this->returPembelianService->save(
                $headerData,
                $detailData,
                $id
            );

            if ($returPembelian_id) {
                $message = $id ? 'Data berhasil diubah!' : 'Data berhasil disimpan!';
                return $this->response->setJSON([
                    'success' => true,
                    'message' => $message,
                    'redirect_url' => site_url('transaksi/pembelian/returpembelian')
                ]);
            }
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'status' => 'false',
                'message' => 'Error: ' . $e->getMessage(),
            ]);
        }
    }


    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        return $this->saveData();
    }

    /**
     * Return the editable properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function edit($id = null)
    {
        // Cek apakah pengguna memiliki peran admin
        if (!in_groups('admin')) {
            return redirect()->to('/')->with('error', 'Anda tidak memiliki akses');
        }

        // Ambil data berdasarkan ID
        $dtreturpembelian = $this->objReturPembelian->find($id);

        // Cek jika data tidak ditemukan
        if (!$dtreturpembelian) {
            return redirect()->to(site_url('returpembelian'))->with('error', 'Data tidak ditemukan');
        }


        // Lanjutkan jika semua pengecekan berhasil
        $data['dtreturpembelian'] = $dtreturpembelian;
        $data['dtlokasi'] = $this->objLokasi->getAll();
        $data['dtsatuan'] = $this->objSatuan->getAll();
        $data['dtsetupsupplier'] = $this->objSetupsupplier->getAll();
        $data['dtpembelian'] = $this->objPembelian->getAll();
        return view('transaksi/pembelian_v/returpembelian/edit', $data);
    }

    /**
     * Add or update a model resource, from "posted" properties.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function update($id = null)
    {
        // Cek apakah pengguna memiliki peran admin
        if (!in_groups('admin')) {
            return redirect()->to('/')->with('error', 'Anda tidak memiliki akses');
        }

        // Cek apakah data dengan ID yang diberikan ada di database
        $existingData = $this->objReturPembelian->find($id);
        if (!$existingData) {
            return redirect()->to(site_url('returpembelian'))->with('error', 'Data tidak ditemukan');
        }

        return $this->saveData($id);
    }

    /**
     * Delete the designated resource object from the model.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function delete($id = null)
    {
        $this->db->table('returpembelian1')->where(['id_returpembelian' => $id])->delete();
        return redirect()->to(site_url('returpembelian'))->with('Sukses', 'Data Berhasil Dihapus');
    }

    public function lookupReturPembelian()
    {
        $param['draw'] = isset($_REQUEST['draw']) ? $_REQUEST['draw'] : '';
        $param['start'] = isset($_REQUEST['start']) ? (int)$_REQUEST['start'] : 0;
        $param['length'] = isset($_REQUEST['length']) ? (int)$_REQUEST['length'] : 10;
        $param['search_value'] = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value'] : '';

        $results = $this->objReturPembelian->searchAndDisplay(
            $param['search_value'],
            $param['start'],
            $param['length']
        );
        $total_count = $this->objReturPembelian->searchAndDisplay(
            $param['search_value']
        );

        $json_data = array(
            'draw' => intval($param['draw']),
            'recordsTotal' => count($total_count),
            'recordsFiltered' => count($total_count),
            'data_items' => $results,
            'token' => csrf_hash() // Add the CSRF token to the response
        );

        echo json_encode($json_data);
    }
}
