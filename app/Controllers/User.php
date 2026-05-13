<?php

namespace App\Controllers;

use App\Models\BarangModel;
use Dompdf\Dompdf;

class User extends BaseController
{
    protected $db;

    public function __construct()
    {
        // Inisialisasi database satu kali untuk semua fungsi
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        if (!session()->get('logged_in')) return redirect()->to('/');
        return view('user/v_dashboard', ['title' => 'Dashboard', 'nama' => session()->get('nama')]);
    }

    public function form_permohonan()
    {
        date_default_timezone_set('Asia/Jakarta');
        $barangModel = new BarangModel();
        $data = [
            'title'         => 'Form Permohonan',
            'barang'        => $barangModel->findAll(),
            'no_permintaan' => 'REQ-' . date('YmdHis'),
            'nama'          => session()->get('nama')
        ];
        return view('user/v_form_permohonan', $data);
    }

    public function kirim_permohonan()
    {
        // Ambil data header
        $no_permintaan = $this->request->getPost('no_permintaan');
        $ruangan       = $this->request->getPost('ruangan');
        $pj            = $this->request->getPost('penanggung_jawab');
        $no_wa         = $this->request->getPost('no_wa');

        // Ambil data barang
        $id_barangs = $this->request->getPost('id_barang');
        $jumlahs    = $this->request->getPost('jumlah');
        $satuans    = $this->request->getPost('satuan');

        if ($id_barangs) {
            foreach ($id_barangs as $key => $id_barang) {
                $this->db->table('t_permohonan')->insert([
                    'id_user'          => session()->get('id_user'), // PENTING: Harus ada agar Riwayat muncul
                    'no_permintaan'    => $no_permintaan,
                    'ruangan'          => $ruangan,
                    'penanggung_jawab' => $pj,
                    'no_wa'            => $no_wa,
                    'id_barang'        => $id_barang,
                    'jumlah'           => $jumlahs[$key],
                    'satuan'           => $satuans[$key],
                    'tgl_mohon'        => date('Y-m-d'),
                    'status'           => 'Pending'
                ]);
            }
            return redirect()->to(base_url('user/riwayat'))->with('success', 'Permohonan berhasil dikirim!');
        }

        return redirect()->back()->with('error', 'Pilih barang terlebih dahulu!');
    }

    public function riwayat()
    {
        $builder = $this->db->table('t_permohonan');
        $builder->select('t_permohonan.*, m_barang.nama_barang, m_barang.merk');
        $builder->join('m_barang', 'm_barang.id_barang = t_permohonan.id_barang');
        $builder->where('t_permohonan.id_user', session()->get('id_user')); // Mencari berdasarkan ID user yang login
        $builder->orderBy('tgl_mohon', 'DESC');

        $data = [
            'title'   => 'Riwayat Permohonan',
            'nama'    => session()->get('nama'),
            'riwayat' => $builder->get()->getResultArray()
        ];
        return view('user/v_riwayat', $data);
    }

    public function download_pdf($no_permintaan)
    {
        $builder = $this->db->table('t_permohonan');
        $builder->select('t_permohonan.*, m_barang.nama_barang, m_barang.merk, users.nama_pegawai');
        $builder->join('m_barang', 'm_barang.id_barang = t_permohonan.id_barang');
        $builder->join('users', 'users.id_user = t_permohonan.id_user');
        $builder->where('no_permintaan', $no_permintaan);
        $data_p = $builder->get()->getResultArray();

        if (empty($data_p)) return redirect()->to('user/riwayat');

        $dompdf = new Dompdf();
        $data = [
            'title'           => 'Cetak Permohonan - ' . $no_permintaan,
            'data_permohonan' => $data_p,
            'no_permintaan'   => $no_permintaan
        ];

        $html = view('user/v_cetak_pdf', $data);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        if (ob_get_length() > 0) ob_end_clean();
        return $dompdf->stream("Permohonan-" . $no_permintaan . ".pdf", ["Attachment" => 1]);
    }
}