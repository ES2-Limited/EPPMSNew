<?php

namespace App\Http\Controllers;

use App\Models\Milestone;
use App\Models\MilestoneChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MilestoneChatController extends Controller
{
    public function index($ulid)
    {
        $milestone = Milestone::where('ulid', $ulid)->firstOrFail();
        \Illuminate\Support\Facades\Gate::authorize('chat', $milestone);
        $messages = MilestoneChatMessage::where('milestone_ulid', $ulid)->orderBy('created_at')->get();
        return view('milestones.chat', compact('milestone', 'messages'));
    }

    public function sendMessage(Request $request, $ulid)
    {
        $request->validate([
            'message' => 'required|string|min:1|max:1000',
        ]);

        $milestone = Milestone::where('ulid', $ulid)->firstOrFail();
        \Illuminate\Support\Facades\Gate::authorize('chat', $milestone);

        MilestoneChatMessage::create([
            'milestone_ulid' => $milestone->ulid,
            'sender_id' => Auth::id(),
            'message' => $request->message,
        ]);

        return redirect()->back()->with('chat-success', 'Message sent!');
    }

    public function fetchMessages($ulid)
    {
        $milestone = Milestone::where('ulid', $ulid)->firstOrFail();
        \Illuminate\Support\Facades\Gate::authorize('chat', $milestone);

        $messages = MilestoneChatMessage::where('milestone_ulid', $ulid)->orderBy('created_at')->get();
        return response()->json($messages);
    }


}
