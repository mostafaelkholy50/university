<?php

namespace App\Models;

use App\Models\subjects;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class grades extends Model
{
    /** @use HasFactory<\Database\Factories\GradesFactory> */
    use HasFactory;
    protected $fillable = [
        'user_id',
        'subject_id',
        'grade',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function subject()
    {
        return $this->belongsTo(subjects::class, 'subject_id');
    }
}
