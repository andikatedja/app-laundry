<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\ComplaintSuggestion;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintSuggestionController extends Controller
{
    /**
     * method to show complaint suggestion view
     *
     * @return View
     */
    public function index(): View
    {
        $user = Auth::user();
        $complaintSuggestions = ComplaintSuggestion::where('user_id', $user->id)->get();

        return view('member.complaint_suggestions', compact('user', 'complaintSuggestions'));
    }

    /**
     * Method to process complaint suggestion
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'isi_sarankomplain' => 'required'
        ]);

        $user = Auth::user();

        $complaintSuggestion = new ComplaintSuggestion([
            'body' => $request->input('isi_sarankomplain'),
            'type' => $request->input('tipe'),
            'user_id' => $user->id,
            'reply' => ''
        ]);

        $complaintSuggestion->save();

        return redirect('member/complaint-suggestions')->with('success', 'Saran/komplain berhasil dikirim!');
    }
}
