<?php

namespace App\Controllers\transaksi\penjualan;

use App\Models\setup_persediaan\ModelLokasi;
use App\Models\setup_persediaan\ModelStock;
use App\Models\setup_persediaan\ModelStockGudang;
use App\Models\transaksi\penjualan\ModelPenjualan;
use App\Models\transaksi\penjualan\ModelReturPenjualan;
use App\Models\transaksi\penjualan\ModelReturPenjualanDetail;
use App\Models\transaksi\penjualan\ModelPenjualanDetail;
use App\Models\transaksi\ModelRiwayatTransaksi;
use App\Models\setup\ModelSetuppelanggan;
use App\Models\setup\ModelSetupsalesman;
use App\Models\setup\ModelSetupBuku;
use App\Models\setup\ModelHutangPiutang;
use App\Models\setup\ModelAntarmuka;
use App\Services\ReturPenjualanService;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use TCPDF;

class ReturPenjualan extends ResourceController
{
    protected $objLokasi, $objSatuan, $objSetupsalesman, $objPenjualan, $objReturPenjualan, $objReturPenjualanDetail, $objPelanggan, $objStock, $db;
    protected $returPenjualanService;

    function __construct()
    {
        $this->objLokasi = new ModelLokasi();
        $this->objSetupsalesman = new ModelSetupsalesman();
        $this->objPelanggan = new ModelSetuppelanggan();
        $this->objPenjualan = new ModelPenjualan();
        $this->objReturPenjualan = new ModelReturPenjualan();
        $this->objReturPenjualanDetail = new ModelReturPenjualanDetail();
        $this->objStock = new ModelStock();
        $this->db = \Config\Database::connect();

        $this->returPenjualanService = new ReturPenjualanService(
            $this->objReturPenjualan,
            $this->objReturPenjualanDetail,
            new ModelStockGudang(),
            new ModelSetupBuku(),
            new ModelRiwayatTransaksi(),
            new ModelHutangPiutang(),
            new ModelAntarmuka(),
            $this->objPelanggan,
            $this->objSetupsalesman,
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
        $data['dtreturpenjualan'] = $this->objReturPenjualan->getAll();

        return view('transaksi/penjualan_v/returpenjualan/index', $data);
    }

    protected function getCommonData()
    {
        // Menggunakan Query Builder untuk join tabel lokasi1 dan satuan1
        $data['dtlokasi'] = $this->objLokasi->getAll();
        $data['dtsalesman'] = $this->objSetupsalesman->findAll();
        $data['dtpelanggan'] = $this->objPelanggan->findAll();

        return $data;
    }

    public function printPDF($id = null)
    {
        // Jika $id tidak diberikan, ambil semua data
        if ($id === null) {
            $data['dtreturpenjualan'] = $this->objReturPenjualan->getAll();
        } else {
            // Jika $id diberikan, ambil data berdasarkan ID dengan join
            $data['dtreturpenjualan'] = $this->objReturPenjualan->getById($id);
            if (empty($data['dtreturpenjualan'])) {
                return redirect()->back()->with('error', 'Data tidak ditemukan.');
            }
        }

        $data = $this->getCommonData();
        $data['dtlokasi'] = $this->objLokasi->getAll();
        $data['dtsatuan'] = $this->objSatuan->getAll();
        // Debugging: Tampilkan konten HTML sebelum PDF
        $html = view('transaksi/penjualan_v/returpenjualan/printPDF', $data);
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
        $pdf->Output('retur_penjualan.pdf', 'D');
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
        // Menggunakan Query Builder untuk join tabel lokasi1 dan satuan1
        $data = $this->getCommonData();
        $data['dtpenjualan'] = $this->objPenjualan->getAll();

        return view('transaksi/penjualan_v/returpenjualan/new', $data);
    }

    protected function getHeaderDataFromRequest(): array
    {
        return [
            'tanggal' => $this->request->getVar('tanggal'),
            'nota' => $this->request->getVar('nota'),
            'id_pelanggan' => $this->request->getVar('id_pelanggan'),
            'id_salesman' => $this->request->getVar('id_salesman'),
            'id_lokasi' => $this->request->getVar('id_lokasi'),
            'id_penjualan' => $this->request->getVar('id_penjualan'),
            'opsi_return' => $this->request->getVar('opsi_return'),
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

            $returpenjualan_id = $this->returPenjualanService->save(
                $headerData,
                $detailData,
                $id
            );

            if ($returpenjualan_id) {
                $message = $id ? 'Data berhasil diubah!' : 'Data berhasil disimpan!';
                return $this->response->setJSON([
                    'success' => true,
                    'message' => $message,
                    'redirect_url' => site_url('transaksi/penjualan/returpenjualan')
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
        $dtheader = $this->objReturPenjualan->getById($id);

        // Cek jika data tidak ditemukan
        if (!$dtheader) {
            return redirect()->to(site_url('returpenjualan'))->with('error', 'Data tidak ditemukan');
        }

        // Ambil data detail berdasarkan ID
        $dtdetail = $this->objReturPenjualanDetail->getById($id);


        // Lanjutkan jika semua pengecekan berhasil
        $data = $this->getCommonData();
        $data['dtpenjualan'] = $this->objPenjualan->getAll();
        $data['dtheader'] = $dtheader;
        $data['dtdetail'] = $dtdetail;
        return view('transaksi/penjualan_v/returpenjualan/edit', $data);
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
        $existingData = $this->objReturPenjualan->find($id);
        if (!$existingData) {
            return redirect()->to(site_url('returpenjualan'))->with('error', 'Data tidak ditemukan');
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
        $this->db->table('returpenjualan1')->where(['id_returpenjualan' => $id])->delete();
        return redirect()->to(site_url('returpenjualan'))->with('Sukses', 'Data Berhasil Dihapus');
    }
}
