<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Fungsi untuk menampilkan halaman laporan keuangan
     */
    public function index()
    {
        $user = Auth::user();
        $years = Transaction::selectRaw('YEAR(created_at) as Tahun')->distinct()->get();
        // $months = Transaction::selectRaw('MONTH(created_at) as Bulan')->distinct()->get();

        return view('admin.report', compact('user', 'years'));
    }

    /**
     * Fungsi untuk mencetak laporan dengan konversi ke pdf
     */
    public function print(Request $request)
    {
        $monthInput = $request->input('bulan');
        $yearInput = $request->input('tahun');
        $dateObj = DateTime::createFromFormat('!m', $monthInput);
        $month = $dateObj->format('F');
        $revenue = Transaction::whereMonth('created_at', $monthInput)
            ->whereYear('created_at', $yearInput)->sum('total');
        $transactionsCount = Transaction::whereMonth('created_at', $monthInput)
            ->whereYear('created_at', $yearInput)->count();
        $pdf = Pdf::loadview('admin.report_pdf', compact('monthInput', 'yearInput', 'revenue', 'transactionsCount'));
        // return $pdf->download('laporan-keuangan-' . $bulan . '-' . $tahun . '.pdf');

        return $pdf->stream();
    }
}
