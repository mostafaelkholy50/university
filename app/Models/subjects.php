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
        'section',
        'years',
    ];
}
