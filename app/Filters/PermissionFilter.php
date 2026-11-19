<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class PermissionFilter implements FilterInterface
{
    private const ROLE_PERMISSIONS = [
        'admin' => ['*'],
        'owner' => [
            'payroll.approve',
            'payroll.reject',
            'analytics.view',
        ],
        'akuntansi' => [
            'payroll.generate',
            'payroll.submit',
            'accounting.journal.create',
            'accounting.journal.post',
            'accounting.period.close',
            'accounting.journal.reverse',
        ],
        'bendahara' => [
            'treasury.payment.request',
            'treasury.payment.execute',
            'treasury.reimbursement.approve',
            'accounting.journal.create',
        ],
        'staff' => [
            'payroll.view',
            'analytics.view',
        ],
        'analyst' => [
            'analytics.view',
        ],
    ];

    public function before(RequestInterface $request, $arguments = null)
    {
        $permission = $arguments[0] ?? null;
        $role = session()->get('role');
        $permissions = self::ROLE_PERMISSIONS[$role] ?? [];

        if ($permission !== null && !in_array('*', $permissions, true) && !in_array($permission, $permissions, true)) {
            if ($request->isAJAX()) {
                return service('response')
                    ->setJSON([
                        'status' => 'error',
                        'message' => 'Forbidden: permission tidak mencukupi.',
                    ])
                    ->setStatusCode(403);
            }

            return redirect()->back()->with('error', 'Akses ditolak. Permission tidak mencukupi.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
