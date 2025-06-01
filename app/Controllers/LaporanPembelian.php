<?php

namespace App\Controllers;

use TCPDF;
use App\Models\setup_persediaan\ModelLokasi;
use App\Models\setup_persediaan\ModelSatuan;
use App\Models\transaksi\pembelian\ModelPembelian;
use App\Models\setup\ModelSetupbank;
use App\Models\setup\ModelSetupsupplier;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class LaporanPembelian extends ResourceController
{

    protected $db, $objLokasi, $objSatuan, $objPembelian, $objSetupbank, $objSetupsupplier;
    function __construct()
    {
        $this->objLokasi = new ModelLokasi();
        $this->objSatuan = new ModelSatuan();
        $this->objSetupsupplier = new ModelSetupsupplier();
        $this->objPembelian = new ModelPembelian();
        $this->objSetupbank = new ModelSetupbank();
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
        $supplier = $this->request->getVar('supplier') ? $this->request->getVar('supplier') : '';

        // Panggil model untuk mendapatkan data laporan
        $dtpembelian = $this->objPembelian->get_laporan($tglawal, $tglakhir, $supplier);

        $sum_subtotal = 0;
        $sum_grandtotal = 0;
        $sum_dpp = 0;
        $sum_disc_cash = 0;
        $sum_ppn = 0;
        foreach ($dtpembelian as $row) {
            $sub_total = floatval($row->sub_total);
            if ($row->disc_cash > 0) {
                $disc = $sub_total * (floatval($row->disc_cash) / 100);
            } else {
                $disc = floatval($row->disc_cash_rp);
            }
            $dpp = $sub_total - $disc;
            if ($row->ppn_option == 'exclude') {
                $ppn = $dpp * (floatval($row->ppn) / 100);
            } else {
                $ppn = 0;
            }
            $total = $dpp + $ppn;

            $row->row_disc_cash = $disc;
            $row->row_dpp = $dpp;
            $row->row_ppn = $ppn;
            $row->row_grand_total =  $total;

            $sum_dpp += $dpp;
            $sum_subtotal += $sub_total;
            $sum_grandtotal += $total;
            $sum_disc_cash += $disc;
            $sum_ppn += $ppn;
        }


        // $data['dtpembelian'] = $rowdata;
        $data = [
            'dtpembelian'    => $dtpembelian,
            'dtsupplier'    => $this->objSetupsupplier->getAll(),
            'subtotal'       => $sum_subtotal,
            'disccash'       => $sum_disc_cash,
            'dpp'       => $sum_dpp,
            'ppn'       => $sum_ppn,
            'grandtotal'     => $sum_grandtotal,
            'tglawal'        => $tglawal,
            'tglakhir'       => $tglakhir,
            'supplier'       => $supplier,
        ];
        return view('laporanpembelian/index', $data);
    }

    public function printPDF($id = null)
    {
        // Ambil data filter dari request
        $tglawal = $this->request->getVar('tglawal') ? $this->request->getVar('tglawal') : '';
        $tglakhir = $this->request->getVar('tglakhir') ? $this->request->getVar('tglakhir') : '';
        $supplier = $this->request->getVar('supplier') ? $this->request->getVar('supplier') : '';

        // Dapatkan data pembelian berdasarkan filter
        $dtpembelian = $this->objPembelian->get_laporan($tglawal, $tglakhir, $supplier);

        // Ambil nama supplier jika filter supplier diterapkan
        $supplierName = 'Semua Supplier';
        if (!empty($supplierId)) {
            $supplierData = $this->db->table('setupsupplier1')
                ->select('nama')
                ->where('id_setupsupplier', $supplierId)
                ->get()
                ->getRow();
            $supplierName = $supplierData ? $supplierData->nama : 'Supplier Tidak Ditemukan';
        }

        // Hitung subtotal dan grand total
        $subtotal = 0;
        $grandtotal = 0;
        if ($dtpembelian) {
            foreach ($dtpembelian as $row) {
                $subtotal += $row->jml_harga; // Total jumlah harga
                $grandtotal += $row->total;  // Total setelah diskon
            }
        }

        $data = [
            'dtpembelian' => $dtpembelian,
            'dtlokasi'    => $this->objLokasi->getAll(),
            'dtsatuan'    => $this->objSatuan->getAll(),
            'dtsetupsupplier' => $this->objSetupsupplier->getAll(),
            'dtsetupbank' => $this->objSetupbank->getAll(),
            'tglawal'     => $tglawal,
            'tglakhir'    => $tglakhir,
            'supplier'    => $supplierName,
            'subtotal'    => $subtotal,
            'grandtotal'  => $grandtotal,
        ];
        // Debugging: Tampilkan konten HTML sebelum PDF
        $html = view('laporanpembelian/printPDF', $data);
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
        $pdf->Output('laporan_pembelian.pdf', 'D');
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
