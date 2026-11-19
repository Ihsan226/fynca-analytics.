<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\LogAktivitasModel;

class Profile extends BaseController
{
    protected $userModel;
    protected $logAktivitasModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->logAktivitasModel = new LogAktivitasModel();
    }

    public function index()
    {
        $userId = session()->get('id_user');
        $user = $this->userModel->find($userId);

        if (!$user) {
            // Jika user tidak ditemukan di database (mungkin dihapus atau session nyangkut)
            session()->destroy();
            return redirect()->to('/auth')->with('error', 'Sesi tidak valid atau pengguna tidak ditemukan. Silakan login kembali.');
        }

        $data = [
            'title' => 'Profil Saya - Fynca Analytics',
            'user'  => $user,
        ];

        return view('profile/index', $data);
    }

    public function update()
    {
        $userId = session()->get('id_user');
        
        $rules = [
            'nama_lengkap' => 'required|min_length[3]',
            'username'     => "required|min_length[4]|is_unique[users.username,id_user,{$userId}]",
        ];

        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $rules['password'] = 'min_length[6]';
        }

        $avatarFile = $this->request->getFile('avatar');
        if ($avatarFile && $avatarFile->isValid()) {
            $rules['avatar'] = 'max_size[avatar,2048]|is_image[avatar]|mime_in[avatar,image/jpg,image/jpeg,image/png]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $updateData = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'username'     => $this->request->getPost('username'),
        ];

        if (!empty($password)) {
            $updateData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if ($avatarFile && $avatarFile->isValid() && !$avatarFile->hasMoved()) {
            $newName = $avatarFile->getRandomName();
            $avatarFile->move(FCPATH . 'uploads/avatars', $newName);
            
            // Delete old avatar if exists
            $currentUser = $this->userModel->find($userId);
            if (!empty($currentUser['avatar']) && file_exists(FCPATH . 'uploads/avatars/' . $currentUser['avatar'])) {
                unlink(FCPATH . 'uploads/avatars/' . $currentUser['avatar']);
            }
            
            $updateData['avatar'] = $newName;
        }

        $this->userModel->update($userId, $updateData);

        // Update session
        $sessionUpdates = [
            'nama_lengkap' => $updateData['nama_lengkap'],
            'username'     => $updateData['username'],
        ];
        if (isset($updateData['avatar'])) {
            $sessionUpdates['avatar'] = $updateData['avatar'];
        }
        session()->set($sessionUpdates);

        // Log Aktivitas
        log_activity('Update Profil', 'Pengguna memperbarui informasi profilnya.', 'AUTH');

        return redirect()->to('/profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
