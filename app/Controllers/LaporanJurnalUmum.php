<?php

namespace App\Controllers;

use App\Models\transaksi\akuntansi\ModelJurnalUmum;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use TCPDF;

class LaporanJurnalUmum extends ResourceController
{
    protected $objJurnalUmum;
    protected $db;

    function __construct()
    {
        $this->objJurnalUmum = new ModelJurnalUmum();
        $this->db = \Config\Database::connect();
    }

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $tglawal = $this->request->getVar('tglawal') ?? '';
        $tglakhir = $this->request->getVar('tglakhir') ?? '';

        // Ambil laporan dari model
        $laporan = $this->objJurnalUmum->get_laporan($tglawal, $tglakhir);

        $data = [
            'laporan' => $laporan['data'],           // Data laporan
            'total_debet' => $laporan['total_debet'],  // Total debit
            'total_kredit' => $laporan['total_kredit'], // Total kredit
            'tglawal' => $tglawal,
            'tglakhir' => $tglakhir,
        ];

        return view('laporanjurnalumum/index', $data);
    }

    public function printPDF()
    {
        $tglawal = $this->request->getVar('tglawal') ?? '';
        $tglakhir = $this->request->getVar('tglakhir') ?? '';

        // Ambil laporan dari model
        $laporan = $this->objJurnalUmum->get_laporan($tglawal, $tglakhir);

        // Konfigurasi TCPDF
        $pdf = new TCPDF();
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);

        // Tambahkan konten ke PDF
        $html = view('laporanjurnalumum/printPDF', [
            'laporan' => $laporan['data'],
            'total_debet' => $laporan['total_debet'],
            'total_kredit' => $laporan['total_kredit'],
            'tglawal' => $tglawal,
            'tglakhir' => $tglakhir,
        ]);
        $pdf->writeHTML($html);

        // Output PDF
        $this->response->setHeader('Content-Type', 'application/pdf');
        $pdf->Output('Laporan_Jurnal_Umum.pdf', 'I');
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
