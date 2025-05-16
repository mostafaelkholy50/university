<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Doctor extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\DoctorFactory> */
    use HasFactory , HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'specialization',
        'experience_years',
        'image',
    ];
    public function courses()
    {
        return $this->hasMany(courses::class);
    }
    public function lectures()
    {
        return $this->hasMany(Lecture::class);
    }
}
