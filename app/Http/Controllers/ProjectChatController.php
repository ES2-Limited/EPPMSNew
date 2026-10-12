<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectChatController extends Controller
{

    public function index($ulid)
    {
        $messages = ProjectChat::where('project_ulid', $ulid)->orderBy('created_at')->get();
        return view('projects.chat', compact('project', 'messages'));
    }

    public function sendMessage(Request $request, $ulid)
    {
        $request->validate([
            'message' => 'required|string|min:1|max:1000',
        ]);

        $project = Project::where('ulid', $ulid)->firstOrFail();

        ProjectChat::create([
            'project_ulid' => $project->ulid,
            'sender_id' => Auth::id(),
            'message' => $request->message,
        ]);

        return redirect()->back()->with('chat-success', 'Message sent!');
    }

    public function fetchMessages($ulid)
    {
        $messages = ProjectChat::where('project_ulid', $ulid)->orderBy('created_at')->get();
        return response()->json($messages);

    }
}
