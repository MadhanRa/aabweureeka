<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ModelPeriode;
use CodeIgniter\HTTP\ResponseInterface;

class TransaksiController extends BaseController
{
     //  INISIALISASI OBJECT DATA
   function __construct()
   {
       $this->objPeriode = new ModelPeriode();   
       $this->db = \Config\Database::connect();

   }
    
    public function posting()
    {
         // Fetch the latest period entry
        $periodes = $this->objPeriode->orderBy('id_periode', 'DESC')->findAll(); 
        return view('transaksi/posting', ['periodes' => $periodes]);
        // return view('transaksi/posting');
    }

    public function prosesPosting()
    {

        
        return redirect()->to('/transaksi/posting')->with('message', 'Posting berhasil diproses');
    }

    public function tutupBuku()
    {
         // Fetch the latest period entry
         $periodes = $this->objPeriode->orderBy('id_periode', 'DESC')->findAll(); 
         return view('transaksi/tutup_buku', ['periodes' => $periodes]);
        // return view('transaksi/tutup_buku');
    }

    public function prosesTutupBuku()
    {

        return redirect()->to('/transaksi/tutup_buku')->with('message', 'Tutup Buku berhasil diproses');
    }
}
