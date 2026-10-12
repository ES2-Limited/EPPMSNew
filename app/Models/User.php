<?php

namespace App\Models;

use App\Mail\VerifyEmail;
use App\Traits\HasUlid;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Str;

class User extends Authenticatable
// implements  MustVerifyEmail
{
    use HasFactory, Notifiable, HasUlid, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ulid',
        'name',
        'first_name',
        'last_name',
        'other_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function generateVerificationToken()
    {
        $this->verification_token = Str::random(20);
        $this->save();
    }

    public function contractor()
    {
        return $this->hasOne(Contractor::class)->where('firm_type_id', 1);
    }


    public function consultant()
    {
        return $this->hasOne(Contractor::class)->where('firm_type_id', 2);
    }


    public function personnel()
    {
        return $this->hasOne(Personnel::class);
    }

    protected function fullname(): Attribute
    {
        return Attribute::make(
            get: fn() => ($this->name != '') ? trim($this->name) : trim("{$this->first_name} {$this->last_name}")
        );
    }
}
