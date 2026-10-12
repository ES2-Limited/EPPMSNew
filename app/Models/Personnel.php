<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personnel extends Model
{
    use HasFactory, HasUlid;

    protected $fillable = [
        'user_id',
        'office_id',
        'created_by_id',
        // 'unit_id',
        'department_id',
        'directorate_id',
        'address',
        'phone',
        'role_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    // public function unit()
    // {
    //     return $this->belongsTo(Unit::class);
    // }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id', 'id');
    }


    public function project_personnel()
    {
        return $this->hasMany(ProjectPersonnel::class);
    }



}
