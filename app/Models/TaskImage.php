<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskImage extends Model
{
    use HasFactory, HasUlid, SoftDeletes;

    protected $fillable = ['task_ulid', 'uploader_id', 'name'];

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_ulid', 'ulid');
    }

}
