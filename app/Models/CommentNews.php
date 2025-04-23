<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentNews extends Model
{
    /** @use HasFactory<\Database\Factories\CommentNewsFactory> */
    use HasFactory;
    protected $fillable = ['news_id', 'user_id', 'comment', 'rate'];
    public function news()
    {
        return $this->belongsTo(News::class, 'news_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
