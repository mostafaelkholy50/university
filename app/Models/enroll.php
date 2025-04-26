<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class enroll extends Model
{
    /** @use HasFactory<\Database\Factories\EnrollFactory> */
    use HasFactory;
    protected $fillable = [
        'user_id',
        'course_id',
        'payment_status',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function course()
    {
        return $this->belongsTo(courses::class, 'course_id');
    }
}
