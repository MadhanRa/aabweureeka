<?php

namespace App\Controllers\setup;

use App\Models\setup\ModelAntarmuka;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Antarmuka extends ResourceController
{

    private $modelAntarmuka;
    private $db;
    function __construct()
    {
        $this->modelAntarmuka = new ModelAntarmuka();
        $this->db = \Config\Database::connect();
    }
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $data['dtantarmuka'] = $this->modelAntarmuka->findAll();
        return view('setup/antarmuka/index', $data);
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
        $builder = $this->db->table('interface1');
        $query = $builder->get();
        $data['dtantarmuka'] = $query->getResult();
        return view('setup/antarmuka/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $data = $this->request->getPost();
        $data = [
            'kas_setara' => $this->request->getVar('kas_setara'),
            'biaya' => $this->request->getVar('biaya'),
            'hutang' => $this->request->getVar('hutang'),
            'hpp' => $this->request->getVar('hpp'),
            'terima_mundur' => $this->request->getVar('terima_mundur'),
            'kl_laba_ditahan' => $this->request->getVar('kl_laba_ditahan'),
            'hutang_lancar' => $this->request->getVar('hutang_lancar'),
            'neraca_laba' => $this->request->getVar('neraca_laba'),
            'piutang_salesman' => $this->request->getVar('piutang_salesman'),
            'rekening_biaya' => $this->request->getVar('rekening_biaya'),
            'piutang_dagang' => $this->request->getVar('piutang_dagang'),
            'penjualan' => $this->request->getVar('penjualan'),
            'retur_penjualan' => $this->request->getVar('retur_penjualan'),
            'diskon_penjualan' => $this->request->getVar('diskon_penjualan'),
            'laba_bulan' => $this->request->getVar('laba_bulan'),
            'laba_tahun' => $this->request->getVar('laba_tahun'),
            'laba_ditahan' => $this->request->getVar('laba_ditahan'),
            'potongan_pembelian' => $this->request->getVar('potongan_pembelian'),
            'ppn_masukan' => $this->request->getVar('ppn_masukan'),
            'ppn_keluaran' => $this->request->getVar('ppn_keluaran'),
            'potongan_penjualan' => $this->request->getVar('potongan_penjualan'),
            'bank' => $this->request->getVar('bank'),
        ];
        $this->db->table('interface1')->insert($data);

        return redirect()->to(site_url('setup/antarmuka'))->with('Sukses', 'Data Berhasil Disimpan');
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
        $data['dtantarmuka'] = $this->modelAntarmuka->find($id);
        return view('setup/antarmuka/edit', $data);
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
        $data = [
            'kas_setara' => $this->request->getVar('kas_setara'),
            'biaya' => $this->request->getVar('biaya'),
            'hutang' => $this->request->getVar('hutang'),
            'hpp' => $this->request->getVar('hpp'),
            'terima_mundur' => $this->request->getVar('terima_mundur'),
            'kl_laba_ditahan' => $this->request->getVar('kl_laba_ditahan'),
            'hutang_lancar' => $this->request->getVar('hutang_lancar'),
            'neraca_laba' => $this->request->getVar('neraca_laba'),
            'piutang_salesman' => $this->request->getVar('piutang_salesman'),
            'rekening_biaya' => $this->request->getVar('rekening_biaya'),
            'piutang_dagang' => $this->request->getVar('piutang_dagang'),
            'penjualan' => $this->request->getVar('penjualan'),
            'retur_penjualan' => $this->request->getVar('retur_penjualan'),
            'diskon_penjualan' => $this->request->getVar('diskon_penjualan'),
            'laba_bulan' => $this->request->getVar('laba_bulan'),
            'laba_tahun' => $this->request->getVar('laba_tahun'),
            'laba_ditahan' => $this->request->getVar('laba_ditahan'),
            'potongan_pembelian' => $this->request->getVar('potongan_pembelian'),
            'ppn_masukan' => $this->request->getVar('ppn_masukan'),
            'ppn_keluaran' => $this->request->getVar('ppn_keluaran'),
            'potongan_penjualan' => $this->request->getVar('potongan_penjualan'),
            'bank' => $this->request->getVar('bank'),
        ];
        $this->modelAntarmuka->update($id, $data);

        return redirect()->to(site_url('setup/antarmuka'))->with('Sukses', 'Data Berhasil diupdate');
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
        $this->db->table('interface1')->where(['id_interface' => $id])->delete();
        return redirect()->to(site_url('setup/antarmuka'))->with('Sukses', 'Data Berhasil Dihapus');
    }
}
