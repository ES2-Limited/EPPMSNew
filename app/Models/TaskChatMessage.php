<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskChatMessage extends Model
{
    use HasFactory, HasUlid;

    protected $table = 'tasks_chat_messages';

    protected $fillable = ['task_ulid', 'sender_id', 'message', 'is_read'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_ulid', 'ulid');
    }
}
