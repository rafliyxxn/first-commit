<?php

namespace App\Controllers;

use thiagoalessio\TesseractOCR\TesseractOCR;
use App\Models\BarangModel;
use App\Models\UserModel;
use App\Models\PermohonanModel;

class Admin extends BaseController
{
    protected $db;

    public function __construct() {
    $this->db = \Config\Database::connect();

    $role = session()->get('role');
    // Izinkan admin_tik dan admin_kepegawaian masuk ke controller ini
    if ($role != 'admin' && $role != 'admin_tik' && $role != 'admin_kepegawaian') {
        echo "<script>alert('Akses Ditolak!'); window.location.href='".base_url('auth/login')."';</script>";
        exit();
    }

}

    // --- DASHBOARD ---
   public function index()
    {
        $db = \Config\Database::connect();

        // 1. Hitung total permintaan berstatus 'PENDING' dari tabel t_permohonan
        try {
            $totalPending = $db->table('t_permohonan')
                               ->where('status', 'PENDING')
                               ->countAllResults();
        } catch (\Exception $e) {
            $totalPending = 0;
        }

        // 2. Ambil barang yang hampir habis dari tabel m_barang (stok di bawah atau sama dengan 5)
        try {
            $stokTipis = $db->table('m_barang')
                            ->where('stok <=', 5)
                            ->orderBy('stok', 'ASC')
                            ->get(3)
                            ->getResultArray();
        } catch (\Exception $e) {
            $stokTipis = [];
        }

        // 3. Ambil 4 permintaan terbaru dari tabel t_permohonan
        try {
            $permintaanTerbaru = $db->table('t_permohonan')
                                    ->orderBy('id_permohonan', 'DESC') // Pastikan primary key-mu sesuai
                                    ->get(4)
                                    ->getResultArray();
        } catch (\Exception $e) {
            $permintaanTerbaru = [];
        }

        $data = [
            'title'             => 'Dashboard Admin',
            'total_pending'     => $totalPending,
            'stok_hampir_habis' => $stokTipis,
            'permintaan_baru'   => $permintaanTerbaru
        ];

        // Pastikan nama file view ini benar. Jika folder admin kamu memiliki view dashboard bernama berbeda, silakan sesuaikan.
        return view('admin/v_dashboard', $data); 
    }

    // --- MASTER BARANG ---
    public function master_barang() {
        $model = new BarangModel();
        $data = [
            'title' => 'Master Barang', 
            'barang' => $model->findAll(), 
            'nama' => session()->get('nama')
        ];
        return view('admin/v_master_barang', $data);
    }

    public function simpan_barang() {
        $model = new BarangModel();
        $model->save([
            'nama_barang'   => $this->request->getPost('nama_barang'),
            'merk'          => $this->request->getPost('merk'),
            'satuan'        => $this->request->getPost('satuan'),
            'stok'          => $this->request->getPost('stok'), 
            'stok_minimal'  => $this->request->getPost('stok_minimal') ?? 0, 
        ]);
        return redirect()->to(base_url('admin/master_barang'))->with('success', 'Barang berhasil disimpan');
    }

    public function update_barang($id) {
        $model = new BarangModel();
        $model->update($id, [
            'nama_barang'   => $this->request->getPost('nama_barang'),
            'merk'          => $this->request->getPost('merk'),
            'satuan'        => $this->request->getPost('satuan'),
            'stok'          => $this->request->getPost('stok'), 
            'stok_minimal'  => $this->request->getPost('stok_minimal'),
        ]);
        return redirect()->to(base_url('admin/master_barang'))->with('success', 'Barang berhasil diupdate');
    }

    public function hapus_barang($id) {
        $model = new BarangModel();
        $model->delete($id);
        return redirect()->to(base_url('admin/master_barang'))->with('success', 'Barang berhasil dihapus');
    }

    // --- FITUR TAMBAH STOK VIA NOTA ---
    public function tambah_stok() {
        $data = [
            'title' => 'Input Stok via Nota',
            'uri'   => 'tambah_stok', 
            'menu'  => 'master_barang'
        ];
        return view('admin/v_input_nota', $data);
    }

    public function review_nota() {
    $fileNota = $this->request->getFile('nota');
    if ($fileNota === null || ! $fileNota->isValid()) {
        return redirect()->to(base_url('admin/tambah_stok'))->with('error', 'Upload gagal!');
    }

    $namaFile = $fileNota->getRandomName();
    $fileNota->move('uploads/nota/', $namaFile);
    $pathFoto = FCPATH . 'uploads/nota/' . $namaFile;

    $hasil_teks = "";
    try {
        $ocr = new \thiagoalessio\TesseractOCR\TesseractOCR($pathFoto);
        $ocr->executable('C:\Program Files\Tesseract-OCR\tesseract.exe');
        $hasil_teks = $ocr->run();
    } catch (\Exception $e) {
        $hasil_teks = "";
    }

    $hasil_deteksi = [];
    // PASTIKAN: tabel m_barang punya kolom 'satuan' dan 'stok'
    $semua_barang = $this->db->table('m_barang')->get()->getResultArray();

    if (!empty($hasil_teks)) {
        $baris_array = explode("\n", $hasil_teks);
        foreach ($baris_array as $baris) {
            $baris = trim($baris);
            if (empty($baris)) continue;

            foreach ($semua_barang as $b) {
                // Logika pencarian nama barang di dalam teks nota
                if (stripos($baris, $b['nama_barang']) !== false) {
                    // Cari angka di baris tersebut untuk Qty
                    $qty = 1;
                    if (preg_match('/\d+/', $baris, $matches)) {
                        $qty = $matches[0];
                    }

                    $hasil_deteksi[] = [
                        'id_barang' => $b['id_barang'],
                        'nama'      => $b['nama_barang'],
                        'satuan'    => $b['satuan'],
                        'qty'       => $qty,
                        'stok_awal' => $b['stok']
                    ];
                    break;
                }
            }
        }
    }

    return view('admin/v_review_nota', [
        'title'         => 'Review Stok Otomatis',
        'file_nota'     => $namaFile,
        'barang_all'    => $semua_barang,
        'hasil_deteksi' => $hasil_deteksi
    ]);
}

    public function simpan_stok_final() {
        $id_barang = $this->request->getPost('id_barang');
        $jumlah_masuk = $this->request->getPost('qty'); // Pastikan di View namanya qty[]

        if (empty($id_barang) || !is_array($id_barang)) {
            return redirect()->to(base_url('admin/tambah_stok'))->with('error', 'Tidak ada data barang yang disimpan.');
        }

        for ($i = 0; $i < count($id_barang); $i++) {
            $id = $id_barang[$i];
            $qty = $jumlah_masuk[$i] ?? 0;

            if (!empty($id) && $qty > 0) {
                $barang = $this->db->table('m_barang')->where('id_barang', $id)->get()->getRowArray();
                if ($barang) {
                    $stok_baru = $barang['stok'] + $qty;
                    $this->db->table('m_barang')->where('id_barang', $id)->update(['stok' => $stok_baru]);
                }
            }
        }
        return redirect()->to(base_url('admin/master_barang'))->with('success', 'Stok barang berhasil diupdate!');
    }

    // --- FITUR PERSETUJUAN ---
    public function persetujuan() {
        $builder = $this->db->table('t_permohonan');
        $builder->select('no_permintaan, MAX(ruangan) as ruangan, MAX(penanggung_jawab) as penanggung_jawab, MAX(no_wa) as no_wa, status, MAX(tgl_mohon) as tgl_mohon');
        $builder->groupBy('no_permintaan'); 
        $builder->orderBy('tgl_mohon', 'DESC');
        
        $data = [
            'title' => 'Persetujuan Permohonan',
            'nama' => session()->get('nama'),
            'permohonan' => $builder->get()->getResultArray()
        ];
        return view('admin/v_persetujuan', $data);
    }

    public function tindak_lanjut($no_permintaan) {
        $permohonan = $this->db->table('t_permohonan')
            ->select('t_permohonan.*, m_barang.nama_barang, m_barang.merk, m_barang.stok AS stok_gudang, m_barang.satuan AS satuan_barang')
            ->join('m_barang', 'm_barang.id_barang = t_permohonan.id_barang')
            ->where('t_permohonan.no_permintaan', $no_permintaan)
            ->get()->getResultArray();

        if (empty($permohonan)) {
            return redirect()->to(base_url('admin/persetujuan'))->with('error', 'Data permohonan tidak ditemukan.');
        }

        $data = [
            'title'            => 'Tindak Lanjut Permohonan',
            'nama'             => session()->get('nama'),
            'no_permintaan'    => $no_permintaan,
            'ruangan'          => $permohonan[0]['ruangan'],
            'penanggung_jawab' => $permohonan[0]['penanggung_jawab'],
            'permohonan'       => $permohonan
        ];
        return view('admin/v_tindak_lanjut', $data);
    }

    public function proses_tindak_lanjut() {
        $no_permintaan = $this->request->getPost('no_permintaan');
        $id_barangs    = $this->request->getPost('id_barang'); 
        $status_aksi   = $this->request->getPost('status_aksi'); 
        $qty_acc       = $this->request->getPost('qty_acc');   
        $catatan       = $this->request->getPost('catatan');   

        if (!$id_barangs) {
            return redirect()->back()->with('error', 'Tidak ada data.');
        }

        $pemohon = $this->db->table('t_permohonan')->where('no_permintaan', $no_permintaan)->get()->getRowArray();

        foreach ($id_barangs as $key => $id_barang) {
            $current_status = ($status_aksi == 'ditolak') ? 'ditolak' : 'selesai';
            $final_qty      = ($status_aksi == 'ditolak') ? 0 : (int)$qty_acc[$key];

            $this->db->table('t_permohonan')
                ->where('no_permintaan', $no_permintaan)
                ->where('id_barang', $id_barang)
                ->update([
                    'status'           => $current_status,
                    'jumlah'           => $final_qty,
                    'keterangan_admin' => $catatan[$key] ?? '',
                    'tgl_setuju'       => date('Y-m-d H:i:s')
                ]);

            if ($current_status == 'selesai' && $final_qty > 0) {
                $barang = $this->db->table('m_barang')->where('id_barang', $id_barang)->get()->getRowArray();
                if ($barang) {
                    $stok_baru = (int)$barang['stok'] - $final_qty;
                    $stok_baru = ($stok_baru < 0) ? 0 : $stok_baru;
                    $this->db->table('m_barang')->where('id_barang', $id_barang)->update(['stok' => $stok_baru]);
                }
            }
        }

        // Notifikasi WA Otomatis
        if ($pemohon && !empty($pemohon['no_wa'])) {
            $nama_user = $pemohon['penanggung_jawab'];
            $nomor_wa  = $pemohon['no_wa'];
            $msg = ($status_aksi == 'disetujui') 
                   ? "Halo *{$nama_user}*, permohonan #{$no_permintaan} telah *DISETUJUI*." 
                   : "Halo *{$nama_user}*, permohonan #{$no_permintaan} telah *DITOLAK*.";
            $this->kirim_wa_fonnte($nomor_wa, $msg);
        }

        return redirect()->to(base_url('admin/persetujuan'))->with('success', 'Permohonan diproses.');
    }

    public function kirim_notif_wa($no_permintaan) {
        $pemohon = $this->db->table('t_permohonan')->where('no_permintaan', $no_permintaan)->get()->getRowArray();
        if ($pemohon && !empty($pemohon['no_wa'])) {
            $pesan = "Halo *{$pemohon['penanggung_jawab']}* dari ruang *{$pemohon['ruangan']}*.\n\nRequest *{$no_permintaan}* telah kami proses.";
            $this->kirim_wa_fonnte($pemohon['no_wa'], $pesan);
            return redirect()->to(base_url('admin/persetujuan'))->with('success', 'Notifikasi WA Terkirim!');
        }
        return redirect()->to(base_url('admin/persetujuan'))->with('error', 'Nomor WA tidak ditemukan.');
    }

    private function kirim_wa_fonnte($nomor, $pesan) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('target' => $nomor, 'message' => $pesan, 'countryCode' => '62'),
            CURLOPT_HTTPHEADER => array('Authorization: T9YwpaMb3V1KHW7ys3rw'),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    // --- MANAJEMEN USER ---
    public function manajemen_user() {
        $model = new UserModel();
        $data = [
            'title' => 'Manajemen User', 
            'nama' => session()->get('nama'), 
            'users' => $model->where('role', 'user')->findAll()
        ];
        return view('admin/v_manajemen_user', $data);
    }

    public function simpan_user() {
        $model = new UserModel();
        $model->save([
            'nama_pegawai' => $this->request->getPost('nama_pegawai'),
            'username'     => $this->request->getPost('username'),
            'password'     => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'bagian'       => $this->request->getPost('bagian'),
            'no_hp'        => $this->request->getPost('no_hp'),
            'role'         => 'user'
        ]);
        return redirect()->to(base_url('admin/manajemen_user'))->with('success', 'User berhasil ditambah');
    }

    public function hapus_user($id) {
        $model = new UserModel();
        if ($model->find($id)) {
            $model->delete($id);
            return redirect()->to(base_url('admin/manajemen_user'))->with('success', 'User berhasil dihapus.');
        }
        return redirect()->to(base_url('admin/manajemen_user'))->with('error', 'User tidak ditemukan.');
    }

    public function update_user($id) {
        $model = new UserModel();
        
        // Ambil data dari form
        $data = [
            'nama_pegawai' => $this->request->getPost('nama_pegawai'),
            'username'     => $this->request->getPost('username'),
            'bagian'       => $this->request->getPost('bagian'),
            'no_hp'        => $this->request->getPost('no_hp'),
        ];

        // Cek jika password diisi, maka update passwordnya
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $model->update($id, $data);
        return redirect()->to(base_url('admin/manajemen_user'))->with('success', 'Data user berhasil diperbarui.');
    }

public function laporan()
    {
        // Menyediakan data title untuk template header
        $data = [
            'title' => 'Laporan Sistem'
        ];

        // Memanggil file view laporan yang terletak di: app/Views/admin/v_laporan.php
        return view('admin/v_laporan', $data);
    }

    // ==========================================
// 1. PROSES CETAK LAPORAN BARANG MASUK
// ==========================================
public function cetak_barang_masuk() {
    $tgl_mulai = $this->request->getGet('tgl_mulai');
    $tgl_selesai = $this->request->getGet('tgl_selesai');

    $db = \Config\Database::connect();
    
    // Query ini disesuaikan 100% dengan kolom asli database kamu
    $query = $db->query("
        SELECT 
            tp.no_nota, 
            tp.tgl_nota, 
            mb.nama_barang, 
            tpd.jumlah_masuk, 
            tpd.harga_satuan, 
            (tpd.jumlah_masuk * tpd.harga_satuan) as total
        FROM t_pembelian_detail tpd
        JOIN t_pembelian tp ON tpd.id_pembelian = tp.id_pembelian
        JOIN m_barang mb ON tpd.id_barang = mb.id_barang
        WHERE tp.tgl_nota BETWEEN ? AND ?
        ORDER BY tp.tgl_nota ASC
    ", [$tgl_mulai, $tgl_selesai]);

    $data = [
        'title'       => 'Laporan Barang Masuk & Pembelian',
        'tgl_mulai'   => $tgl_mulai,
        'tgl_selesai' => $tgl_selesai,
        'laporan'     => $query->getResultArray()
    ];

    return view('admin/cetak/v_cetak_barang_masuk', $data);
}

// ==========================================
// 2. PROSES CETAK LAPORAN STOK OPNAME
// ==========================================
public function cetak_stok_opname() {
    $bulan = $this->request->getGet('bulan'); // Format: Angka 1-12 atau nama bulan
    $tahun = $this->request->getGet('tahun'); // Format: YYYY (contoh: 2026)

    $db = \Config\Database::connect();
    
    // Kita lakukan JOIN dengan m_barang agar bisa memunculkan nama barang
    // Filter dilakukan berdasarkan BULAN dan TAHUN dari kolom tanggal_opname
    $query = $db->query("
        SELECT 
            so.id_opname,
            so.tanggal_opname,
            mb.nama_barang,
            so.stok_sistem,
            so.stok_fisik,
            so.selisih,
            so.keterangan
        FROM t_stok_opname so
        JOIN m_barang mb ON so.id_barang = mb.id_barang
        WHERE MONTH(so.tanggal_opname) = ? 
          AND YEAR(so.tanggal_opname) = ?
        ORDER BY so.tanggal_opname ASC
    ", [$bulan, $tahun]);

    // Mapping nama bulan untuk judul laporan
    $nama_bulan = [
        '1' => 'Januari', '2' => 'Februari', '3' => 'Maret', '4' => 'April',
        '5' => 'Mei', '6' => 'Juni', '7' => 'Juli', '8' => 'Agustus',
        '9' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    ];

    $data = [
        'title'      => 'Laporan Stok Opname',
        'bulan_txt'  => isset($nama_bulan[$bulan]) ? $nama_bulan[$bulan] : $bulan,
        'tahun'      => $tahun,
        'laporan'    => $query->getResultArray()
    ];

    return view('admin/cetak/v_cetak_stok_opname', $data);
}

// ==========================================
// 3. PROSES CETAK LAPORAN STOK AKHIR BULAN
// ==========================================
public function cetak_stok_bulanan() {
    $bulan = $this->request->getGet('bulan');
    $tahun = $this->request->getGet('tahun');

    $db = \Config\Database::connect();
    
    // Query mengambil semua master barang dan mencocokkan sisa stoknya dari t_stok
    $query = $db->query("
        SELECT 
            mb.id_barang,
            mb.nama_barang,
            mb.satuan,
            IFNULL(ts.jumlah_stok, 0) as sisa_stok,
            ts.update_terakhir
        FROM m_barang mb
        LEFT JOIN t_stok ts ON mb.id_barang = ts.id_barang
        ORDER BY mb.nama_barang ASC
    ");

    $nama_bulan = [
        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
        '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
        '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
        '1' => 'Januari', '2' => 'Februari', '3' => 'Maret', '4' => 'April',
        '5' => 'Mei', '6' => 'Juni', '7' => 'Juli', '8' => 'Agustus',
        '9' => 'September'
    ];

    $data = [
        'title'      => 'Laporan Stok Akhir Bulan',
        'bulan_txt'  => isset($nama_bulan[$bulan]) ? $nama_bulan[$bulan] : $bulan,
        'tahun'      => $tahun,
        'laporan'    => $query->getResultArray()
    ];

    // Kita arahkan ke nama view v_cetak_stok_bulanan sesuai rutenya
    return view('admin/cetak/v_cetak_stok_bulanan', $data);
}

// ==========================================
// 4. PROSES CETAK REKAP PERMINTAAN RUANGAN
// ==========================================
public function cetak_rekap_permintaan() {
    $bulan = $this->request->getGet('bulan');
    $tahun = $this->request->getGet('tahun');

    $db = \Config\Database::connect();
    
    // Query disesuaikan dengan kolom asli: no_permintaan dan tgl_mohon
    $query = $db->query("
        SELECT 
            tp.no_permintaan, 
            tp.tgl_mohon, 
            tp.ruangan,
            tp.penanggung_jawab,
            mb.nama_barang, 
            tp.jumlah,
            tp.satuan,
            tp.status
        FROM t_permohonan tp
        JOIN m_barang mb ON tp.id_barang = mb.id_barang
        WHERE MONTH(tp.tgl_mohon) = ? 
          AND YEAR(tp.tgl_mohon) = ?
          AND tp.status = 'selesai'
        ORDER BY tp.tgl_mohon ASC
    ", [$bulan, $tahun]);

    $nama_bulan = [
        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
        '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
        '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    ];

    $data = [
        'title'      => 'Rekap Permintaan Barang Per Ruangan',
        'bulan_txt'  => isset($nama_bulan[$bulan]) ? $nama_bulan[$bulan] : $bulan,
        'tahun'      => $tahun,
        'laporan'    => $query->getResultArray()
    ];

    return view('admin/cetak/v_cetak_rekap_permintaan', $data);
}

public function cetak_berita_acara()
{
    $no_nota = $this->request->getGet('no_nota');

    // Ambil data transaksi utama (Pastikan nomor nota ada di database ya!)
    $transaksi = $this->db->table('t_permohonan')
        ->join('users', 'users.id_user = t_permohonan.id_user', 'left') 
        ->where('no_permintaan', $no_nota) 
        ->get()->getRowArray();

    if (!$transaksi) {
        return "Gagal Generate: Data dengan nomor " . htmlspecialchars($no_nota) . " belum ada di tabel t_permohonan kamu.";
    }

    // Ambil data barang yang diminta
    $items = $this->db->table('t_permohonan_detail')
        ->join('m_barang', 'm_barang.id_barang = t_permohonan_detail.id_barang')
        ->where('id_permohonan', $transaksi['id_permohonan'])
        ->get()->getResultArray();

    // ---------------------------------------------------------
    // LOGIKA FORMAT TANGGAL & NAMA PENANGGUNG JAWAB SESUAI FOTO
    // ---------------------------------------------------------
    $tgl = strtotime($transaksi['tgl_mohon'] ?? date('Y-m-d')); 
    $hari_array = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
    $bulan_array = ['01' => 'Januari', '02' => 'Pebruari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'Nopember', '12' => 'Desember'];
    $tahun_terbilang = ['2023' => 'Dua Ribu Dua Puluh Tiga', '2024' => 'Dua Ribu Dua Puluh Empat', '2025' => 'Dua Ribu Dua Puluh Lima', '2026' => 'Dua Ribu Dua Puluh Enam', '2027' => 'Dua Ribu Dua Puluh Tujuh'];

    $data = [
        'transaksi'   => $transaksi,
        'items'       => $items,
        'nomor_ba'    => $no_nota,
        'hari'        => $hari_array[date('l', $tgl)],
        'tgl_angka'   => date('d', $tgl),
        'bulan_huruf' => $bulan_array[date('m', $tgl)],
        'tahun_huruf' => $tahun_terbilang[date('Y', $tgl)] ?? date('Y', $tgl),
        'periode'     => $bulan_array[date('m', $tgl)] . ' ' . date('Y', $tgl)
    ];

    // Ngambil list nama dari tabel
    $pj_string = $transaksi['penanggung_jawab'] ?? '';
    $data['list_pj'] = array_filter(array_map('trim', explode(',', $pj_string)));

    // Render ke View HTML dulu
    $html = view('admin/v_cetak_berita_acara', $data);

    // ---------------------------------------------------------
    // PROSES GENERATE KE PDF (DOMPDF)
    // ---------------------------------------------------------
    $options = new \Dompdf\Options();
    $options->set('isRemoteEnabled', true); // Wajib True biar logo bisa di-generate
    
    $dompdf = new \Dompdf\Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    
    // Output langsung ke Browser berupa file PDF
    $dompdf->stream("Berita_Acara_ATK_".$no_nota.".pdf", ["Attachment" => 0]);
}
}