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
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): View
    {
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        $complaintSuggestions = ComplaintSuggestion::where('user_id', $user->id)->get();

        return view('member.complaint_suggestions', compact('user', 'complaintSuggestions'));
    }

    /**
     * Method to process complaint suggestion
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'body' => ['required'],
            'type' => ['required'],
        ]);

        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        $complaintSuggestion = new ComplaintSuggestion([
            'body'    => $request->input('body'),
            'type'    => $request->input('type'),
            'user_id' => $user->id,
            'reply'   => '',
        ]);

        $complaintSuggestion->save();

        return redirect()->route('member.complaints.index')
            ->with('success', 'Saran/komplain berhasil dikirim!');
    }
}
