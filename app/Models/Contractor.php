<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contractor extends Model
{
    use HasFactory, HasUlid, SoftDeletes;

    protected $fillable = [
        'phone',
        'website',
        'website',
        'user_id',
        'logo',
        'created_by_id',
        'firm_type_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contractor()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id', 'id');
    }


    public function firm_type()
    {
        return $this->belongsTo(FirmType::class, 'firm_type_id', 'id');
    }
}
