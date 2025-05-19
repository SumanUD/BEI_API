<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    protected $fillable = [
    'banner_video',
    'about_image',
    'about_bg_image',
    'team_members',
    'email',
    'linkedin',
    'about_gallery',
];

protected $casts = [
    'team_members' => 'array',
    'about_gallery' => 'array',
];

}
