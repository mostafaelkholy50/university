<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class episodes extends Model
{
    /** @use HasFactory<\Database\Factories\EpisodesFactory> */
    use HasFactory;
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'Video',
    ];
    public function course()
    {
        return $this->belongsTo(courses::class, 'course_id');
    }
}
