<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class courses extends Model
{
    /** @use HasFactory<\Database\Factories\CoursesFactory> */
    use HasFactory;
    protected $fillable = [
        'doctor_id',
        'title',
        'description',
        'image',
        'category',
        'date',
    ];
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
    public function episodes()
    {
        return $this->hasMany(episodes::class, 'course_id');
    }
}
