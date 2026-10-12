<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Directorate extends Model
{
    use HasFactory, HasUlid, SoftDeletes;

    protected $fillable = [
        'ulid',
        'code',
        'name',
        'function',
        'created_by_id'
    ];

    public function department(): HasOne
    {
        return $this->hasOne(Department::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id', 'id');
    }

}
