<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractorPersonnel extends Model
{
    use HasFactory, HasUlid, SoftDeletes;

    protected $fillable = [
        'user_id',
        'created_by_id',
        'contractor_id',
        'address',
        'phone'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contractor()
    {
        return $this->belongsTo(Contractor::class, 'contractor_id', 'id');
    }


}
