<?php

namespace App\Controllers\transaksi\pembelian;

use App\Models\transaksi\pembelian\ModelPembelian;
use App\Models\transaksi\pembelian\ModelPembelianDetail;
use App\Models\transaksi\ModelRiwayatTransaksi;
use App\Models\transaksi\ModelRiwayatHutang;
use App\Models\transaksi\ModelMutasiStock;
use App\Models\setup_persediaan\ModelSatuan;
use App\Models\setup_persediaan\ModelStock;
use App\Models\setup_persediaan\ModelStockGudang;
use App\Models\setup_persediaan\ModelLokasi;
use App\Models\setup\ModelAntarmuka;
use App\Models\setup\ModelSetupBuku;
use App\Models\setup\ModelSetupsupplier;
use App\Models\setup\ModelHutangPiutang;
use App\Services\PembelianService;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Config\Database;
use TCPDF;

class Pembelian extends ResourceController
{
    protected $pembelianService;
    protected $objAntarmuka;
    protected $objSetupBuku;
    protected $objSetupsupplier;
    protected $objLokasi;
    protected $objSatuan;
    protected $objStock;
    protected $objPembelian;
    protected $objPembelianDetail;
    protected $db;

    function __construct()
    {

        $this->objAntarmuka = new ModelAntarmuka();
        $this->objSetupBuku = new ModelSetupBuku();
        $this->objSetupsupplier = new ModelSetupsupplier();
        $this->objLokasi = new ModelLokasi();
        $this->objSatuan = new ModelSatuan();
        $this->objStock = new ModelStock();
        $this->objPembelian = new ModelPembelian();
        $this->objPembelianDetail = new ModelPembelianDetail();

        $this->db = Database::connect();
        helper('terbilang');

        $this->pembelianService = new PembelianService(
            new ModelPembelian(),
            new ModelPembelianDetail(),
            new ModelStockGudang(),
            new ModelSetupBuku(),
            new ModelRiwayatTransaksi(),
            new ModelRiwayatHutang(),
            new ModelMutasiStock(),
            new ModelSetupsupplier(),
            new ModelHutangPiutang(),
            $this->db
        );
    }

    private function getCommonData(): array
    {

        // Ambil kode kas dan setara di interface
        $kodeKas = $this->objAntarmuka->getKodeKas();

        if ($kodeKas) {
            // pisah kodekas dengan koma
            $kodeKas = explode(',', $kodeKas);
        }

        return [
            'dtlokasi' => $this->objLokasi->getAll(),
            'dtsatuan' => $this->objSatuan->getAll(),
            'dtsetupsupplier' => $this->objSetupsupplier->getAll(),
            'dtrekening' => $this->objSetupBuku->getRekeningKas($kodeKas),
        ];
    }

    /**
     * Return an array of resource, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $month = date('m');
        $year = date('Y');

        $data = $this->getCommonData();

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

        $data['dtpembelian'] = $this->objPembelian->getAll();

        return view('transaksi/pembelian_v/pembelian/index', $data);
    }

    public function printPDF($id = null)
    {
        // Jika $id tidak diberikan, ambil semua data
        if ($id === null) {
            $data['dtpembelian'] = $this->objPembelian->getAll();
        } else {
            // Jika $id diberikan, ambil data berdasarkan ID dengan join
            $data['dtpembelian'] = $this->objPembelian->getById($id);
            if (empty($data['dtpembelian'])) {
                return redirect()->back()->with('error', 'Data tidak ditemukan.');
            }
            $data['dtdetail'] = $this->objPembelianDetail->select('*')
                ->where('id_pembelian', $id)
                ->findAll();
            $data['dtpembelian']->terbilang = terbilang($data['dtpembelian']->grand_total);
        }


        // Debugging: Tampilkan konten HTML sebelum PDF
        $html = view('transaksi/pembelian_v/pembelian/printPDF', $data);
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
        $pdf->Output('nota_pembelian.pdf', 'I');
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
            $dtpembelian = $this->objPembelian->find($id);

            if (!$dtpembelian) {
                return $this->response->setJSON([
                    'status' => 'false',
                    'message' => 'Data tidak ditemukan',
                ]);
            }

            $dtdetail = $this->objPembelianDetail->select('pembelian1_detail.*, stock1.conv_factor')
                ->join('stock1', 'pembelian1_detail.id_stock = stock1.id_stock', 'left')
                ->where('pembelian1_detail.id_pembelian', $id)
                ->findAll();

            $msg = [
                'status' => 'success',
                'data' => [
                    'header' => $dtpembelian,
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
        $data = $this->getCommonData();

        return view('transaksi/pembelian_v/pembelian/new', $data);
    }

    protected function getHeaderDataFromRequest(): array
    {
        return [
            'tanggal' => $this->request->getVar('tanggal'),
            'nota' => $this->request->getVar('nota'),
            'id_setupsupplier' => $this->request->getVar('id_setupsupplier'),
            'TOP' => $this->request->getVar('TOP'),
            'tgl_jatuhtempo' => $this->request->getVar('tgl_jatuhtempo'),
            'tgl_invoice' => $this->request->getVar('tgl_invoice'),
            'no_invoice' => $this->request->getVar('no_invoice'),
            'id_lokasi' => $this->request->getVar('id_lokasi'),
            'id_setupbuku' => $this->request->getVar('id_setupbuku'),
            'disc_cash' => $this->request->getVar('disc_cash') ?? 0,
            'disc_cash_rp' => $this->request->getVar('disc_cash_rp_raw') ?? 0,
            'dpp' => $this->request->getVar('dpp_raw') ?? 0,
            'ppn' => $this->request->getVar('ppn') ?? 0,
            'ppn_option' => $this->request->getVar('ppn_option'),
            'tunai' => floatval($this->request->getVar('tunai_raw')) ?? 0,
            'sub_total' => floatval($this->request->getVar('sub_total_raw')) ?? 0,
            'grand_total' => floatval($this->request->getVar('grand_total_raw')) ?? 0,
            'hutang' => floatval($this->request->getVar('hutang_raw')) ?? 0,
        ];
    }

    protected function saveData($id = null)
    {
        $headerData = $this->getHeaderDataFromRequest();
        $detailData = $this->request->getVar('detail');

        try {

            $pembelian_id = $this->pembelianService->save(
                $headerData,
                $detailData,
                $id
            );

            if ($pembelian_id) {
                $message = $id ? 'Data berhasil diubah!' : 'Data berhasil disimpan!';
                return $this->response->setJSON([
                    'success' => true,
                    'message' => $message,
                    'redirect_url' => site_url('transaksi/pembelian/pembelian')
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
        return $this->saveData(); // Redirect to saveData method for processing
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
        $dtpembelian = $this->objPembelian->find($id);

        // Cek jika data tidak ditemukan
        if (!$dtpembelian) {
            return redirect()->to(site_url('transaksi/pembelian/pembelian'))->with('error', 'Data tidak ditemukan');
        }

        $data = $this->getCommonData();

        // Ambil data detail berdasarkan ID
        $data['dtdetail'] = $this->objPembelianDetail->select('pembelian1_detail.*, stock1.conv_factor')
            ->join('stock1', 'pembelian1_detail.id_stock = stock1.id_stock', 'left')
            ->where('pembelian1_detail.id_pembelian', $id)
            ->findAll();

        $data['dtheader'] = $dtpembelian;
        return view('transaksi/pembelian_v/pembelian/edit', $data);
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

        return $this->saveData($id); // Redirect to saveData method for processing
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
        $this->db->table('pembelian1')->where(['id_pembelian' => $id])->delete();
        return redirect()->to(site_url('transaksi/pembelian'))->with('Sukses', 'Data Berhasil Dihapus');
    }

    public function lookupStock()
    {
        $term = $this->request->getGet('term');
        $id_supplier = $this->request->getGet('supplier');

        $results = $this->objStock
            ->select('stock1.id_stock, stock1.kode, stock1.nama_barang, stock1.id_satuan, stock1.id_satuan2, stock1.conv_factor,
            sat1.kode_satuan as satuan_1,
            sat2.kode_satuan as satuan_2,
            harga1.harga_beli')
            ->join('satuan1 sat1', 'stock1.id_satuan = sat1.id_satuan', 'left')
            ->join('satuan1 sat2', 'stock1.id_satuan2 = sat2.id_satuan', 'left')
            ->join('harga1', 'stock1.id_stock = harga1.id_stock', 'left')
            ->where('stock1.id_setupsupplier', $id_supplier)
            ->groupStart()
            ->like('stock1.nama_barang', $term)
            ->orLike('stock1.kode', $term)
            ->groupEnd()
            ->limit(5)
            ->findAll();

        return $this->response->setJSON($results);
    }
}
