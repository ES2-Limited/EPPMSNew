<?php

namespace App\Models;

use App\Enums\Status;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, HasUlid, SoftDeletes;

    protected $casts = [
        'status' => Status::class,
    ];


    protected $fillable = [
        'milestone_id',
        'name',
        'description',
        'start_date',
        'due_date',
        'cost',
        'gap',
        'deleted_by',
    ];

    public function milestone(): BelongsTo
    {
        return $this->belongsTo(Milestone::class, 'milestone_id', 'id');
    }

    public function mark_as_done()
    {
        $this->status = 'done';
        $this->save();

        $this->milestone->check_and_mark_as_done();
    }

    public function images()
    {
        return $this->hasMany(TaskImage::class, 'task_ulid', 'ulid');
    }
}
