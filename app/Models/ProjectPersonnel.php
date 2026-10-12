<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectPersonnel extends Model
{
    use HasFactory, HasUlid;

    protected $fillable = [
        'project_ulid',
        'personnel_id',
        'department_id',
        'created_by_id',
        'role_id'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_ulid', 'ulid');
    }

    public function personnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
