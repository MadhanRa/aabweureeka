<?php

namespace App\Models\setup;

use CodeIgniter\Model;

class ModelUserLokasi extends Model
{
    protected $table = 'user_lokasi_opname';
    protected $primaryKey = 'id_user_lokasi';
    protected $returnType = 'object';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['id_user', 'id_lokasi'];
    public $timestamps = false;
}
