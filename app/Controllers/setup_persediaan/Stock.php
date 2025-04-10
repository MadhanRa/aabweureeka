<?php

namespace App\Controllers\setup_persediaan;

use App\Models\setup_persediaan\ModelGroup;
use App\Models\setup_persediaan\ModelStock;
use App\Models\setup_persediaan\ModelKelompok;
use App\Models\setup\ModelSetupsupplier;
use App\Models\setup_persediaan\ModelSatuan;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Stock extends ResourceController
{
    protected $objStock, $objLokasi, $db, $objKelompok, $objGroup, $objSetupsupplier, $objSatuan;
    //  INISIALISASI OBJECT DATA
    function __construct()
    {
        $this->objSatuan = new ModelSatuan();
        $this->objStock = new ModelStock();
        $this->objKelompok = new ModelKelompok();
        $this->objGroup = new ModelGroup();
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
        $data['dtstock'] = $this->objStock->getStockWithRelations();
        // d($data);
        return view('setup_persediaan/stock/index', $data);
    }

    public function getStock()
    {
        if ($this->request->isAJAX()) {
            $data['dtstock'] = $this->objStock->getStockWithRelations();
            $msg = [
                'data' => view('setup_persediaan/stock/data', $data)
            ];

            return $this->response->setJSON($msg);
        }
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
        helper('form');

        $data['dtsatuan'] = $this->objSatuan->findAll();
        $data['dtgroup'] = $this->db->table('group1')->get()->getResult();
        $data['dtkelompok'] = $this->db->table('kelompok1')->get()->getResult();
        $data['dtsupplier'] = $this->db->table('setupsupplier1')->get()->getResult();

        if ($this->request->isAJAX()) {
            $msg = [
                'data' => view('setup_persediaan/stock/new', $data)
            ];

            echo json_encode($msg);
        }
        // return view('setup_persediaan/stock/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {

        if ($this->request->isAJAX()) {

            $validation =  \Config\Services::validation();
            $valid = $this->validate([
                'kode' => [
                    'label' => 'Kode',
                    'rules' => 'required|is_unique[stock1.kode]',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                        'is_unique' => '{field} sudah ada, coba yang lain.'
                    ]
                ],
            ]);

            if (!$valid) {
                $msg = [
                    'error' => [
                        'kode' => $validation->getError('kode'),
                    ]
                ];
            } else {
                $data = [
                    'id_stock' => $this->request->getVar('id_stock'),
                    'kode' => $this->request->getVar('kode'),
                    'nama_barang' => $this->request->getVar('nama_barang'),
                    'id_group' => $this->request->getVar('id_group'),
                    'id_kelompok' => $this->request->getVar('id_kelompok'),
                    'id_setupsupplier' => $this->request->getVar('id_setupsupplier'),
                    'id_satuan' => $this->request->getVar('id_satuan'),
                    'id_satuan2' => $this->request->getVar('id_satuan2'),
                    'conv_factor' => $this->request->getVar('conv_factor'),
                    'min_stock' => $this->request->getVar('min_stock'),
                ];
                $this->objStock->insert($data);

                $msg = [
                    'success' => 'Data Berhasil Disimpan'
                ];
            }
            return $this->response->setJSON($msg);
        }

        // return redirect()->to(site_url('setup_persediaan/stock'))->with('Sukses', 'Data Berhasil Disimpan');
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
        helper('form');
        if ($this->request->isAJAX()) {
            // Ambil data berdasarkan ID
            $data['stock'] = $this->objStock->find($id);

            $data['dtsatuan'] = $this->objSatuan->findAll();
            $data['dtgroup'] = $this->db->table('group1')->get()->getResult();
            $data['dtkelompok'] = $this->db->table('kelompok1')->get()->getResult();
            $data['dtsupplier'] = $this->db->table('setupsupplier1')->get()->getResult();

            if ($this->request->isAJAX()) {
                $msg = [
                    'data' => view('setup_persediaan/stock/edit', $data)
                ];

                return $this->response->setJSON($msg);
            }
        } else {
            return $this->response->setStatusCode(404)->setJSON(['message' => 'Halaman Tidak Ditemukan']);
        }
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
        if ($this->request->isAJAX()) {
            $data = $this->request->getPost();

            if ($this->objStock->update($id, $data)) {
                $msg = [
                    'success' => 'Data Berhasil Diubah'
                ];
            } else {
                $msg = [
                    'error' => 'Gagal Mengubah Data'
                ];
            }

            return $this->response->setJSON($msg);
        } else {
            return $this->response->setStatusCode(404)->setJSON(['message' => 'Halaman Tidak Ditemukan']);
        }
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
        if ($this->request->isAJAX()) {
            if ($this->objStock->delete($id)) {
                $msg = [
                    'success' => 'Data Berhasil Dihapus'
                ];
            } else {
                $msg = [
                    'error' => 'Gagal Menghapus Data'
                ];
            }
            return $this->response->setJSON($msg);
        } else {
            return $this->response->setStatusCode(404)->setJSON(['message' => 'Halaman Tidak Ditemukan']);
        }
    }
}
