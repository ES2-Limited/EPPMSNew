<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory, HasUlid;

    protected $fillable = [
        'ulid',
        'email',
        'name',
        'website',
        'created_by_id',
        'updated_by_id'
    ];


}
