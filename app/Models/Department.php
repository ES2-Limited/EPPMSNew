<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, HasUlid, SoftDeletes;

    protected $fillable = [
        'ulid',
        'code',
        'name',
        'function',
        'directorate_id',
        'created_by_id'
    ];

    public function units(): HasOne
    {
        return $this->hasOne(Unit::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id', 'id');
    }

    public function directorate()
    {
        return $this->belongsTo(Directorate::class);
    }

}
