<?php

namespace App\Controllers\transaksi;

use CodeIgniter\Model;
use App\Models\setup\ModelSetupbank;
use App\Models\transaksi\ModelLunasSalesman;
use App\Models\setup\ModelSetupsalesman;
use App\Models\transaksi\ModelRiwayatTransaksi;
use App\Models\transaksi\ModelRiwayatPiutang;
use App\Models\transaksi\penjualan\ModelPenjualan;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use TCPDF;

class LunasSalesman extends ResourceController
{
    protected $objSetupbank;
    protected $objLunasSalesman;
    protected $objSetupsalesman;
    protected $objRiwayatTransaksi;
    protected $objRiwayatPiutang;
    protected $objPenjualan;
    protected $db;

    //  INISIALISASI OBJECT DATA
    function __construct()
    {
        $this->objSetupsalesman = new ModelSetupsalesman();
        $this->objSetupbank = new ModelSetupbank();
        $this->objLunasSalesman = new ModelLunasSalesman();
        $this->objRiwayatTransaksi = new ModelRiwayatTransaksi();
        $this->objRiwayatPiutang = new ModelRiwayatPiutang();
        $this->objPenjualan = new ModelPenjualan();
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
        $data['dtlunassalesman'] = $this->objLunasSalesman->getAll();
        $data['dtsalesman'] = $this->objSetupsalesman->findAll();
        $data['dtsetupbank'] = $this->objSetupbank->findAll();
        return view('lunassalesman/index', $data);
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
        $data['dtpenjualan'] = $this->objPenjualan->where('opsi_pembayaran', 'kredit')->findAll();
        $data['dtlunassalesman'] = $this->objLunasSalesman->getAll();
        $data['dtsalesman'] = $this->objSetupsalesman->findAll();
        $data['dtsetupbank'] = $this->objSetupbank->findAll();
        return view('lunassalesman/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $this->db->transBegin();

        try {
            // Ambil nilai dari form dan pastikan menjadi angka
            $saldo = floatval($this->request->getVar('saldo'));
            $nilai_pelunasan = floatval($this->request->getVar('nilai_pelunasan'));
            $diskon = floatval($this->request->getVar('diskon'));

            // Hitung diskon sebagai persentase dari nilai pelunasan
            $diskon_amount = ($diskon / 100) * $nilai_pelunasan;

            // Kalkulasi sisa sesuai logika yang diterapkan pada JavaScript
            $sisa = $saldo - ($nilai_pelunasan + $diskon_amount);

            $data = [
                'nota'              => $this->request->getVar('nota'),
                'id_penjualan'              => $this->request->getVar('id_penjualan'),
                'id_salesman'      => $this->request->getVar('id_salesman'),
                'tanggal'           => $this->request->getVar('tanggal'),
                'id_setupbank'      => $this->request->getVar('id_setupbank'),
                'saldo'             => $saldo,
                'nilai_pelunasan'   => $nilai_pelunasan,
                'diskon'            => $diskon,
                'pdpt'              => $this->request->getVar('pdpt'),
                'sisa'              => $sisa,
                'keterangan'        => $this->request->getVar('keterangan'),
            ];
            $id_pelunasan = $this->objLunasSalesman->insert($data);

            $id_setupbuku = $this->objSetupbank->find($this->request->getVar('id_setupbank'))->id_setupbuku;

            // Penambahan saldo rekening bank di setupbuku
            $old_saldo = $this->db->table('setupbuku1')->where('id_setupbuku', $id_setupbuku)->get()->getRow()->saldo_berjalan;
            $new_saldo = $old_saldo + $nilai_pelunasan;
            $this->db->table('setupbuku1')->where('id_setupbuku', $id_setupbuku)->update(['saldo_berjalan' => $new_saldo]);

            // Simpan riwayat transaksi
            $dataRiwayatTransaksiRekening = [
                'tanggal' => $this->request->getVar('tanggal'),
                'jenis_transaksi' => 'pelunasan piutang',
                'id_transaksi' => $id_pelunasan,
                'nota' => $this->request->getVar('nota'),
                'id_setupbuku' => $id_setupbuku,
                'debit' => $nilai_pelunasan,
                'kredit' => 0,
                'saldo_setelah' => $new_saldo,
                'deskripsi' => $this->request->getVar('keterangan'),
            ];

            $this->objRiwayatTransaksi->insert($dataRiwayatTransaksiRekening);

            // simpan riwayat transaksi piutang
            $saldo_lama_salesman = $this->objSetupsalesman->find($this->request->getVar('id_salesman'))->saldo;

            $sisa_saldo_salesman = $saldo_lama_salesman - $nilai_pelunasan;

            $dataRiwayatPiutang = [
                'tanggal' => $this->request->getVar('tanggal'),
                'pelaku' => 'salesman',
                'id_transaksi' => $id_pelunasan,
                'jenis_transaksi' => 'pelunasan',
                'nota' => $this->request->getVar('nota'),
                'id_pelaku' => $this->request->getVar('id_salesman'),
                'debit' => 0,
                'kredit' => $nilai_pelunasan,
                'saldo_setelah' => $sisa_saldo_salesman,
                'deskripsi' => $this->request->getVar('keterangan'),
            ];

            $this->objRiwayatPiutang->insert($dataRiwayatPiutang);

            // Update saldo salesman
            $this->objSetupsalesman->update($this->request->getVar('id_salesman'), ['saldo' => $sisa_saldo_salesman]);

            // Commit transaksi jika semua operasi berhasil
            $this->db->transCommit();

            return redirect()->to(site_url('lunassalesman'))->with('Sukses', 'Data Berhasil Disimpan');
        } catch (\Exception $e) {
            // Jika terjadi kesalahan, rollback transaksi
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
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
        // Cek apakah pengguna memiliki peran admin
        if (!in_groups('admin')) {
            return redirect()->to('/')->with('error', 'Anda tidak memiliki akses');
        }

        // Ambil data berdasarkan ID
        $dtlunassalesman = $this->objLunasSalesman->find($id);

        // Cek jika data tidak ditemukan
        if (!$dtlunassalesman) {
            return redirect()->to(site_url('lunassalesman'))->with('error', 'Data tidak ditemukan');
        }


        // Lanjutkan jika semua pengecekan berhasil
        $data['dtpenjualan'] = $this->objPenjualan->where('opsi_pembayaran', 'kredit')->findAll();
        $data['dtlunassalesman'] = $dtlunassalesman;
        $data['dtsalesman'] = $this->objSetupsalesman->findAll();
        $data['dtsetupbank'] = $this->objSetupbank->findAll();
        return view('lunassalesman/edit', $data);
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
        // Cek apakah pengguna memiliki peran admin
        if (!in_groups('admin')) {
            return redirect()->to('/')->with('error', 'Anda tidak memiliki akses');
        }

        // Cek apakah data dengan ID yang diberikan ada di database
        $existingData = $this->objLunasSalesman->find($id);
        if (!$existingData) {
            return redirect()->to(site_url('lunassalesman'))->with('error', 'Data tidak ditemukan');
        }

        $this->db->transBegin();
        try {
            // Ambil nilai dari form dan pastikan menjadi angka
            $saldo = floatval($this->request->getVar('saldo'));
            $nilai_pelunasan = floatval($this->request->getVar('nilai_pelunasan'));
            $diskon = floatval($this->request->getVar('diskon'));

            // Hitung diskon sebagai persentase dari nilai pelunasan
            $diskon_amount = ($diskon / 100) * $nilai_pelunasan;

            // Kalkulasi sisa sesuai logika yang diterapkan pada JavaScript
            $sisa = $saldo - ($nilai_pelunasan + $diskon_amount);

            // Data untuk disimpan di database
            $data = [
                'nota'              => $this->request->getVar('nota'),
                'id_penjualan'      => $this->request->getVar('id_penjualan'),
                'id_salesman'       => $this->request->getVar('id_salesman'),
                'tanggal'           => $this->request->getVar('tanggal'),
                'id_setupbank'      => $this->request->getVar('id_setupbank'),
                'saldo'             => $saldo,
                'nilai_pelunasan'   => $nilai_pelunasan,
                'diskon'            => $diskon,
                'pdpt'              => $this->request->getVar('pdpt'),
                'sisa'              => $sisa,
                'keterangan'        => $this->request->getVar('keterangan'),
            ];

            // Update data berdasarkan ID
            $this->objLunasSalesman->update($id, $data);

            // Dapatkan data bank dan nilai pelunasan lama
            $old_bank_id = $existingData->id_setupbank;
            $old_nilai_pelunasan = floatval($existingData->nilai_pelunasan);
            $new_bank_id = $this->request->getVar('id_setupbank');

            // Dapatkan ID rekening buku untuk bank yang terkait
            $old_id_setupbuku = $this->objSetupbank->find($old_bank_id)->id_setupbuku ?? null;
            $new_id_setupbuku = $this->objSetupbank->find($new_bank_id)->id_setupbuku;

            // Jika bank berubah atau nilai pelunasan berubah
            if ($old_bank_id != $new_bank_id || $old_nilai_pelunasan != $nilai_pelunasan) {
                // Jika bank lama ada, kembalikan saldo bank lama
                if ($old_id_setupbuku) {
                    $old_bank_saldo = $this->db->table('setupbuku1')->where('id_setupbuku', $old_id_setupbuku)->get()->getRow()->saldo_berjalan;
                    $restored_bank_saldo = $old_bank_saldo - $old_nilai_pelunasan;
                    $this->db->table('setupbuku1')->where('id_setupbuku', $old_id_setupbuku)->update(['saldo_berjalan' => $restored_bank_saldo]);

                    // Update atau hapus riwayat transaksi lama
                    $old_riwayat = $this->objRiwayatTransaksi
                        ->where(['id_transaksi' => $id, 'jenis_transaksi' => 'pelunasan piutang', 'id_setupbuku' => $old_id_setupbuku])
                        ->first();

                    if ($old_riwayat) {
                        $this->objRiwayatTransaksi->delete($old_riwayat->id_riwayat);
                    }
                }

                // Update saldo bank baru
                $new_bank_saldo = $this->db->table('setupbuku1')->where('id_setupbuku', $new_id_setupbuku)->get()->getRow()->saldo_berjalan;
                $updated_bank_saldo = $new_bank_saldo + $nilai_pelunasan;
                $this->db->table('setupbuku1')->where('id_setupbuku', $new_id_setupbuku)->update(['saldo_berjalan' => $updated_bank_saldo]);

                // Buat riwayat transaksi baru
                $dataRiwayatTransaksiRekening = [
                    'tanggal' => $this->request->getVar('tanggal'),
                    'jenis_transaksi' => 'pelunasan piutang',
                    'id_transaksi' => $id,
                    'nota' => $this->request->getVar('nota'),
                    'id_setupbuku' => $new_id_setupbuku,
                    'debit' => $nilai_pelunasan,
                    'kredit' => 0,
                    'saldo_setelah' => $updated_bank_saldo,
                    'deskripsi' => $this->request->getVar('keterangan'),
                ];

                $this->objRiwayatTransaksi->insert($dataRiwayatTransaksiRekening);
            }

            // Handle perubahan data salesman
            $old_id_salesman = $existingData->id_salesman;
            $new_id_salesman = $this->request->getVar('id_salesman');

            if ($old_id_salesman != $new_id_salesman || $old_nilai_pelunasan != $nilai_pelunasan) {
                // Jika salesman berubah, kembalikan saldo salesman lama
                if ($old_id_salesman) {
                    $old_salesman_saldo = $this->objSetupsalesman->find($old_id_salesman)->saldo;
                    $restored_salesman_saldo = $old_salesman_saldo + $old_nilai_pelunasan;
                    $this->objSetupsalesman->update($old_id_salesman, ['saldo' => $restored_salesman_saldo]);

                    // Hapus riwayat piutang lama
                    $old_riwayat_piutang = $this->objRiwayatPiutang
                        ->where(['id_transaksi' => $id, 'jenis_transaksi' => 'pelunasan', 'id_pelaku' => $old_id_salesman])
                        ->first();

                    if ($old_riwayat_piutang) {
                        $this->objRiwayatPiutang->delete($old_riwayat_piutang->id_riwayat);
                    }
                }

                // Update saldo salesman baru
                $new_salesman_saldo = $this->objSetupsalesman->find($new_id_salesman)->saldo;
                $updated_salesman_saldo = $new_salesman_saldo - $nilai_pelunasan;
                $this->objSetupsalesman->update($new_id_salesman, ['saldo' => $updated_salesman_saldo]);

                // Buat riwayat piutang baru
                $dataRiwayatPiutang = [
                    'tanggal' => $this->request->getVar('tanggal'),
                    'pelaku' => 'salesman',
                    'id_transaksi' => $id,
                    'jenis_transaksi' => 'pelunasan',
                    'nota' => $this->request->getVar('nota'),
                    'id_pelaku' => $new_id_salesman,
                    'debit' => 0,
                    'kredit' => $nilai_pelunasan,
                    'saldo_setelah' => $updated_salesman_saldo,
                    'deskripsi' => $this->request->getVar('keterangan'),
                ];

                $this->objRiwayatPiutang->insert($dataRiwayatPiutang);
            }

            $this->db->transCommit();
            return redirect()->to(site_url('lunassalesman'))->with('success', 'Data berhasil diupdate.');
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return redirect()->to(site_url('lunassalesman'))->with('error', 'Data gagal diupdate: ' . $e->getMessage());
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
        $this->db->table('lunassalesman1')->where(['id_lunashusalesman' => $id])->delete();
        return redirect()->to(site_url('lunassalesman'))->with('Sukses', 'Data Berhasil Dihapus');
    }
}
