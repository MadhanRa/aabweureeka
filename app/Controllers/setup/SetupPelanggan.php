<?php

namespace App\Controllers\setup;

use App\Models\setup\ModelSetuppelanggan;
use App\Models\setup\ModelHutangPiutang;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class SetupPelanggan extends ResourceController
{
    protected $pelangganModel;
    protected $hutangPiutangModel;
    protected $db;
    // INISIALISASI OBJECT DATA
    function __construct()
    {
        $this->pelangganModel = new ModelSetuppelanggan();
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
        $data['dtsetuppelanggan'] = $this->pelangganModel->getAllPelanggan();
        return view('setup/pelanggan/index', $data);
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
        $data['data'] = $this->pelangganModel->getPelangganById($id);

        // Cek jika data tidak ditemukan
        if (!$data) {
            return $this->response->setJSON([
                'error' => 'Data tidak ditemukan'
            ]);
        }

        if ($this->request->isAJAX()) {
            $msg = [
                'success' => true,
                'data' => view('setup/pelanggan/piutang/detail', $data),
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
        // Buat kode pelanggan baru
        $jumlahData = $this->pelangganModel->countAllResults();
        $kode_pelanggan = str_pad($jumlahData + 1, 3, '0', STR_PAD_LEFT);
        $data['kode_pelanggan'] = $kode_pelanggan;
        return view('setup/pelanggan/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $nama_pelanggan = $this->request->getVar('nama_pelanggan');
        $kode_pelanggan = $this->request->getVar('kode_pelanggan');

        // Ambil huruf pertama dari nama pelanggan
        $kode_prefix = strtoupper(substr($nama_pelanggan, 0, 1));

        // Format kode pelanggan baru (e.g., I00001)
        $kode_pelanggan = $kode_pelanggan . '-' . $kode_prefix;

        $data = [
            'kode_pelanggan' => $kode_pelanggan,
            'nama_pelanggan' => $nama_pelanggan,
            'alamat_pelanggan' => $this->request->getVar('alamat_pelanggan'),
            'kota_pelanggan' => $this->request->getVar('kota_pelanggan'),
            'telp_pelanggan' => $this->request->getVar('telp_pelanggan'),
            'plafond' => $this->request->getVar('plafond'),
            'npwp' => $this->request->getVar('npwp'),
            'class_pelanggan' => $this->request->getVar('class_pelanggan'),
            'tipe' => $this->request->getVar('tipe'),
            'saldo' => '0',
        ];
        if ($this->pelangganModel->insert($data)) {
            return redirect()->to(site_url('setup/pelanggan'))->with('Sukses', 'Data Berhasil Disimpan');
        }
        return redirect()->to(site_url('setup/pelanggan/new'))->with('error', 'Data Gagal Disimpan');
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
        $dtsetuppelanggan = $this->pelangganModel->find($id);

        // Cek jika data tidak ditemukan
        if (!$dtsetuppelanggan) {
            return redirect()->to(site_url('setup/pelanggan'))->with('error', 'Data tidak ditemukan');
        }


        // Lanjutkan jika semua pengecekan berhasil
        $data['dtsetuppelanggan'] = $dtsetuppelanggan;

        return view('setup/pelanggan/edit', $data);
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

        $data = $this->request->getPost([
            'kode_pelanggan',
            'nama_pelanggan',
            'alamat_pelanggan',
            'kota_pelanggan',
            'telp_pelanggan',
            'plafond',
            'npwp',
            'class_pelanggan',
            'tipe',
        ]);
        // Update data berdasarkan ID
        $this->pelangganModel->update($id, $data);

        return redirect()->to(site_url('setup/pelanggan'))->with('Sukses', 'Data Berhasil Disimpan');
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
        $this->db->table('setuppelanggan1')->where(['id_pelanggan' => $id])->delete();
        return redirect()->to(site_url('setup/pelanggan'))->with('Sukses', 'Data Berhasil Dihapus');
    }

    public function getPiutang($id = null)
    {
        $data['dtpiutang'] = $this->hutangPiutangModel->getHutangPiutang($id, 'pelanggan');
        if ($this->request->isAJAX()) {
            $msg = [
                'data' => view('setup/pelanggan/data_piutang', $data)
            ];
            return $this->response->setJSON($msg);
        }
    }

    public function newPiutang($id = null)
    {
        helper('form');

        if ($this->request->isAJAX()) {
            $data['id'] = $id;
            $msg = [
                'data' => view('setup/pelanggan/piutang/new_piutang', $data)
            ];
            return $this->response->setJSON($msg);
        }
    }


    public function createPiutang($id = null)
    {
        if ($this->request->isAJAX()) {
            $this->db->transBegin();

            try {
                $saldoPiutang = $this->request->getVar('saldo');

                $data = [
                    'tanggal' => $this->request->getVar('tanggal'),
                    'nota' => $this->request->getVar('nota'),
                    'tanggal_jt' => $this->request->getVar('tanggal_jt'),
                    'saldo' => $saldoPiutang,
                    'relasi_id' => $id,
                    'relasi_tipe' => 'pelanggan',
                    'jenis' => 'piutang'
                ];

                // Masukkan data ke dalam tabel
                if ($this->hutangPiutangModel->insert($data)) {
                    // Ambil data pelanggan yang bersangkutan
                    $pelanggan = $this->pelangganModel->find($id);

                    if (!$pelanggan) {
                        throw new \Exception('Pelanggan tidak ditemukan');
                    }

                    // Hitung saldo baru
                    $updatedSaldo = (float)$pelanggan->saldo + (float)$saldoPiutang;
                    // Update saldo pelanggan
                    $this->pelangganModel->update($id, ['saldo' => $updatedSaldo]);
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

    public function editPiutang($piutang_id = null)
    {
        helper('form');

        if ($this->request->isAJAX()) {
            $data['data'] = $this->hutangPiutangModel->find($piutang_id);
            $msg = [
                'data' => view('setup/pelanggan/edit_piutang', $data)
            ];
            return $this->response->setJSON($msg);
        }
    }

    public function updatePiutang($id_hutang_piutang = null)
    {
        if ($this->request->isAJAX()) {
            $this->db->transBegin();

            try {
                // Ambil data asli
                $originalPiutang = $this->hutangPiutangModel->find($id_hutang_piutang);
                $originalAmount = (float)$originalPiutang->saldo;
                $newAmount = (float)$this->request->getVar('saldo');
                $pelangganId = $originalPiutang->relasi_id;

                $data = [
                    'tanggal' => $this->request->getVar('tanggal'),
                    'nota' => $this->request->getVar('nota'),
                    'tanggal_jt' => $this->request->getVar('tanggal_jt'),
                    'saldo' => $newAmount,
                ];

                // Update data berdasarkan ID
                if ($this->hutangPiutangModel->update($id_hutang_piutang, $data)) {
                    // Ambil data pelanggan yang bersangkutan
                    $pelanggan = $this->pelangganModel->find($pelangganId);

                    if (!$pelanggan) {
                        throw new \Exception('pelanggan tidak ditemukan');
                    }

                    // Hitung perbedaan saldo dan update
                    $saldoDifference = $newAmount - $originalAmount;
                    $updatedSaldo = (float)$pelanggan->saldo + $saldoDifference;

                    // Update saldo pelanggan
                    $this->pelangganModel->update($pelangganId, ['saldo' => $updatedSaldo]);
                    // Commit transaksi jika semua berhasil
                    $this->db->transCommit();

                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Data berhasil diupdate!',
                        'id' => $pelangganId,
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

    public function deletePiutang($id_hutang_piutang = null)
    {
        if ($this->request->isAJAX()) {
            $this->db->transBegin();

            try {
                // Ambil data piutang sebelum dihapus
                $piutang = $this->hutangPiutangModel->find($id_hutang_piutang);
                $jumlahPiutang = (float)$piutang->saldo;
                $pelangganId = $piutang->relasi_id;

                // Hapus data piutang
                if ($this->hutangPiutangModel->delete($id_hutang_piutang)) {
                    // Ambil data pelanggan
                    $pelanggan = $this->pelangganModel->find($pelangganId);

                    if (!$pelanggan) {
                        throw new \Exception('Pelanggan tidak ditemukan');
                    }

                    // Hitung saldo baru
                    $updatedSaldo = (float)$pelanggan->saldo - $jumlahPiutang;
                    $this->pelangganModel->update($pelangganId, ['saldo' => $updatedSaldo]);

                    $this->db->transCommit();
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Data berhasil dihapus!',
                        'id' => $pelangganId,
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
