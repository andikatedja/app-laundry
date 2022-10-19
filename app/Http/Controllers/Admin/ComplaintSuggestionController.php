<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ComplaintSuggestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintSuggestionController extends Controller
{
    /**
     * Fungsi untuk menampilkan halaman saran komplain
     */
    public function index()
    {
        $user = Auth::user();
        $suggestions = ComplaintSuggestion::where([
            'type' => 1,
            'reply' => ''
        ])->get();
        $complaints = ComplaintSuggestion::where([
            'type' => 2,
            'reply' => ''
        ])->get();
        $count = ComplaintSuggestion::where('reply', '')->count();
        return view('admin.complaint_suggestion', compact('user', 'suggestions', 'complaints', 'count'));
    }

    /**
     * Fungsi untuk mengambil isi saran komplain melalui ajax
     */
    public function show(ComplaintSuggestion $complaintSuggestion)
    {
        return response()->json($complaintSuggestion);
    }

    /**
     * Fungsi untuk mengirim balasan dari saran komplain
     */
    public function update(ComplaintSuggestion $complaintSuggestion, Request $request)
    {
        $complaintSuggestion->reply = $request->input('balasan');
        $complaintSuggestion->save();
    }
}
