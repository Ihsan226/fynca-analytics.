<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $role = session()->get('role');
        
        // If no arguments provided, just pass
        if (empty($arguments)) {
            return;
        }

        // Check if user role is in the allowed arguments
        if (!in_array($role, $arguments)) {
            // If it's an AJAX request, return JSON response
            if ($request->isAJAX()) {
                return service('response')
                    ->setJSON(['status' => 'error', 'message' => 'Forbidden: You do not have permission to access this resource.'])
                    ->setStatusCode(403);
            }
            
            // Otherwise, redirect with error
            return redirect()->back()->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk tindakan tersebut.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
