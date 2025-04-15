<?php

namespace App\Controllers\setup;

use App\Models\setup\ModelSetupsupplier;
use App\Models\setup\ModelHutangPiutang;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class SetupSupplier extends ResourceController
{
    protected $supplierModel;
    protected $hutangPiutangModel;
    protected $db;
    function __construct()
    {
        $this->supplierModel = new ModelSetupsupplier();
        $this->hutangPiutangModel = new ModelHutangPiutang();
        $this->db = \Config\Database::connect();
    }
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $data['dtsetupsupplier'] = $this->supplierModel->findAll();
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
        $data['data'] = $this->supplierModel->find($id);

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
        $data['dthutang'] = $this->hutangPiutangModel->getHutangPiutang($id, 'supplier');
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
                $saldoHutang = $this->request->getVar('saldo');

                $data = [
                    'tanggal' => $this->request->getVar('tanggal'),
                    'nota' => $this->request->getVar('nota'),
                    'tanggal_jt' => $this->request->getVar('tanggal_jt'),
                    'saldo' => $saldoHutang,
                    'relasi_id' => $id,
                    'relasi_tipe' => 'supplier',
                    'jenis' => 'hutang'
                ];

                // Masukkan data ke dalam tabel
                if ($this->hutangPiutangModel->insert($data)) {
                    // Ambil data supplier yang bersangkutan
                    $supplier = $this->supplierModel->find($id);

                    if (!$supplier) {
                        throw new \Exception('Supplier tidak ditemukan');
                    }

                    // Hitung saldo baru
                    $updatedSaldo = (float)$supplier->saldo + (float)$saldoHutang;
                    // Update saldo supplier
                    $this->supplierModel->update($id, ['saldo' => $updatedSaldo]);
                    // Commit transaksi jika semua berhasil
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
            $data['data'] = $this->hutangPiutangModel->find($hutang_id);
            $msg = [
                'data' => view('setup/supplier/hutang/edit_hutang', $data)
            ];
            return $this->response->setJSON($msg);
        }
    }

    public function updateHutang($id_hutang_piutang = null)
    {
        if ($this->request->isAJAX()) {
            $this->db->transBegin();

            try {
                // Ambil data asli
                $originalHutang = $this->hutangPiutangModel->find($id_hutang_piutang);
                $originalAmount = (float)$originalHutang->saldo;
                $newAmount = (float)$this->request->getVar('saldo');
                $supplierId = $originalHutang->relasi_id;

                $data = [
                    'tanggal' => $this->request->getVar('tanggal'),
                    'nota' => $this->request->getVar('nota'),
                    'tanggal_jt' => $this->request->getVar('tanggal_jt'),
                    'saldo' => $newAmount,
                ];

                // Update data berdasarkan ID
                if ($this->hutangPiutangModel->update($id_hutang_piutang, $data)) {
                    // Ambil data Supplier yang bersangkutan
                    $supplier = $this->supplierModel->find($supplierId);

                    if (!$supplier) {
                        throw new \Exception('Supplier tidak ditemukan');
                    }

                    // Hitung perbedaan saldo dan update
                    $saldoDifference = $newAmount - $originalAmount;
                    $updatedSaldo = (float)$supplier->saldo + $saldoDifference;

                    // Update saldo Supplier
                    $this->supplierModel->update($supplierId, ['saldo' => $updatedSaldo]);
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

    public function deleteHutang($id_hutang_piutang = null)
    {
        if ($this->request->isAJAX()) {
            $this->db->transBegin();

            try {
                // Ambil data piutang sebelum dihapus
                $hutang = $this->hutangPiutangModel->find($id_hutang_piutang);
                $jumlahHutang = (float)$hutang->saldo;
                $supplierId = $hutang->relasi_id;

                // Hapus data piutang
                if ($this->hutangPiutangModel->delete($id_hutang_piutang)) {
                    // Ambil data Supplier
                    $supplier = $this->supplierModel->find($supplierId);

                    if (!$supplier) {
                        throw new \Exception('Supplier tidak ditemukan');
                    }

                    // Hitung saldo baru
                    $updatedSaldo = (float)$supplier->saldo - $jumlahHutang;
                    $this->supplierModel->update($supplierId, ['saldo' => $updatedSaldo]);

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
