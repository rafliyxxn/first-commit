<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function index()
    {
        // Jika sudah login, lempar ke dashboard sesuai role
        if (session()->get('logged_in')) {
            return $this->redirectBerdasarkanRole(session()->get('role'));
        }
        return view('v_login');
    }

    public function login_process()
    {
        $userModel = new UserModel();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $userModel->where('username', $username)->first();

        if ($user) {
            // Cek password (bisa pakai bypass 'admin123' ATAU password asli terenkripsi)
            if ($password == "admin123" || password_verify($password, $user['password'])) {
                session()->set([
                    'id_user'   => $user['id_user'],
                    'nama'      => $user['nama_pegawai'],
                    'role'      => $user['role'],
                    'logged_in' => TRUE
                ]);

                // Arahkan menggunakan fungsi bantuan di bawah
                return $this->redirectBerdasarkanRole($user['role']);
            }
            return redirect()->back()->with('error', 'Password salah.');
        }
        return redirect()->back()->with('error', 'Username tidak ditemukan.');
    }

    public function logout()
    {
        // Menghapus seluruh session
        session()->destroy();
        
        // Mengarahkan kembali ke halaman login (root)
        return redirect()->to(base_url('/'))->with('success', 'Anda telah berhasil logout.');
    }

    // --- FUNGSI BANTUAN UNTUK MENGATUR JALAN (REDIRECT) ---
    private function redirectBerdasarkanRole($role)
    {
        // Hanya menyisakan peran Admin dan User biasa
        if ($role == 'admin_tik' || $role == 'admin') {
            return redirect()->to(base_url('admin'));
        } else {
            return redirect()->to(base_url('user'));
        }
    }
}