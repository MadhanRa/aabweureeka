<?php

namespace App\Controllers;

use App\Models\setup_persediaan\ModelLokasi;
use App\Models\setup_persediaan\ModelSatuan;
use App\Models\transaksi\ModelHasilSablon;
use App\Models\setup\ModelSetupsupplier;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use TCPDF;

class LaporanHasilSablon extends ResourceController
{
    protected $objHasilSablon;
    protected $objLokasi;
    protected $objSatuan;
    protected $objSetupsupplier;
    protected $db;
    function __construct()
    {
        $this->objHasilSablon = new ModelHasilSablon();
        $this->objLokasi = new ModelLokasi();
        $this->objSatuan = new ModelSatuan();
        $this->objSetupsupplier = new ModelSetupsupplier();
        $this->db = \Config\Database::connect();
    }
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $tglawal = $this->request->getVar('tglawal') ? $this->request->getVar('tglawal') : '';
        $tglakhir = $this->request->getVar('tglakhir') ? $this->request->getVar('tglakhir') : '';

        // Panggil model untuk mendapatkan data laporan
        $dthasilsablon = $this->objHasilSablon->get_laporan($tglawal, $tglakhir);

        // $data['dtpembelian'] = $rowdata;
        $data = [
            'dthasilsablon'     => $dthasilsablon,
            'dtlokasi'          => $this->objLokasi->getAll(),
            'dtsatuan'          => $this->objSatuan->getAll(),
            'dtsetupsupplier'   => $this->objSetupsupplier->getAll(),
            'tglawal'           => $tglawal,
            'tglakhir'          => $tglakhir,
        ];
        return view('laporanhasilsablon/index', $data);
    }

    public function printPDF()
    {
        $tglawal = $this->request->getVar('tglawal') ?? '';
        $tglakhir = $this->request->getVar('tglakhir') ?? '';

        // Ambil laporan dari model
        $dthasilsablon = $this->objHasilSablon->get_laporan($tglawal, $tglakhir);

        // Konfigurasi TCPDF
        $pdf = new TCPDF();
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);

        // Tambahkan konten ke PDF
        $html = view('laporanhasilsablon/printPDF', [
            'dthasilsablon' => $dthasilsablon,
            'tglawal' => $tglawal,
            'tglakhir' => $tglakhir,
        ]);
        $pdf->writeHTML($html);

        // Output PDF
        $this->response->setHeader('Content-Type', 'application/pdf');
        $pdf->Output('Laporan_Hasil_Sablon.pdf', 'I');
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
        //
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        //
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
        //
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
        //
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
        //
    }
}
