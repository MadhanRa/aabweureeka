<?php

namespace App\Controllers\transaksi\penjualan;

use App\Models\setup_persediaan\ModelLokasi;
use App\Models\setup_persediaan\ModelStock;
use App\Models\setup_persediaan\ModelStockGudang;
use App\Models\transaksi\penjualan\ModelPenjualan;
use App\Models\transaksi\penjualan\ModelPenjualanDetail;
use App\Models\transaksi\ModelRiwayatTransaksi;
use App\Models\transaksi\ModelRiwayatPiutang;
use App\Models\transaksi\ModelMutasiStock;
use App\Models\setup\ModelSetuppelanggan;
use App\Models\setup\ModelSetupsalesman;
use App\Models\setup\ModelSetupBuku;
use App\Models\setup\ModelHutangPiutang;
use App\Models\setup\ModelAntarmuka;
use App\Services\PenjualanService;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use TCPDF;

class Penjualan extends ResourceController
{
    protected $objLokasi;
    protected $objSetupsalesman;
    protected $objPenjualan;
    protected $objPenjualanDetail;
    protected $objSetuppelanggan;
    protected $objStock;
    protected $penjualanService;
    protected $db;

    function __construct()
    {
        $this->objLokasi = new ModelLokasi();
        $this->objSetupsalesman = new ModelSetupsalesman();
        $this->objPenjualan = new ModelPenjualan();
        $this->objPenjualanDetail = new ModelPenjualanDetail();
        $this->objSetuppelanggan = new ModelSetuppelanggan();
        $this->objStock = new ModelStock();
        $this->db = \Config\Database::connect();


        // Inisialisasi service
        $this->penjualanService = new PenjualanService(
            $this->objPenjualan,
            $this->objPenjualanDetail,
            new ModelStockGudang(),
            new ModelSetupBuku(),
            new ModelRiwayatTransaksi(),
            new ModelRiwayatPiutang(),
            new ModelHutangPiutang(),
            new ModelMutasiStock(),
            new ModelAntarmuka(),
            $this->objSetuppelanggan,
            $this->objSetupsalesman,
            $this->db
        );
        helper('terbilang');
    }

    protected function getCommonData()
    {
        return [
            'dtlokasi' => $this->objLokasi->getAll(),
            'dtsalesman' => $this->objSetupsalesman->findAll(),
            'dtpelanggan' => $this->objSetuppelanggan->findAll(),
        ];
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
        $data['dtpenjualan'] = $this->objPenjualan->getAll();

        return view('transaksi/penjualan_v/penjualan/index', $data);
    }

    public function printPDF($id = null)
    {
        // Jika $id tidak diberikan, ambil semua data
        if ($id === null) {
            $data['dtpenjualan'] = $this->objPenjualan->getAll();
        } else {
            // Jika $id diberikan, ambil data berdasarkan ID dengan join
            $data['dtpenjualan'] = $this->objPenjualan->getById($id);
            if (empty($data['dtpenjualan'])) {
                return redirect()->back()->with('error', 'Data tidak ditemukan.');
            }
            $data['dtdetail'] = $this->objPenjualanDetail->select('*')
                ->where('id_penjualan', $id)
                ->findAll();
            $data['dtpenjualan']->terbilang = terbilang($data['dtpenjualan']->grand_total);
        }

        // Debugging: Tampilkan konten HTML sebelum PDF
        $html = view('transaksi/penjualan_v/penjualan/printPDF', $data);
        // echo $html;
        // exit; // Jika perlu debugging

        // Buat PDF baru
        $pdf = new TCPDF('landscape', PDF_UNIT, 'A4', true, 'UTF-8', false);

        // Hapus header/footer default
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Set margin
        $pdf->SetMargins(5, 5, 5);

        // Set font
        $pdf->SetFont('Helvetica', '', 12);
        // Tambah halaman baru
        $pdf->AddPage();


        // Cetak konten menggunakan WriteHTML
        $pdf->writeHTML($html, true, false, true, false, '');

        // Set tipe respons menjadi PDF
        $this->response->setContentType('application/pdf');
        $pdf->Output('nota_penjualan.pdf', 'I');
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
        if ($this->request->isAJAX()) {
            $dtpenjualan = $this->objPenjualan->find($id);

            if (!$dtpenjualan) {
                return $this->response->setJSON([
                    'status' => 'false',
                    'message' => 'Data tidak ditemukan',
                ]);
            }

            $dtdetail = $this->objPenjualanDetail->select('penjualan1_detail.*, stock1.conv_factor')
                ->join('stock1', 'penjualan1_detail.id_stock = stock1.id_stock', 'left')
                ->where('penjualan1_detail.id_penjualan', $id)
                ->findAll();

            $msg = [
                'status' => 'success',
                'data' => [
                    'header' => $dtpenjualan,
                    'detail' => $dtdetail,
                ]
            ];

            return $this->response->setJSON($msg);
        } else {
            return redirect()->to('/')->with('error', 'Anda tidak memiliki akses');
        }
    }

    /**
     * Return a new resource object, with default properties.
     *
     * @return ResponseInterface
     */
    public function new()
    {
        // Menggunakan Query Builder untuk join tabel lokasi1 dan satuan1
        $data = $this->getCommonData();
        $data['formAction'] = site_url('transaksi/penjualan/penjualan');
        $data['formMethod'] = '';
        $data['data'] = null;
        $data['isEdit'] = false;

        return view('transaksi/penjualan_v/penjualan/new', $data);
    }

    protected function getHeaderDataFromRequest(): array
    {
        return [
            'tanggal' => $this->request->getVar('tanggal'),
            'nota' => $this->request->getVar('nota'),
            'id_pelanggan' => $this->request->getVar('id_pelanggan'),
            'TOP' => $this->request->getVar('TOP'),
            'tgl_jatuhtempo' => $this->request->getVar('tgl_jatuhtempo'),
            'id_salesman' => $this->request->getVar('id_salesman'),
            'id_lokasi' => $this->request->getVar('id_lokasi'),
            'no_fp' => $this->request->getVar('no_fp') ?? '',
            'opsi_pembayaran' => $this->request->getVar('opsi_pembayaran'),
            'disc_cash' => $this->request->getVar('disc_cash') ?? 0,
            'netto' => $this->request->getVar('netto_raw') ?? 0,
            'ppn' => $this->request->getVar('ppn') ?? 0,
            'ppn_option' => $this->request->getVar('ppn_option'),
            'sub_total' => floatval($this->request->getVar('sub_total_raw')) ?? 0,
            'grand_total' => floatval($this->request->getVar('grand_total_raw')) ?? 0,
        ];
    }

    protected function saveData($id = null)
    {
        $headerData = $this->getHeaderDataFromRequest();
        $detailData = $this->request->getVar('detail');

        try {

            $penjualan_id = $this->penjualanService->save(
                $headerData,
                $detailData,
                $id
            );

            if ($penjualan_id) {
                $message = $id ? 'Data berhasil diubah!' : 'Data berhasil disimpan!';
                return $this->response->setJSON([
                    'success' => true,
                    'message' => $message,
                    'redirect_url' => site_url('transaksi/penjualan/penjualan')
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

        $data = $this->getCommonData();

        // Ambil data berdasarkan ID
        $dtpenjualan = $this->objPenjualan->find($id);

        // Ambil data detail berdasarkan ID
        $data['dtdetail'] = $this->objPenjualanDetail->select('penjualan1_detail.*, stock1.conv_factor, harga1.harga_jualinc, harga1.harga_jualexc')
            ->join('stock1', 'penjualan1_detail.id_stock = stock1.id_stock', 'left')
            ->join('harga1', 'penjualan1_detail.id_stock = harga1.id_stock', 'left')
            ->where('penjualan1_detail.id_penjualan', $id)
            ->findAll();

        // Cek jika data tidak ditemukan
        if (!$dtpenjualan) {
            return redirect()->to(site_url('pembelian'))->with('error', 'Data tidak ditemukan');
        }


        // Lanjutkan jika semua pengecekan berhasil
        $data['dtheader'] = $dtpenjualan;
        return view('transaksi/penjualan_v/penjualan/edit', $data);
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
        $this->db->table('penjualan1')->where(['id_penjualan' => $id])->delete();
        return redirect()->to(site_url('transaksi/penjualan_v/penjualan'))->with('Sukses', 'Data Berhasil Dihapus');
    }

    public function lookupPenjualan()
    {
        $param['draw'] = isset($_REQUEST['draw']) ? $_REQUEST['draw'] : '';
        $param['start'] = isset($_REQUEST['start']) ? (int)$_REQUEST['start'] : 0;
        $param['length'] = isset($_REQUEST['length']) ? (int)$_REQUEST['length'] : 10;
        $param['search_value'] = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value'] : '';

        $results = $this->objPenjualan->searchAndDisplay(
            $param['search_value'],
            $param['start'],
            $param['length']
        );
        $total_count = $this->objPenjualan->searchAndDisplay(
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

    public function lookupStock()
    {
        $param['draw'] = isset($_REQUEST['draw']) ? $_REQUEST['draw'] : '';
        $param['start'] = isset($_REQUEST['start']) ? (int)$_REQUEST['start'] : 0;
        $param['length'] = isset($_REQUEST['length']) ? (int)$_REQUEST['length'] : 10;
        $param['search_value'] = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value'] : '';
        $param['id_lokasi'] = isset($_REQUEST['id_lokasi']) ? $_REQUEST['id_lokasi'] : null;
        $param['id_salesman'] = isset($_REQUEST['id_salesman']) ? $_REQUEST['id_salesman'] : null;


        $results = $this->objStock->searchAndDisplayStockGudang(
            $param['search_value'],
            $param['start'],
            $param['length'],
            $param['id_lokasi'],
            $param['id_salesman']
        );
        $total_count = $this->objStock->searchAndDisplayStockGudang(
            $param['search_value'],
            null,
            null,
            $param['id_lokasi'],
            $param['id_salesman']
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
