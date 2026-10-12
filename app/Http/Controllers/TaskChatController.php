<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskChatController extends Controller
{
    public function index($ulid)
    {
        $messages = TaskChatMessage::where('task_ulid', $ulid)->orderBy('created_at')->get();
        return view('tasks.chat', compact('task', 'messages'));
    }

    public function sendMessage(Request $request, $ulid)
    {
        $request->validate([
            'message' => 'required|string|min:1|max:1000',
        ]);

        $task = Task::where('ulid', $ulid)->firstOrFail();

        TaskChatMessage::create([
            'task_ulid' => $task->ulid,
            'sender_id' => Auth::id(),
            'message' => $request->message,
        ]);

        return redirect()->back()->with('chat-success', 'Message sent!');
    }

    public function fetchMessages($ulid)
    {
        $messages = TaskChatMessage::where('task_ulid', $ulid)->orderBy('created_at')->get();
        return response()->json($messages);
    }

}
