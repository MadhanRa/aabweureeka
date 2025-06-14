<?php

namespace App\Controllers;

use App\Models\transaksi\ClosedPeriodsModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Accounting extends ResourceController
{
    protected $db;

    protected $closedPeriodsModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->closedPeriodsModel = new ClosedPeriodsModel();
    }

    public function index()
    {
        return view('accounting/closeBook');
    }

    // Method untuk proses "Tutup Buku"
    public function closePeriod()
    {
        if ($this->request->getMethod() === 'post') {
            // $startDate = $this->request->getPost('start_date');
            // $endDate = $this->request->getPost('end_date');

            // Validasi dan pemeriksaan lainnya...

            $data = [
                'periode_start' => '2024-01-01',
                'periode_end'   => '2024-01-31',
                'is_closed'     => 1,
                'created_at'    => date('Y-m-d H:i:s'), // Atau gunakan $this->request->getVar('created_at') jika ada input
            ];

            $this->db->table('closed_periods')->insert($data);

            return redirect()->to('/accounting/closeBook')->with('success', 'Periode berhasil ditutup');
        }

        return view('accounting/closeBook');
    }



    public function isPeriodClosed($date)
    {
        $query = $this->db->table('closed_periods')
            ->where('periode_start <=', $date)
            ->where('periode_end >=', $date)
            ->where('is_closed', 1)
            ->get();

        return $query->getNumRows() > 0; // Kembali true jika periode ditutup
    }

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */

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
