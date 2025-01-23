<?php

namespace App\Filters;

use App\Models\PeriodsModels;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class ClosePeriodFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $month = date('m');
        $year = date('Y');

        $periodsmodel = new PeriodsModels();
        $periods = $periodsmodel->where('periods_start', $month)
                                ->where('periods_end', $year)
                                ->first();
        $isAdmin = session()->get('role') === 'admin';

        if ($periods && $periods['is_closed'] && !$isAdmin) {
            return redirect()->back()->with('error', 'Periode ini telah ditutup. Anda tidak dapat mengedit atau menghapus data.');
        }

    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
