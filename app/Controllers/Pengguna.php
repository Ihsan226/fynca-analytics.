<?php
namespace App\Controllers;

use App\Models\UserModel;

class Pengguna extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen Pengguna',
            'users' => $this->userModel->findAll()
        ];
        return view('pengguna/index', $data);
    }

    public function store()
    {
        $username = $this->request->getPost('username');
        
        // Cek username duplikat
        if ($this->userModel->where('username', $username)->first()) {
            return redirect()->back()->with('error', 'Username sudah digunakan!');
        }

        $this->userModel->save([
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'username'     => $username,
            'password'     => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'         => $this->request->getPost('role') ?? 'admin'
        ]);

        return redirect()->to('/pengguna')->with('success', 'User berhasil ditambahkan.');
    }

    public function update($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/pengguna')->with('error', 'Data user tidak ditemukan.');
        }

        $username = $this->request->getPost('username');
        
        // Cek username duplikat selain punya user ini sendiri
        $cekUsername = $this->userModel->where('username', $username)->where('id_user !=', $id)->first();
        if ($cekUsername) {
            return redirect()->back()->with('error', 'Username sudah digunakan oleh user lain!');
        }

        $dataUpdate = [
            'id_user'      => $id,
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'username'     => $username,
            'role'         => $this->request->getPost('role') ?? 'admin'
        ];

        // Update password hanya jika diisi
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $dataUpdate['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->userModel->save($dataUpdate);

        return redirect()->to('/pengguna')->with('success', 'Data user berhasil diperbarui.');
    }

    public function delete($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/pengguna')->with('error', 'Data user tidak ditemukan.');
        }

        // Jangan izinkan user menghapus dirinya sendiri
        if ($id == session()->get('id_user')) {
            return redirect()->to('/pengguna')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri saat sedang login.');
        }

        $this->userModel->delete($id);

        return redirect()->to('/pengguna')->with('success', 'User berhasil dihapus.');
    }
}
