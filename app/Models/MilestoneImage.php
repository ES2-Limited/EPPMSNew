<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MilestoneImage extends Model
{
    use HasFactory, HasUlid;

    protected $fillable = ['milestone_ulid', 'uploader_id', 'name'];
}
