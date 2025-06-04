<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'news_title', 'thumbnail_picture', 'news_link',
    ];
}
