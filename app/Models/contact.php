<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class contact extends Model
{
    /** @use HasFactory<\Database\Factories\ContactFactory> */
    use HasFactory;
    protected $fillable = ['user_id','frist_name','last_name', 'email', 'message'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
