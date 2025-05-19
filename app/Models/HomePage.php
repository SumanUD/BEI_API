<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomePage extends Model
{
    protected $fillable = [
        'slug', 'desktop_video', 'mobile_video', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
