<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangModel extends Model
{
    protected $table      = 'm_barang';
    protected $primaryKey = 'id_barang';

    // TAMBAHKAN 'stok' ke dalam array ini!
    protected $allowedFields = ['nama_barang', 'merk', 'satuan', 'stok', 'stok_minimal'];
}