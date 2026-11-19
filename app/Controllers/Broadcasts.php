<?php

namespace App\Controllers;

use App\Models\BroadcastModel;

class Broadcasts extends BaseController
{
    protected $broadcastModel;

    public function __construct()
    {
        $this->broadcastModel = new BroadcastModel();
    }

    public function index()
    {
        $data = [
            'title'      => 'Pengumuman Sistem',
            'broadcasts' => $this->broadcastModel->orderBy('created_at', 'DESC')->findAll()
        ];
        return view('broadcasts/index', $data);
    }

    public function store()
    {
        $rules = [
            'title'   => 'required|min_length[3]',
            'message' => 'required|min_length[10]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->broadcastModel->save([
            'title'   => $this->request->getPost('title'),
            'message' => $this->request->getPost('message'),
            'status'  => 'active'
        ]);

        return redirect()->to('/broadcasts')->with('success', 'Pengumuman berhasil dibuat.');
    }

    public function toggle($id)
    {
        $broadcast = $this->broadcastModel->find($id);
        if ($broadcast) {
            $newStatus = ($broadcast['status'] == 'active') ? 'inactive' : 'active';
            $this->broadcastModel->update($id, ['status' => $newStatus]);
            return redirect()->to('/broadcasts')->with('success', 'Status pengumuman berhasil diubah.');
        }
        return redirect()->to('/broadcasts')->with('error', 'Pengumuman tidak ditemukan.');
    }

    public function delete($id)
    {
        $this->broadcastModel->delete($id);
        return redirect()->to('/broadcasts')->with('success', 'Pengumuman berhasil dihapus.');
    }
}
