<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Office extends Model
{
    use HasFactory, HasUlid, SoftDeletes;


    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'office_type_id',
        'department_id',
        'address',
        'email',
        'phone',
        'lga_id',
        'created_by_id',
        'updated_by_id',
        'deleted_by'
    ];

    public function lga() : BelongsTo
    {
        return $this->belongsTo(Lga::class);
    }

    public function office_type() : BelongsTo
    {
        return $this->belongsTo(OfficeType::class);
    }

    public function department() : BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id', 'id');
    }


}
