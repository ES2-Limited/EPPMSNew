<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MilestoneChatMessage extends Model
{
    use HasFactory, HasUlid, SoftDeletes;

    protected $table = 'milestones_chat_messages';


    protected $fillable = ['milestone_ulid', 'sender_id', 'message', 'is_read'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function milestone()
    {
        return $this->belongsTo(Milestone::class, 'milestone_ulid', 'ulid');
    }
}
