<?php

namespace App\Models;

use CodeIgniter\Model;

class PermohonanModel extends Model
{
    protected $table      = 't_permohonan';
    protected $primaryKey = 'id_permohonan';
    
    // Gunakan auto increment karena id_permohonan biasanya AI
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    /**
     * PERBAIKAN:
     * 1. 'catatan' diganti menjadi 'keterangan_admin' (sesuai screenshot phpMyAdmin kamu)
     * 2. 'tanggal_pengajuan' diganti menjadi 'tgl_mohon' (sesuai screenshot phpMyAdmin kamu)
     */
    protected $allowedFields = [
        'no_permintaan', 
        'id_user', 
        'ruangan', 
        'no_wa', 
        'id_barang', 
        'jumlah', 
        'satuan', 
        'keterangan_admin', // Sesuaikan dengan database
        'status', 
        'tgl_mohon'         // Sesuaikan dengan database
    ];

    // Opsional: Jika kamu ingin CodeIgniter otomatis mengisi tgl_mohon
    // protected $useTimestamps = false; 
}