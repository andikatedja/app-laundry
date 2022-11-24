<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use DateTime;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use PDF;

class ReportController extends Controller
{
    /**
     * Show report page
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): View
    {
        $user = Auth::user();
        $years = Transaction::selectRaw('YEAR(created_at) as Tahun')->distinct()->get();
        // $months = Transaction::selectRaw('MONTH(created_at) as Bulan')->distinct()->get();

        return view('admin.report', compact('user', 'years'));
    }

    /**
     * Print report as pdf
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function print(Request $request): Response
    {
        $monthInput = $request->input('month');
        $yearInput = $request->input('year');
        $dateObj = DateTime::createFromFormat('!m', $monthInput);

        if ($dateObj) {
            $month = $dateObj->format('F');
        } else {
            abort(500);
        }

        $revenue = Transaction::whereMonth('created_at', $monthInput)
            ->whereYear('created_at', $yearInput)->sum('total');
        $transactionsCount = Transaction::whereMonth('created_at', $monthInput)
            ->whereYear('created_at', $yearInput)->count();
        $pdf = PDF::loadview(
            'admin.report_pdf',
            compact(
                'monthInput',
                'yearInput',
                'revenue',
                'transactionsCount',
            )
        );

        return $pdf->stream();
    }

    /**
     * Get month by year report
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMonth(Request $request): JsonResponse
    {
        $year = $request->input('year', now()->year);
        $month = Transaction::whereYear('created_at', $year)
            ->selectRaw('MONTH(created_at) as Bulan')
            ->distinct()
            ->get();

        return response()->json($month);
    }
}
