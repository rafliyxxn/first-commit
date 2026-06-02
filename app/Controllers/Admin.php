<?php

namespace App\Controllers;

use thiagoalessio\TesseractOCR\TesseractOCR;
use App\Models\BarangModel;
use App\Models\UserModel;
use App\Models\PermohonanModel;

class Admin extends BaseController
{
    protected $db;

    public function __construct() 
    {
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

        // 1. Hitung total permintaan berstatus 'PENDING'
        try {
            $totalPending = $db->table('t_permohonan')
                               ->where('status', 'PENDING')
                               ->countAllResults();
        } catch (\Exception $e) {
            $totalPending = 0;
        }

        // 2. Ambil barang yang hampir habis (stok <= 5)
        try {
            $stokTipis = $db->table('m_barang')
                            ->where('stok <=', 5)
                            ->orderBy('stok', 'ASC')
                            ->limit(5) // Ditambah jadi 5 agar pas di desain
                            ->get()
                            ->getResultArray();
        } catch (\Exception $e) {
            $stokTipis = [];
        }

        // 3. Ambil 5 permintaan terbaru dengan join ke m_barang
        try {
            $permintaanTerbaru = $db->table('t_permohonan')
                                    ->select('t_permohonan.*, m_barang.nama_barang')
                                    ->join('m_barang', 'm_barang.id_barang = t_permohonan.id_barang', 'left')
                                    ->orderBy('id_permohonan', 'DESC') 
                                    ->limit(5)
                                    ->get()
                                    ->getResultArray();
        } catch (\Exception $e) {
            $permintaanTerbaru = [];
        }

        // 4. Data Grafik: Barang paling banyak diminta (Tahun Ini)
        $tahun_ini = date('Y');
        $bulan_ini = date('m');

        try {
            $topTahunIni = $db->query("
                SELECT mb.nama_barang, SUM(tp.jumlah) as total 
                FROM t_permohonan tp
                JOIN m_barang mb ON tp.id_barang = mb.id_barang
                WHERE (tp.status = 'selesai' OR tp.status = 'ACC') 
                AND YEAR(tp.tgl_mohon) = ?
                GROUP BY tp.id_barang, mb.nama_barang
                ORDER BY total DESC
                LIMIT 7
            ", [$tahun_ini])->getResultArray();
        } catch (\Exception $e) {
            $topTahunIni = [];
        }

        // 5. Data Grafik: Barang paling banyak diminta (Bulan Ini)
        try {
            $topBulanIni = $db->query("
                SELECT mb.nama_barang, SUM(tp.jumlah) as total 
                FROM t_permohonan tp
                JOIN m_barang mb ON tp.id_barang = mb.id_barang
                WHERE (tp.status = 'selesai' OR tp.status = 'ACC') 
                AND YEAR(tp.tgl_mohon) = ? AND MONTH(tp.tgl_mohon) = ?
                GROUP BY tp.id_barang, mb.nama_barang
                ORDER BY total DESC
                LIMIT 7
            ", [$tahun_ini, $bulan_ini])->getResultArray();
        } catch (\Exception $e) {
            $topBulanIni = [];
        }

        $data = [
            'title'         => 'Dashboard Admin',
            'total_pending' => $totalPending,
            'stok_tipis'    => $stokTipis,
            'terbaru'       => $permintaanTerbaru,
            'top_tahun_ini' => json_encode($topTahunIni), // Kirim sebagai JSON untuk Chart.js
            'top_bulan_ini' => json_encode($topBulanIni), // Kirim sebagai JSON untuk Chart.js
            'tahun_ini'     => $tahun_ini
        ];

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
    // 1. Ambil file gambar nota dari form upload
    $fileNota = $this->request->getFile('nota');
    if ($fileNota === null || ! $fileNota->isValid()) {
        return redirect()->to(base_url('admin/tambah_stok'))->with('error', 'Upload gagal!');
    }

    $mimeType = $fileNota->getMimeType();

    // 2. Berikan nama acak dan pindahkan ke folder public uploads
    $namaFile = $fileNota->getRandomName();
    $fileNota->move('uploads/nota/', $namaFile);
    $pathFoto = FCPATH . 'uploads/nota/' . $namaFile;

    // 3. API Key OCR.space milikmu
    $apiKey = 'K87994429288957'; 
    $apiUrl = 'https://api.ocr.space/parse/image';

    // 4. Konversi gambar ke format Base64 khusus untuk OCR.space
    $base64Image = 'data:' . $mimeType . ';base64,' . base64_encode(file_get_contents($pathFoto));

    // 5. Susun Payload (Gunakan Engine 2 + isTable untuk akurasi maksimal)
    $postData = [
        'base64Image' => $base64Image,
        'language'    => 'eng',
        'isTable'     => 'true',
        'scale'       => 'true',
        'OCREngine'   => '2' 
    ];

    $raw_text = "";
    $error_ocr = "";
    $hasil_deteksi = [];

    // 6. Jalankan request cURL ke server OCR.space
    try {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['apikey: ' . $apiKey]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        // Bypass SSL Localhost XAMPP
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        
        $response = curl_exec($ch);
        if (curl_errno($ch)) throw new \Exception(curl_error($ch));
        curl_close($ch);

        $result = json_decode($response, true);
        if (isset($result['IsErroredOnProcessing']) && $result['IsErroredOnProcessing'] == true) {
            $pesanError = is_array($result['ErrorMessage']) ? implode(', ', $result['ErrorMessage']) : $result['ErrorMessage'];
            throw new \Exception($pesanError);
        }
        $raw_text = $result['ParsedResults'][0]['ParsedText'] ?? '';

    } catch (\Exception $e) {
        $error_ocr = "Kendala API OCR.space: " . $e->getMessage();
    }

    // 7. Ambil semua data master barang dari database
    $semua_barang = $this->db->table('m_barang')->get()->getResultArray();

    // 8. PROSES OLAH DATA DENGAN SMART FILTER & LOGIKA MULTI-LAYER DETEKSI
    if (!empty($raw_text)) {
        $baris_array = explode("\n", $raw_text);
        
        // Kata penanda baris non-barang yang harus dibuang
        $blacklist = ['total', 'grand', 'subtotal', 'harga', 'faktur', 'invoice', 'nota', 'tanggal', 'kepada', 'jumlah', 'diskon', 'netto', 'bayar', 'kembali', 'cash', 'kembar', 'pembelian', 'alamat', 'telp', 'no.'];

        foreach ($baris_array as $baris) {
            $baris = trim($baris);
            
            if (empty($baris) || strlen($baris) < 3) continue;
            if (preg_match('/^[0-9\s.,\/|+-]+$/', $baris)) continue; // Abaikan jika cuma garis pembatas angka

            // Validasi Blacklist
            $is_blacklist = false;
            foreach ($blacklist as $word) {
                if (stripos($baris, $word) !== false) {
                    $is_blacklist = true;
                    break;
                }
            }
            if ($is_blacklist) continue;

            // --- Tahap A: Word-Token Matching (Pencocokan Nama Barang) ---
            $id_barang_ditemukan = '';
            $nama_db_ditemukan = '';
            $satuan_final = '';
            $stok_awal_final = 0;
            $max_score = 0; 

            foreach ($semua_barang as $b) {
                $nama_db_lower = strtolower($b['nama_barang']);
                $baris_lower = strtolower($baris);

                // 1. Cek substring langsung
                if (stripos($baris_lower, $nama_db_lower) !== false) {
                    $id_barang_ditemukan = $b['id_barang'];
                    $nama_db_ditemukan = $b['nama_barang'];
                    $satuan_final = $b['satuan'];
                    $stok_awal_final = $b['stok'] ?? 0;
                    break; 
                }

                // 2. Cek berbasis Fleksibilitas Kata Kunci (Untuk merk selipan)
                $db_clean = preg_replace('/[^a-z0-9]/', ' ', $nama_db_lower);
                $baris_clean = preg_replace('/[^a-z0-9]/', ' ', $baris_lower);

                $words_db = array_filter(explode(' ', $db_clean), function($val) {
                    return trim($val) !== '';
                });

                if (!empty($words_db)) {
                    $matched_count = 0;
                    foreach ($words_db as $word) {
                        if (stripos($baris_clean, $word) !== false) {
                            $matched_count++;
                        }
                    }

                    $score = $matched_count / count($words_db);
                    if ($score >= 0.70 && $score > $max_score) {
                        $max_score = $score;
                        $id_barang_ditemukan = $b['id_barang'];
                        $nama_db_ditemukan = $b['nama_barang'];
                        $satuan_final = $b['satuan'];
                        $stok_awal_final = $b['stok'] ?? 0;
                    }
                }
            }

            // --- Tahap B: Ekstraksi Kuantitas (Qty) & Satuan ---
            $qty = 1;
            $satuan_nota = 'Pcs';
            $has_unit_match = false;

            // 1. NORMALISASI TULISAN TANGAN & FILTERING SPASI OCR
            // Perbaiki huruf 'o'/'O' yang sering keliru menggantikan angka 0 (Misal: 1o -> 10)
            $baris_normalized = preg_replace('/(\d)[oO]/', '${1}0', $baris);
            
            // Satukan angka yang renggang akibat tulisan tangan sebelum nama satuan (Misal: "1 0 Pcs" -> "10 Pcs")
            $baris_normalized = preg_replace('/(\d)\s+(\d)\s*(?:rim|pcs|pes|cs|roll|pack|pak|buah|bh|btg|lembar|lbr|box|dus|kg|gr|gram|ltr|lsn|lusin)/i', '$1$2', $baris_normalized);

            // Regex Satuan Flexible (Mengakomodasi variasi typo hasil OCR)
            $pattern_satuan_flexible = '/(\d+(?:[.,]\d+)?)\s*(rim|pcs|pes|cs|prs|roll|pack|pak|buah|bh|btg|lembar|lbr|box|dus|kg|gr|gram|ltr|lsn|lusin)/i';

            if (preg_match_all($pattern_satuan_flexible, $baris_normalized, $matches, PREG_OFFSET_CAPTURE)) {
                $last_full_match = end($matches[0]);
                $last_qty_match  = end($matches[1]);
                $last_sat_match  = end($matches[2]);

                $qty_clean = str_replace(',', '.', $last_qty_match[0]);
                $qty_parsed = floatval($qty_clean);
                
                $qty = ($qty_parsed == intval($qty_parsed)) ? intval($qty_parsed) : $qty_parsed;
                
                // Normalisasi penulisan nama satuan ke database
                $sat_raw = strtolower($last_sat_match[0]);
                if (in_array($sat_raw, ['pcs', 'pes', 'cs', 'prs'])) {
                    $satuan_nota = 'Pcs';
                } else if ($sat_raw == 'pak') {
                    $satuan_nota = 'Pack';
                } else {
                    $satuan_nota = ucfirst($sat_raw);
                }
                $has_unit_match = true;
            }

            // 2. SMART FALLBACK HARVESTER (Andalan untuk Nota Tulisan Tangan)
            // Jika satuan resmi tidak terdeteksi OR angka yang didapat cuma 1 (dicurigai gagal regex)
            if (!$has_unit_match || $qty == 1) {
                // Buang nomor urut di awal baris (misal "1. ", "2) ") agar tidak dikira jumlah barang
                $baris_tanpa_no_urut = preg_replace('/^\d+\s*[\.)\]-]\s*/', '', $baris_normalized);
                
                // Panen semua angka murni yang tersisa di baris tersebut
                if (preg_match_all('/(\d+)/', $baris_tanpa_no_urut, $fallback_matches)) {
                    $kandidat_qty = [];
                    foreach ($fallback_matches[1] as $num_str) {
                        $num_val = intval($num_str);
                        // Kuantitas logis barang ATK biasanya di bawah 1000 (menghindari angka tahun atau nominal harga)
                        if ($num_val > 0 && $num_val < 1000) {
                            $kandidat_qty[] = $num_val;
                        }
                    }
                    
                    // Ambil angka paling belakang/kanan (karena posisi Qty nota selalu di setelah nama barang)
                    if (!empty($kandidat_qty)) {
                        $angka_terakhir = end($kandidat_qty);
                        if ($angka_terakhir > 1 || !$has_unit_match) {
                            $qty = $angka_terakhir;
                            $has_unit_match = true; // Loloskan dari filter sampah
                        }
                    }
                }
            }

            // FILTER SAMPAH: Jika barang tidak di DB dan tidak ada angka kuantitas yang valid, buang!
            if (empty($id_barang_ditemukan) && !$has_unit_match) {
                continue; 
            }

            // Jika barang baru/tidak ada di DB tapi ada kuantitas murni yang sah
            if (empty($id_barang_ditemukan)) {
                $offset_potong = isset($last_full_match) ? $last_full_match[1] : strlen($baris_normalized);
                $nama_bersih = trim(substr($baris_normalized, 0, $offset_potong));
                $nama_db_ditemukan = trim($nama_bersih, " -.,:/|+#");
                $satuan_final = $satuan_nota;
            }

            $hasil_deteksi[] = [
                'id_barang' => $id_barang_ditemukan,
                'nama'      => !empty($nama_db_ditemukan) ? $nama_db_ditemukan : $baris,
                'satuan'    => !empty($satuan_final) ? $satuan_final : $satuan_nota,
                'qty'       => $qty,
                'stok_awal' => $stok_awal_final
            ];
        }
    }

    // 9. Kirimkan semua variabel ke halaman View
    return view('admin/v_review_nota', [
        'title'         => 'Review Stok Otomatis (OCR.space API)',
        'file_nota'     => $namaFile,
        'barang_all'    => $semua_barang,
        'hasil_deteksi' => $hasil_deteksi,
        'raw_text'      => $raw_text,  
        'error_ocr'     => $error_ocr  
    ]);
}

    public function simpan_stok_final() {
        $id_barang = $this->request->getPost('id_barang');
        $jumlah_masuk = $this->request->getPost('qty');

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

        $status_lower = strtolower(trim($status_aksi));
        $is_ditolak   = ($status_lower == 'ditolak');

        foreach ($id_barangs as $key => $id_barang) {
            $current_status = $is_ditolak ? 'ditolak' : 'selesai';
            $final_qty      = $is_ditolak ? 0 : (int)$qty_acc[$key];

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

        if ($pemohon && !empty($pemohon['no_wa'])) {
            $nama_user = $pemohon['penanggung_jawab'];
            $nomor_wa  = $pemohon['no_wa'];
            $msg = "Halo *{$nama_user}*, permohonan barang Anda dengan nomor request *{$no_permintaan}* telah *KAMI PROSES*.";
            $this->kirim_wa_fonnte($nomor_wa, $msg);
        }

        return redirect()->to(base_url('admin/persetujuan'))->with('success', 'Permohonan berhasil diproses.');
    }

    public function kirim_notif_wa($no_permintaan) {
        $pemohon = $this->db->table('t_permohonan')->where('no_permintaan', $no_permintaan)->get()->getRowArray();
        
        if ($pemohon && !empty($pemohon['no_wa'])) {
            $rincian_barang = $this->db->table('t_permohonan')
                ->select('t_permohonan.status, t_permohonan.jumlah, t_permohonan.keterangan_admin, m_barang.nama_barang')
                ->join('m_barang', 'm_barang.id_barang = t_permohonan.id_barang')
                ->where('t_permohonan.no_permintaan', $no_permintaan)
                ->get()->getResultArray();

            $nama_user = $pemohon['penanggung_jawab'];
            $ruangan   = $pemohon['ruangan'];
            
            $pesan = "Halo *{$nama_user}* (Ruang *{$ruangan}*).\n\nBerikut adalah detail hasil rincian untuk permohonan barang Anda dengan Nomor Request *{$no_permintaan}*:\n\n";
            
            $no = 1;
            foreach ($rincian_barang as $item) {
                $qty     = (int)$item['jumlah'];
                $catatan = empty($item['keterangan_admin']) ? '-' : $item['keterangan_admin'];

                if ($qty <= 0 || strtoupper($item['status']) === 'DITOLAK') {
                    $status_item = 'DITOLAK';
                } else {
                    $status_item = 'SELESAI';
                }

                $pesan .= "*{$no}. {$item['nama_barang']}*\n";
                $pesan .= "├ Status : *{$status_item}*\n";
                
                if ($status_item === 'SELESAI') {
                    $pesan .= "├ Di-ACC : {$qty}\n";
                }
                
                $pesan .= "└ Catatan: {$catatan}\n\n";
                $no++;
            }
            
            $pesan .= "Untuk barang dengan status SELESAI, silakan diambil di Ruangan TIK. Terima kasih.";

            $this->kirim_wa_fonnte($pemohon['no_wa'], $pesan);
            
            return redirect()->to(base_url('admin/persetujuan'))->with('success', 'Rincian Notifikasi WA berhasil dikirim!');
        }
        
        return redirect()->to(base_url('admin/persetujuan'))->with('error', 'Gagal mengirim: Nomor WA tidak ditemukan.');
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
        
        $data = [
            'nama_pegawai' => $this->request->getPost('nama_pegawai'),
            'username'     => $this->request->getPost('username'),
            'bagian'       => $this->request->getPost('bagian'),
            'no_hp'        => $this->request->getPost('no_hp'),
        ];

        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $model->update($id, $data);
        return redirect()->to(base_url('admin/manajemen_user'))->with('success', 'Data user berhasil diperbarui.');
    }

    public function laporan()
    {
        $data = [
            'title' => 'Laporan Sistem'
        ];
        return view('admin/v_laporan', $data);
    }

    // ==========================================
    // 1. PROSES CETAK LAPORAN BARANG MASUK
    // ==========================================
    public function cetak_barang_masuk() {
        $tgl_mulai = $this->request->getGet('tgl_mulai');
        $tgl_selesai = $this->request->getGet('tgl_selesai');

        $db = \Config\Database::connect();
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
        $bulan = $this->request->getGet('bulan'); 
        $tahun = $this->request->getGet('tahun'); 

        $db = \Config\Database::connect();
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

        return view('admin/cetak/v_cetak_stok_bulanan', $data);
    }

    // ==========================================
    // 4. PROSES CETAK REKAP PERMINTAAN RUANGAN
    // ==========================================
    public function cetak_rekap_permintaan() {
        $bulan = $this->request->getGet('bulan');
        $tahun = $this->request->getGet('tahun');

        $db = \Config\Database::connect();
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

    public function pilih_permintaan()
    {
        $db = \Config\Database::connect();
        $data['daftar_permintaan'] = $db->table('t_permohonan')
            ->whereIn('status', ['selesai', 'acc'])
            ->orderBy('id_permohonan', 'DESC')
            ->get()->getResultArray();

        return view('admin/v_pilih_permintaan', $data); 
    }

    public function form_berita_acara($id_permohonan)
    {
        $db = \Config\Database::connect();
        $permintaan = $db->table('t_permohonan')
            ->where('id_permohonan', $id_permohonan)
            ->get()->getRowArray();

        if (!$permintaan) {
            return redirect()->to(base_url('admin/laporan'))->with('error', 'Data tidak ditemukan.');
        }

        $data = [
            'id_permohonan'    => $id_permohonan,
            'no_permintaan'    => $permintaan['no_permintaan'],
            'ruangan'          => $permintaan['ruangan'],
            'penanggung_jawab' => $permintaan['penanggung_jawab']
        ];

        return view('admin/v_form_berita_acara', $data);
    }
}
