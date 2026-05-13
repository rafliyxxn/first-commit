<?php

namespace App\Controllers;

use App\Models\UserModel;
// Sesuaikan nama model barang, transaksi masuk, dan permintaan milikmu
// Contoh asumsi nama model:
// use App\Models\BarangModel; 
// use App\Models\PermintaanModel;

class Laporan extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Laporan Sistem - E-Persediaan TIK'
        ];
        return view('admin/laporan/v_index', $data);
    }

    // 1. Laporan Barang Masuk & Pembelian Pertanggal
    public function barang_masuk()
    {
        $tgl_mulai = $this->request->getGet('tgl_mulai');
        $tgl_selesai = $this->request->getGet('tgl_selesai');

        // Lakukan query ke tabel barang masuk / nota berdasarkan tanggal
        // $data['laporan'] = $this->barangMasukModel->getLaporan($tgl_mulai, $tgl_selesai);

        $data['tgl_mulai'] = $tgl_mulai;
        $data['tgl_selesai'] = $tgl_selesai;
        return view('admin/laporan/cetak_barang_masuk', $data);
    }

    // 2. Laporan Stok Opname
    public function stok_opname()
    {
        $bulan = $this->request->getGet('bulan') ?? date('m');
        $tahun = $this->request->getGet('tahun') ?? date('Y');

        // Ambil data dari t_stok_opname
        return view('admin/laporan/cetak_stok_opname');
    }

    // 3. Laporan Stok Barang Terakhir (Per Akhir Bulan)
    public function stok_bulanan()
    {
        $bulan = $this->request->getGet('bulan') ?? date('m');
        $tahun = $this->request->getGet('tahun') ?? date('Y');

        return view('admin/laporan/cetak_stok_bulanan');
    }

    // 4. Laporan Rekap Permintaan Per Ruangan (Bulanan)
    public function rekap_permintaan()
    {
        $bulan = $this->request->getGet('bulan') ?? date('m');
        $tahun = $this->request->getGet('tahun') ?? date('Y');

        return view('admin/laporan/cetak_rekap_permintaan');
    }
}