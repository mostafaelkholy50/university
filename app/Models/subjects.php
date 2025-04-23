<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subjects extends Model
{
    /** @use HasFactory<\Database\Factories\SubjectsFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'specialty',
        'term',
        'years',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function grades()
    {
        return $this->hasMany(grades::class, 'subject_id'); // واضح وصريح
    }
}
