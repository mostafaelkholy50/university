<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class schedule extends Model
{
    /** @use HasFactory<\Database\Factories\ScheduleFactory> */
    use HasFactory;
    protected $fillable = [
        'image',
        'specialty',
        'years',
    ];
    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }
}
