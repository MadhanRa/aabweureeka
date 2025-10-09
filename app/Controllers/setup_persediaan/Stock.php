<?php

namespace App\Controllers\setup_persediaan;

use App\Models\setup_persediaan\ModelGroup;
use App\Models\setup_persediaan\ModelStock;
use App\Models\setup_persediaan\ModelStockGudang;
use App\Models\setup_persediaan\ModelKelompok;
use App\Models\setup_persediaan\ModelSatuan;
use App\Models\setup_persediaan\ModelLokasi;
use App\Models\setup\ModelSetupsupplier;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Stock extends ResourceController
{
    protected $objStock, $objLokasi, $db, $objKelompok, $objGroup, $objSetupsupplier, $objSatuan, $objStockGudang;
    //  INISIALISASI OBJECT DATA
    function __construct()
    {
        $this->objSatuan = new ModelSatuan();
        $this->objStock = new ModelStock();
        $this->objLokasi = new ModelLokasi();
        $this->objStockGudang = new ModelStockGudang();
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
        if ($this->request->isAJAX()) {
            $data['dtstock'] = $this->objStock->getStockWithRelationsById($id);
            $dataStockGudang = $this->objStockGudang->where(['id_stock' => $id])->findAll();
            $data['dtlokasi'] = $this->objLokasi->findAll();

            // Buat array stok gudang terindeks berdasarkan id_lokasi
            $stockGudangByLokasi = [];
            if (!empty($dataStockGudang)) {
                foreach ($dataStockGudang as $stockGudang) {
                    $stockGudangByLokasi[$stockGudang->id_lokasi] = $stockGudang;
                }
            }

            // Tambahkan data stok ke setiap lokasi
            foreach ($data['dtlokasi'] as $lokasi) {
                if (isset($stockGudangByLokasi[$lokasi->id_lokasi])) {
                    $lokasi->qty1 = $stockGudangByLokasi[$lokasi->id_lokasi]->qty1;
                    $lokasi->qty2 = $stockGudangByLokasi[$lokasi->id_lokasi]->qty2;
                    $lokasi->jml_harga = $stockGudangByLokasi[$lokasi->id_lokasi]->jml_harga;
                } else {
                    $lokasi->qty1 = 0;
                    $lokasi->qty2 = 0;
                    $lokasi->jml_harga = 0;
                }
            }

            $msg = [
                'data' => view('setup_persediaan/stock/detail', $data)
            ];

            return $this->response->setJSON($msg);
        } else {
            return $this->response->setStatusCode(404)->setJSON(['message' => 'Halaman Tidak Ditemukan']);
        }
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

    public function lookupStock()
    {
        $param['draw'] = isset($_REQUEST['draw']) ? $_REQUEST['draw'] : '';
        $param['start'] = isset($_REQUEST['start']) ? (int)$_REQUEST['start'] : 0;
        $param['length'] = isset($_REQUEST['length']) ? (int)$_REQUEST['length'] : 10;
        $param['search_value'] = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value'] : '';
        $param['supplier_id'] = isset($_REQUEST['supplier_id']) ? $_REQUEST['supplier_id'] : null;

        $results = $this->objStock->searchAndDisplay(
            $param['search_value'],
            $param['start'],
            $param['length'],
            $param['supplier_id']
        );
        $total_count = $this->objStock->searchAndDisplay(
            $param['search_value'],
            null,
            null,
            $param['supplier_id']
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

    function pilihItem($id_stock)
    {
        if ($this->request->isAJAX()) {
            $data = $this->objStock->getStockById($id_stock);
            $msg = [
                'sukses' => true,
                'data' => $data
            ];
            return $this->response->setJSON($msg);
        }
    }
}
