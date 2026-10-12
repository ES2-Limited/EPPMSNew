<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectChat extends Model
{
    use HasFactory, HasUlid;

    protected $table = 'project_chats';

    protected $fillable = ['project_ulid', 'sender_id', 'message', 'is_read'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'task_ulid', 'ulid');
    }
}
