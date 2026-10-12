<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasUlid;

    protected $fillable = ['key', 'name', 'signature', 'active'];

}
