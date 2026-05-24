<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => ['required', 'string', 'in:Assessment,Roadmap,Dashboard,Login,Resources,Account deletion,Malpractice report,Other'],
            'priority' => ['required', 'string', 'in:Low,Medium,High'],
            'subject' => ['required', 'string', 'max:120'],
            'message' => ['required', 'string', 'min:10', 'max:1200'],
        ]);

        SupportTicket::create([
            'user_id' => $request->user()->id,
            'category' => $validated['category'],
            'priority' => $validated['priority'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'Open',
        ]);

        return back()->with('status', 'Your issue has been sent to the admin team.');
    }
}
