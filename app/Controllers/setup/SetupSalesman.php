<?php

namespace App\Controllers\setup;

use App\Models\setup\ModelSetupsalesman;
use App\Models\transaksi\ModelPiutang;
use App\Models\transaksi\ModelRiwayatPiutang;
use App\Models\setup_persediaan\ModelLokasi;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class SetupSalesman extends ResourceController
{
    protected $salesmanModel, $hutangPiutangModel, $lokasiModel, $db, $piutangModel, $riwayatPiutangModel;
    // INISIALISASI OBJECT DATA
    function __construct()
    {
        $this->salesmanModel = new ModelSetupsalesman();
        $this->riwayatPiutangModel = new ModelRiwayatPiutang();
        $this->piutangModel = new ModelPiutang();
        $this->lokasiModel = new ModelLokasi();
        $this->db = \Config\Database::connect();
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
        $data['dtsetupsalesman'] = $this->salesmanModel->getAllSalesman();
        return view('setup/salesman/index', $data);
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
        $data['dtsalesman'] = $this->salesmanModel->getSalesmanById($id);
        // Cek jika data tidak ditemukan
        if (!$data['dtsalesman']) {
            return $this->response->setJSON(['error' => 'Data tidak ditemukan']);
        }

        if ($this->request->isAJAX()) {
            $msg = [
                'data' => view('setup/salesman/piutang/detail', $data)
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
        // Menghitung jumlah data yang telah ada untuk kode
        $count = $this->salesmanModel->countAllResults();

        // Memformat kode
        $kodeSetupsalesman = str_pad($count + 1, 3, '0', STR_PAD_LEFT);
        $data['kode_setupsalesman'] = $kodeSetupsalesman;
        $data['dtlokasi'] = $this->lokasiModel->findAll();
        return view('setup/salesman/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $namaSetupsalesman = $this->request->getVar('nama_setupsalesman');
        $kodeSalesman = $this->request->getVar('kode_setupsalesman');

        // Ekstrak huruf pertama dari nama
        $initial = strtoupper(substr($namaSetupsalesman, 0, 1));

        // Buat kode otomatis
        $kodeSetupsalesman = $kodeSalesman . '-' . $initial;

        // Data untuk disimpan
        $data = [
            'kode_salesman' => $kodeSetupsalesman,
            'nama_salesman' => $namaSetupsalesman,
            'id_lokasi' => $this->request->getVar('id_lokasi'),
        ];

        if ($this->salesmanModel->insert($data)) {
            return redirect()->to(site_url('setup/salesman'))->with('Sukses', 'Data Berhasil Disimpan');
        }
        return redirect()->to(site_url('setup/salesman/new'))->with('error', 'Data Gagal Disimpan');
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
        $dtsetupsalesman = $this->salesmanModel->find($id);

        // Cek jika data tidak ditemukan
        if (!$dtsetupsalesman) {
            return redirect()->to(site_url('setup/salesman'))->with('error', 'Data tidak ditemukan');
        }


        // Lanjutkan jika semua pengecekan berhasil
        $data['dtsalesman'] = $dtsetupsalesman;
        $data['dtlokasi'] = $this->lokasiModel->findAll();
        return view('setup/salesman/edit', $data);
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

        // Data untuk disimpan
        $data = [
            'kode_salesman' => $this->request->getVar('kode_salesman'),
            'nama_salesman' => $this->request->getVar('nama_salesman'),
            'id_lokasi' => $this->request->getVar('id_lokasi'),
        ];
        // Update data berdasarkan ID
        $this->salesmanModel->update($id, $data);

        return redirect()->to(site_url('setup/salesman'))->with('Sukses', 'Data Berhasil Disimpan');
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
        $this->db->table('setupsalesman1')->where(['id_salesman' => $id])->delete();
        return redirect()->to(site_url('setup/salesman'))->with('Sukses', 'Data Berhasil Dihapus');
    }

    public function getPiutang($id = null)
    {
        $data['dtpiutang'] = $this->piutangModel->getRiwayatPiutangById($id, 'salesman');
        if ($this->request->isAJAX()) {
            $msg = [
                'data' => view('setup/salesman/piutang/data_piutang', $data)
            ];
            return $this->response->setJSON($msg);
        }
    }

    public function addPiutang($id = null)
    {
        if ($this->request->isAJAX()) {
            $this->db->transBegin();

            try {
                $nominalPiutang = $this->request->getVar('saldo');

                $data = [
                    'id_relasional' => $id,
                    'relasi_tipe' => 'salesman',
                    'sumber' => 'manual',
                    'nota' => $this->request->getVar('nota'),
                    'ref_transaksi' => null,
                    'tanggal' => $this->request->getVar('tanggal'),
                    'tanggal_jt' => $this->request->getVar('tanggal_jt'),
                    'total_piutang' => $nominalPiutang,
                    'status' => 'open'
                ];

                $id_piutang = $this->piutangModel->insert($data);
                // Masukkan data ke dalam tabel
                if ($id_piutang) {
                    $updatedSaldo = $this->piutangModel->getSaldoPiutangById($id, 'salesman') + (float)$nominalPiutang;

                    // Simpan data riwayat transaksi piutang
                    $riwayatData = [
                        'id_piutang' => $id_piutang,
                        'id_pelaku' => $id,
                        'jenis_pelaku' => 'salesman',
                        'tanggal' => $this->request->getVar('tanggal'),
                        'jenis_transaksi' => 'manual',
                        'nota' => $this->request->getVar('nota'),
                        'debit' => 0,
                        'kredit' => $nominalPiutang,
                        'deskripsi' => 'Penambahan piutang manual',
                    ];

                    $this->db->table('riwayat_transaksi_piutang')->insert($riwayatData);

                    $this->db->transCommit();

                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Data berhasil disimpan!',
                        'id' => $id,
                        'updatedSaldo' => $updatedSaldo,
                    ]);
                } else {
                    return $this->response->setJSON(['success' => false, 'message' => 'Data gagal disimpan!']);
                }
            } catch (\Exception $e) {
                // Rollback transaksi jika terjadi kesalahan
                $this->db->transRollback();
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Terjadi kesalahan, data Gagal Disimpan: ' . $e->getMessage()
                ]);
            }
        }
    }

    public function editPiutang($id_piutang = null)
    {
        helper('form');

        $data['data'] = $this->piutangModel->find($id_piutang);

        if ($this->request->isAJAX()) {
            $msg = [
                'data' => view('setup/salesman/piutang/edit_piutang', $data)
            ];

            return $this->response->setJSON($msg);
        }
    }

    // public function updatePiutang($id_piutang = null)
    // {
    //     if ($this->request->isAJAX()) {
    //         $this->db->transBegin();

    //         try {
    //             $newAmount = (float)$this->request->getVar('saldo');

    //             $data = [
    //                 'tanggal' => $this->request->getVar('tanggal'),
    //                 'nota' => $this->request->getVar('nota'),
    //                 'tgl_jatuhtempo' => $this->request->getVar('tgl_jatuhtempo'),
    //                 'saldo' => $newAmount,
    //             ];

    //             // Update data berdasarkan ID
    //             if ($this->piutangModel->update($id_piutang, $data)) {
    //                 // Ambil data salesman yang bersangkutan


    //                 // Hitung perbedaan saldo dan update
    //                 $saldoDifference = $newAmount - $originalAmount;
    //                 $updatedSaldo = (float)$salesman->saldo + $saldoDifference;

    //                 // Update saldo salesman
    //                 $this->salesmanModel->update($salesmanId, ['saldo' => $updatedSaldo]);
    //                 // Commit transaksi jika semua berhasil
    //                 $this->db->transCommit();

    //                 return $this->response->setJSON([
    //                     'success' => true,
    //                     'message' => 'Data berhasil diupdate!',
    //                     'id' => $salesmanId,
    //                     'updatedSaldo' => $updatedSaldo,
    //                 ]);
    //             } else {
    //                 throw new \Exception('Data gagal diupdate!');
    //             }
    //         } catch (\Exception $e) {
    //             $this->db->transRollback();
    //             return $this->response->setJSON([
    //                 'success' => false,
    //                 'message' => 'Data gagal diupdate: ' . $e->getMessage()
    //             ]);
    //         }
    //     }
    // }

    public function deletePiutang($id_hutang_piutang = null)
    {
        if ($this->request->isAJAX()) {
            $this->db->transBegin();

            try {
                // Ambil data piutang sebelum dihapus
                $piutang = $this->hutangPiutangModel->find($id_hutang_piutang);
                $jumlahPiutang = (float)$piutang->saldo;
                $salesmanId = $piutang->relasi_id;

                // Hapus data piutang
                if ($this->hutangPiutangModel->delete($id_hutang_piutang)) {
                    // Ambil data salesman
                    $salesman = $this->salesmanModel->find($salesmanId);

                    if (!$salesman) {
                        throw new \Exception('Salesman tidak ditemukan');
                    }

                    // Hitung saldo baru
                    $updatedSaldo = (float)$salesman->saldo - $jumlahPiutang;
                    $this->salesmanModel->update($salesmanId, ['saldo' => $updatedSaldo]);

                    $this->db->transCommit();
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Data berhasil dihapus!',
                        'updatedSaldo' => $updatedSaldo,
                    ]);
                } else {
                    throw new \Exception('Data gagal diupdate!');
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
