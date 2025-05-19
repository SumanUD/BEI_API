<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
protected $fillable = [
    'brand_name',
    'brand_logo',
    'banner_images',
    'youtube_link',
    'below_video_text',
    'image_gallery',
    'video_gallery',
];

protected $casts = [
    'banner_images' => 'array',
    'youtube_link' => 'array',
    'image_gallery' => 'array',
    'video_gallery' => 'array',
];


}
