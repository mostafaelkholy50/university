<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class term_one_payments extends Model
{
    /** @use HasFactory<\Database\Factories\TermOnePaymentsFactory> */
    use HasFactory;
    protected $fillable = [
        'user_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
