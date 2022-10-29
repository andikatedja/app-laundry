<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ComplaintSuggestion;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Facades\Auth;

class ComplaintSuggestionController extends Controller
{
    /**
     * View complaint suggestion page
     *
     * @return View
     */
    public function index(): View
    {
        $user = Auth::user();

        $suggestions = ComplaintSuggestion::where([
            'type' => 1,
            'reply' => null,
        ])->get();

        $complaints = ComplaintSuggestion::where([
            'type' => 2,
            'reply' => null,
        ])->get();

        $count = ComplaintSuggestion::where('reply', '')->count();

        return view('admin.complaint_suggestion', compact('user', 'suggestions', 'complaints', 'count'));
    }

    /**
     * Get complaint suggestion by id
     *
     * @param ComplaintSuggestion $complaintSuggestion
     * @return JsonResponse
     */
    public function show(ComplaintSuggestion $complaintSuggestion): JsonResponse
    {
        return response()->json($complaintSuggestion);
    }

    /**
     * Send complaint suggestion reply
     *
     * @param ComplaintSuggestion $complaintSuggestion
     * @param Request $request
     * @return JsonResponse
     */
    public function update(ComplaintSuggestion $complaintSuggestion, Request $request): JsonResponse
    {
        $complaintSuggestion->reply = $request->input('balasan');
        $complaintSuggestion->save();

        return response()->json();
    }
}
