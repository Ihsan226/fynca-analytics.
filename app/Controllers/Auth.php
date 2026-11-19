<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        // Inisialisasi model
        $this->userModel = new UserModel();
    }

    // --- BAGIAN LOGIN ---
    public function index()
    {
        // Jika sudah login, tendang ke dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        return view('auth/login');
    }

    public function loginProcess()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $this->userModel->where('username', $username)->first();

        if ($user) {
            // Cek kecocokan password hash
            if (password_verify($password, $user['password'])) {
                // Set data session
                $sessionData = [
                    'id_user'      => $user['id_user'],
                    'id_organization' => $user['id_organization'] ?? null,
                    'nama_lengkap' => $user['nama_lengkap'],
                    'username'     => $user['username'],
                    'role'         => $user['role'],
                    'avatar'       => $user['avatar'] ?? null,
                    'isLoggedIn'   => true
                ];
                session()->set($sessionData);
                
                log_activity('Login', 'Berhasil login ke dalam sistem', 'auth');
                
                return redirect()->to('/dashboard');
            } else {
                return redirect()->back()->with('error', 'Password salah!');
            }
        } else {
            return redirect()->back()->with('error', 'Username tidak ditemukan!');
        }
    }

    // --- BAGIAN LOGOUT ---
    public function logout()
    {
        log_activity('Logout', 'Keluar dari sistem', 'auth');
        
        session()->destroy();
        return redirect()->to('/auth');
    }
}