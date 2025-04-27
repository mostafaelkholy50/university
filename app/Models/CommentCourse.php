<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentCourse extends Model
{
    /** @use HasFactory<\Database\Factories\CommentCourseFactory> */
    use HasFactory;
    protected $fillable = [
        'user_id',
        'course_id',
        'comment',
        'rate',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function course()
    {
        return $this->belongsTo(Courses::class, 'course_id');
    }
    
}
