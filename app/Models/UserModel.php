<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users'; // Sesuaikan dengan nama tabel di DB kamu
    protected $primaryKey = 'id_user';
    protected $allowedFields = ['username', 'password', 'nama_pegawai', 'role', 'bagian', 'no_hp'];
}