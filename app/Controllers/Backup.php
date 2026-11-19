<?php

namespace App\Controllers;

class Backup extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Backup & Restore Database'
        ];
        return view('backup/index', $data);
    }

    public function download()
    {
        $db = \Config\Database::connect();
        $hostname = $db->hostname;
        $username = $db->username;
        $password = $db->password;
        $database = $db->database;

        $filename = 'backup_' . $database . '_' . date('Y-m-d_H-i-s') . '.sql';
        $filepath = WRITEPATH . 'uploads/' . $filename;

        // Command for mysqldump
        $command = "mysqldump --user={$username} --password={$password} --host={$hostname} {$database} > {$filepath}";
        
        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            return redirect()->back()->with('error', 'Gagal melakukan backup database. Pastikan mysqldump tersedia di server.');
        }

        return $this->response->download($filepath, null)->setFileName($filename);
    }
}
