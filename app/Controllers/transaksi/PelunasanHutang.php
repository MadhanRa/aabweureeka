<?php

namespace App\Controllers\transaksi;

use App\Models\transaksi\ModelPelunasanHutang;
use App\Models\transaksi\ModelRiwayatTransaksi;
use App\Models\transaksi\ModelRiwayatHutang;
use App\Models\transaksi\pembelian\ModelPembelian;
use App\Models\setup\ModelSetupbank;
use App\Models\setup\ModelSetupsupplier;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use TCPDF;

class PelunasanHutang extends ResourceController
{
    protected $objSetupsupplier;
    protected $objSetupbank;
    protected $objPelunasanHutang;
    protected $objRiwayatTransaksi;
    protected $objRiwayatHutang;
    protected $objPembelian;
    protected $db;

    //  INISIALISASI OBJECT DATA
    function __construct()
    {
        $this->objSetupsupplier = new ModelSetupsupplier();
        $this->objSetupbank = new ModelSetupbank();
        $this->objPelunasanHutang = new ModelPelunasanHutang();
        $this->objPembelian = new ModelPembelian();
        $this->objRiwayatTransaksi = new ModelRiwayatTransaksi();
        $this->objRiwayatHutang = new ModelRiwayatHutang();
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
        $data['dtpelunasanhutang'] = $this->objPelunasanHutang->getAll();
        $data['dtsetupsupplier'] = $this->objSetupsupplier->findAll();
        $data['dtsetupbank'] = $this->objSetupbank->findAll();
        return view('pelunasanhutang/index', $data);
    }

    public function printPDF($id = null)
    {
        // Jika $id tidak diberikan, ambil semua data
        if ($id === null) {
            $data['dtpelunasanhutang'] = $this->objPelunasanHutang->getAll();
        } else {
            // Jika $id diberikan, ambil data berdasarkan ID dengan join
            $data['dtpelunasanhutang'] = $this->objPelunasanHutang->getById($id);
            if (empty($data['dtpelunasanhutang'])) {
                return redirect()->back()->with('error', 'Data tidak ditemukan.');
            }
        }

        $data['dtsetupsupplier'] = $this->objSetupsupplier->getAll();
        $data['dtsetupbank'] = $this->objSetupbank->getAll();

        // Debugging: Tampilkan konten HTML sebelum PDF
        $html = view('pelunasanhutang/printPDF', $data);
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
        $pdf->Output('pelunasan_hutang.pdf', 'I');
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
        $data['dtpembelian'] = $this->objPembelian->findAll();
        $data['dtpelunasanhutang'] = $this->objPelunasanHutang->getAll();
        $data['dtsetupsupplier'] = $this->objSetupsupplier->findAll();
        $data['dtsetupbank'] = $this->objSetupbank->findAll();
        return view('pelunasanhutang/new', $data);
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
                'id_pembelian'     => $this->request->getVar('id_pembelian'),
                'id_setupsupplier'      => $this->request->getVar('id_setupsupplier'),
                'tanggal'           => $this->request->getVar('tanggal'),
                'id_setupbank'      => $this->request->getVar('id_setupbank'),
                'saldo'             => $saldo,
                'nilai_pelunasan'   => $nilai_pelunasan,
                'diskon'            => $diskon,
                'pdpt'              => $this->request->getVar('pdpt'),
                'sisa'              => $sisa,
                'keterangan'        => $this->request->getVar('keterangan'),


            ];
            $id_pelunasan = $this->objPelunasanHutang->insert($data);

            // Pengurangan saldo rekening bank di setupbuku
            $id_setupbuku = $this->objSetupbank->find($this->request->getVar('id_setupbank'))->id_setupbuku;

            $old_saldo = $this->db->table('setupbuku1')->where('id_setupbuku', $id_setupbuku)->get()->getRow()->saldo_berjalan;
            $new_saldo = $old_saldo - $nilai_pelunasan;
            $this->db->table('setupbuku1')->where('id_setupbuku', $id_setupbuku)->update(['saldo_berjalan' => $new_saldo]);

            // Tambahkan riwayat transaksi rekening bank
            $riwayatData = [
                'tanggal'           => $this->request->getVar('tanggal'),
                'id_transaksi'     => $id_pelunasan,
                'jenis_transaksi'  => 'pelunasan',
                'nota'             => $this->request->getVar('nota'),
                'id_setupbuku' => $id_setupbuku,
                'debit'            => 0,
                'kredit'           => $nilai_pelunasan,
                'saldo_setelah'    => $new_saldo,
                'deskripsi'        => $this->request->getVar('keterangan'),
            ];

            $this->objRiwayatTransaksi->insert($riwayatData);

            // Tambahkan riwayat transaksi hutang
            $riwayatHutangData = [
                'tanggal'           => $this->request->getVar('tanggal'),
                'id_transaksi'      => $id_pelunasan,
                'jenis_transaksi'   => 'pelunasan',
                'nota'              => $this->request->getVar('nota'),
                'id_setupsupplier'  => $this->request->getVar('id_setupsupplier'),
                'deskripsi'         => $this->request->getVar('keterangan'),
                'debit'             => $nilai_pelunasan,
                'kredit'            => 0,
                'saldo_setelah'     => $sisa, // Sisa setelah pelunasan
            ];

            $this->objRiwayatHutang->insert($riwayatHutangData);

            // Update saldo supplier
            $saldo_lama_supplier = $this->objSetupsupplier->find($this->request->getVar('id_setupsupplier'))->saldo;

            $saldo_baru_supplier = $saldo_lama_supplier - $nilai_pelunasan;
            $this->objSetupsupplier->update($this->request->getVar('id_setupsupplier'), ['saldo' => $saldo_baru_supplier]);

            // Commit transaksi jika semua operasi berhasil
            $this->db->transCommit();

            return redirect()->to(site_url('pelunasanhutang'))->with('Sukses', 'Data Berhasil Disimpan');
        } catch (\Throwable $th) {
            // Jika terjadi kesalahan, rollback transaksi
            $this->db->transRollback();
            return redirect()->to(site_url('pelunasanhutang'))->with('error', 'Data Gagal Disimpan: ' . $th->getMessage());
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
        $dtpelunasanhutang = $this->objPelunasanHutang->find($id);

        // Cek jika data tidak ditemukan
        if (!$dtpelunasanhutang) {
            return redirect()->to(site_url('pelunasanhutang'))->with('error', 'Data tidak ditemukan');
        }


        // Lanjutkan jika semua pengecekan berhasil
        $data['dtpembelian'] = $this->objPembelian->findAll();
        $data['dtpelunasanhutang'] = $dtpelunasanhutang;
        $data['dtsetupsupplier'] = $this->objSetupsupplier->findAll();
        $data['dtsetupbank'] = $this->objSetupbank->findAll();
        return view('pelunasanhutang/edit', $data);
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
        $existingData = $this->objPelunasanHutang->find($id);
        if (!$existingData) {
            return redirect()->to(site_url('pelunasanhutang'))->with('error', 'Data tidak ditemukan');
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
            $sisa = $saldo - $nilai_pelunasan + $diskon_amount;

            // Ambil data yang diinputkan dari form
            $data = [
                'nota'              => $this->request->getVar('nota'),
                'id_setupsupplier'  => $this->request->getVar('id_setupsupplier'),
                'tanggal'           => $this->request->getVar('tanggal'),
                'id_setupbank'      => $this->request->getVar('id_setupbank'),
                'saldo'             => $saldo,
                'nilai_pelunasan'   => $nilai_pelunasan,
                'diskon'            => $diskon,
                'pdpt'              => $this->request->getVar('pdpt'),
                'sisa'              => $sisa,
                'keterangan'        => $this->request->getVar('keterangan'),
            ];

            // Ambil data lama untuk keperluan pembaruan saldo
            $old_nilai_pelunasan = floatval($existingData->nilai_pelunasan);
            $old_bank_id = $existingData->id_setupbank;
            $old_supplier_id = $existingData->id_setupsupplier;

            // Update data pelunasan hutang
            $this->objPelunasanHutang->update($id, $data);

            // Pengaturan saldo rekening bank
            // 1. Kembalikan saldo lama
            $old_bank_setupbuku_id = $this->objSetupbank->find($old_bank_id)->id_setupbuku;
            $current_bank_saldo = $this->db->table('setupbuku1')->where('id_setupbuku', $old_bank_setupbuku_id)->get()->getRow()->saldo_berjalan;
            $restored_bank_saldo = $current_bank_saldo + $old_nilai_pelunasan;
            $this->db->table('setupbuku1')->where('id_setupbuku', $old_bank_setupbuku_id)->update(['saldo_berjalan' => $restored_bank_saldo]);

            // 2. Kurangkan dengan nilai pelunasan baru (jika bank sama)
            $new_bank_setupbuku_id = $this->objSetupbank->find($this->request->getVar('id_setupbank'))->id_setupbuku;
            if ($old_bank_setupbuku_id == $new_bank_setupbuku_id) {
                $new_bank_saldo = $restored_bank_saldo - $nilai_pelunasan;
                $this->db->table('setupbuku1')->where('id_setupbuku', $new_bank_setupbuku_id)->update(['saldo_berjalan' => $new_bank_saldo]);
            } else {
                // Jika bank berbeda, kurangi saldo bank baru
                $new_bank_current_saldo = $this->db->table('setupbuku1')->where('id_setupbuku', $new_bank_setupbuku_id)->get()->getRow()->saldo_berjalan;
                $new_bank_saldo = $new_bank_current_saldo - $nilai_pelunasan;
                $this->db->table('setupbuku1')->where('id_setupbuku', $new_bank_setupbuku_id)->update(['saldo_berjalan' => $new_bank_saldo]);
            }

            // Update riwayat transaksi rekening bank
            // Hapus riwayat lama
            $this->objRiwayatTransaksi->where(['jenis_transaksi' => 'pelunasan_hutang', 'id_transaksi' => $id])->delete();

            // Tambah riwayat baru
            $riwayatData = [
                'tanggal'          => $this->request->getVar('tanggal'),
                'jenis_transaksi'  => 'pelunasan_hutang',
                'id_transaksi'     => $id,
                'nota'             => $this->request->getVar('nota'),
                'id_setupbuku'     => $new_bank_setupbuku_id,
                'debit'            => 0,
                'kredit'           => $nilai_pelunasan,
                'saldo_setelah'    => $new_bank_saldo,
                'deskripsi'        => $this->request->getVar('keterangan'),
            ];
            $this->objRiwayatTransaksi->insert($riwayatData);

            // Update riwayat hutang
            // Hapus riwayat lama
            $this->objRiwayatHutang->where(['jenis_transaksi' => 'pelunasan_hutang', 'id_transaksi' => $id])->delete();

            // Tambah riwayat baru
            $riwayatHutangData = [
                'tanggal'           => $this->request->getVar('tanggal'),
                'id_transaksi'      => $id,
                'jenis_transaksi'   => 'pelunasan_hutang',
                'nota'              => $this->request->getVar('nota'),
                'id_setupsupplier'  => $this->request->getVar('id_setupsupplier'),
                'deskripsi'         => $this->request->getVar('keterangan'),
                'debit'             => $nilai_pelunasan,
                'kredit'            => 0,
                'saldo_setelah'     => $sisa,
            ];
            $this->objRiwayatHutang->insert($riwayatHutangData);

            // Update saldo supplier
            // 1. Kembalikan saldo lama supplier
            $old_supplier_saldo = $this->objSetupsupplier->find($old_supplier_id)->saldo;
            $restored_supplier_saldo = $old_supplier_saldo + $old_nilai_pelunasan;

            if ($old_supplier_id == $this->request->getVar('id_setupsupplier')) {
                // Jika supplier sama, kurangkan langsung dengan nilai baru
                $new_supplier_saldo = $restored_supplier_saldo - $nilai_pelunasan;
                $this->objSetupsupplier->update($this->request->getVar('id_setupsupplier'), ['saldo' => $new_supplier_saldo]);
            } else {
                // Jika supplier berbeda, kembalikan saldo supplier lama
                $this->objSetupsupplier->update($old_supplier_id, ['saldo' => $restored_supplier_saldo]);

                // Kurangkan saldo supplier baru
                $new_supplier_current_saldo = $this->objSetupsupplier->find($this->request->getVar('id_setupsupplier'))->saldo;
                $new_supplier_saldo = $new_supplier_current_saldo - $nilai_pelunasan;
                $this->objSetupsupplier->update($this->request->getVar('id_setupsupplier'), ['saldo' => $new_supplier_saldo]);
            }

            // Commit transaksi jika semua operasi berhasil
            $this->db->transCommit();

            return redirect()->to(site_url('pelunasanhutang'))->with('success', 'Data berhasil diupdate.');
        } catch (\Throwable $th) {
            // Jika terjadi kesalahan, rollback transaksi
            $this->db->transRollback();
            return redirect()->to(site_url('pelunasanhutang'))->with('error', 'Data Gagal Diupdate: ' . $th->getMessage());
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
        $this->db->table('pelunasanhutang1')->where(['id_lunashutang' => $id])->delete();
        return redirect()->to(site_url('pelunasanhutang'))->with('Sukses', 'Data Berhasil Dihapus');
    }
}
