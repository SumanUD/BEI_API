<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    protected $fillable = [
        'desktop_video', 'mobile_video', 'description', 'right_image', 'team_members',
    ];

    protected $casts = [
        'team_members' => 'array',
    ];

}
