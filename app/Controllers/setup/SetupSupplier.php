<?php

namespace App\Controllers\setup;

use App\Models\setup\ModelSetupsupplier;
use App\Models\setup\ModelHutangPiutang;
use App\Models\transaksi\ModelHutang;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class SetupSupplier extends ResourceController
{
    protected $supplierModel;
    protected $hutangPiutangModel;
    protected $hutangModel;
    protected $db;
    function __construct()
    {
        $this->supplierModel = new ModelSetupsupplier();
        $this->hutangPiutangModel = new ModelHutangPiutang();
        $this->hutangModel = new ModelHutang();
        $this->db = \Config\Database::connect();
    }
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $data['dtsetupsupplier'] = $this->supplierModel->getAllSupplier();
        return view('setup/supplier/index', $data);
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
        // Ambil data berdasarkan ID
        $data['data'] = $this->supplierModel->getSupplierById($id);

        if (!$data) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        if ($this->request->isAJAX()) {
            $msg = [
                'success' => true,
                'data' => view('setup/supplier/hutang/detail', $data),
            ];
            return $this->response->setJSON($msg);
        }
    }

    /**
     * Return a new resource object, with default properties.
     *
     * @return ResponseInterface
     */
    public function new()
    {
        // Buat supplier kode
        $count = $this->supplierModel->countAllResults();
        $data['kode_supplier'] = sprintf('%03d', $count + 1);
        return view('setup/supplier/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $namaSalesman = $this->request->getVar('nama');
        $kodeSalesman = $this->request->getVar('kode');

        // Ekstrak huruf pertama dari nama
        $initial = strtoupper(substr($namaSalesman, 0, 1));

        // Buat kode otomatis
        $kodeSetupSalesman = $kodeSalesman . '-' . $initial;


        $data = $this->request->getPost([
            'nama',
            'alamat',
            'telepon',
            'contact_person',
            'npwp',
            'tipe',
        ]);

        $data['kode'] = $kodeSetupSalesman;
        $data['saldo'] = '0';

        if ($this->supplierModel->insert($data)) {
            return redirect()->to(site_url('setup/supplier'))->with('Sukses', 'Data Berhasil Disimpan');
        }
        return redirect()->to(site_url('setup/supplier/new'))->with('error', 'Data Gagal Disimpan');
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
        // Ambil data berdasarkan ID
        $dtsetupsupplier = $this->supplierModel->find($id);

        // Cek jika data tidak ditemukan
        if (!$dtsetupsupplier) {
            return redirect()->to(site_url('setup/supplier'))->with('error', 'Data tidak ditemukan');
        }


        // Lanjutkan jika semua pengecekan berhasil
        $data['dtsetupsupplier'] = $dtsetupsupplier;

        return view('setup/supplier/edit', $data);
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
        $data = $this->request->getPost();
        // Update data berdasarkan ID
        $this->supplierModel->update($id, $data);

        return redirect()->to(site_url('setup/supplier'))->with('Sukses', 'Data Berhasil Diupdate');
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
        $this->db->table('setupsupplier1')->where(['id_setupsupplier' => $id])->delete();
        return redirect()->to(site_url('setup/supplier'))->with('Sukses', 'Data Berhasil Dihapus');
    }

    public function getHutang($id = null)
    {
        $data['dthutang'] = $this->hutangModel->getHutangBySupplier($id);
        if ($this->request->isAJAX()) {
            $msg = [
                'data' => view('setup/supplier/hutang/data_hutang', $data)
            ];
            return $this->response->setJSON($msg);
        }
    }

    public function newHutang($id = null)
    {
        helper('form');

        if ($this->request->isAJAX()) {
            $data['id'] = $id;
            $msg = [
                'data' => view('setup/supplier/hutang/new_hutang', $data)
            ];
            return $this->response->setJSON($msg);
        }
    }

    public function createHutang($id = null)
    {
        if ($this->request->isAJAX()) {
            $this->db->transBegin();

            try {

                $data = [
                    'id_setupsupplier' => $id,
                    'nota' => $this->request->getVar('nota'),
                    'sumber' => 'manual',
                    'tanggal' => $this->request->getVar('tanggal'),
                    'tgl_jatuhtempo' => $this->request->getVar('tanggal_jt'),
                    'nominal' => $this->request->getVar('saldo'),
                    'saldo' => $this->request->getVar('saldo'),
                ];
                $idHutang = $this->hutangModel->insert($data);
                // Masukkan data ke dalam tabel
                if ($idHutang) {

                    // Simpan data riwayat transaksi hutang
                    $riwayatData = [
                        'id_hutang' => $idHutang,
                        'tanggal' => $this->request->getVar('tanggal'),
                        'jenis_transaksi' => $this->request->getVar('manual'),
                        'nota' => $this->request->getVar('nota'),
                        'nominal' => $this->request->getVar('saldo'),
                        'saldo_setelah' => $this->request->getVar('saldo'),
                        'deskripsi' => 'Penambahan hutang manual',
                    ];

                    $this->db->table('riwayat_transaksi_hutang')->insert($riwayatData);

                    $updatedSaldo = $this->hutangModel->getSaldoHutangBySupplier($id);

                    $this->db->transCommit();

                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Data berhasil disimpan!',
                        'id' => $id,
                        'updatedSaldo' => $updatedSaldo,
                    ]);
                } else {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Data gagal disimpan!',
                    ]);
                }
            } catch (\Exception $e) {
                // Rollback transaksi jika terjadi kesalahan
                $this->db->transRollback();
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Terjadi kesalahan, data Gagal Disimpan: ' . $e->getMessage(),
                ]);
            }
        }
    }

    public function editHutang($hutang_id = null)
    {
        helper('form');

        if ($this->request->isAJAX()) {
            $data['data'] = $this->hutangModel->find($hutang_id);
            $msg = [
                'data' => view('setup/supplier/hutang/edit_hutang', $data)
            ];
            return $this->response->setJSON($msg);
        }
    }

    public function updateHutang($id_hutang = null)
    {
        if ($this->request->isAJAX()) {
            $this->db->transBegin();

            try {
                // Ambil data asli
                $originalHutang = $this->hutangModel->find($id_hutang);
                $originalAmount = (float)$originalHutang->saldo;
                $newAmount = (float)$this->request->getVar('saldo');
                $supplierId = $originalHutang->id_setupsupplier;

                $data = [
                    'tanggal' => $this->request->getVar('tanggal'),
                    'nota' => $this->request->getVar('nota'),
                    'tgl_jatuhtempo' => $this->request->getVar('tanggal_jt'),
                    'nominal' => $newAmount,
                    'saldo' => $newAmount,
                ];

                // Update data berdasarkan ID
                if ($this->hutangModel->update($id_hutang, $data)) {
                    // Simpan data riwayat transaksi hutang

                    $id_riwayat_hutang = $this->db->table('riwayat_transaksi_hutang')
                        ->where('id_hutang', $id_hutang)
                        ->get()
                        ->getRow()
                        ->id_riwayat_hutang;

                    $riwayatData = [
                        'id_hutang' => $id_hutang,
                        'tanggal' => $this->request->getVar('tanggal'),
                        'jenis_transaksi' => 'update',
                        'nota' => $this->request->getVar('nota'),
                        'nominal' => $newAmount - $originalAmount, // Selisih perubahan
                        'saldo_setelah' => $newAmount,
                        'deskripsi' => 'Update hutang manual',
                    ];
                    $this->db->table('riwayat_transaksi_hutang')->update($id_riwayat_hutang, $riwayatData);

                    $updatedSaldo = $this->hutangModel->getSaldoHutangBySupplier($supplierId);

                    // Commit transaksi jika semua berhasil
                    $this->db->transCommit();

                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Data berhasil diupdate!',
                        'id' => $supplierId,
                        'updatedSaldo' => $updatedSaldo,
                    ]);
                } else {
                    throw new \Exception('Data gagal diupdate!');
                }
            } catch (\Exception $e) {
                $this->db->transRollback();
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Data gagal diupdate: ' . $e->getMessage()
                ]);
            }
        }
    }

    public function deleteHutang($id_hutang = null)
    {
        if ($this->request->isAJAX()) {
            $this->db->transBegin();

            try {
                // hapus riwayat transaksi hutang terkait
                if ($this->db->table('riwayat_transaksi_hutang')->where('id_hutang', $id_hutang)->delete()) {

                    // Ambil ID supplier sebelum menghapus hutang
                    $hutang = $this->hutangModel->find($id_hutang);
                    $supplierId = $hutang->id_setupsupplier;

                    // Hapus data hutang
                    $this->hutangModel->delete($id_hutang);

                    $updatedSaldo = $this->hutangModel->getSaldoHutangBySupplier($supplierId);


                    $this->db->transCommit();
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Data berhasil dihapus!',
                        'id' => $supplierId,
                        'updatedSaldo' => $updatedSaldo,
                    ]);
                } else {
                    throw new \Exception('Data gagal dihapus!');
                }
            } catch (\Exception $e) {
                $this->db->transRollback();
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Data gagal dihapus: ' . $e->getMessage()
                ]);
            }
        }
    }
}
