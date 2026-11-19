<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('isLoggedIn')) {
            if ($request->isAJAX()) {
                return service('response')
                    ->setJSON([
                        'status' => 'error',
                        'message' => 'Sesi login telah berakhir.'
                    ])
                    ->setStatusCode(401);
            }

            return redirect()->to('/auth')->with('error', 'Silakan login terlebih dahulu.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu diisi untuk saat ini
    }
}