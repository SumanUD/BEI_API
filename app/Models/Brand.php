<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model
{
    use HasFactory;

    protected $table = 'brands';

    protected $fillable = [
        'brand_name',
        'brand_logo',
        'banner_images',
        'youtube_link',
        'below_video_text',
        'image_gallery',
        'video_gallery',
        'video_gallery_video',
    ];

protected $casts = [
    'banner_images' => 'array',
    'youtube_link' => 'array',
    'image_gallery' => 'array',
    'video_gallery' => 'array',
    'video_gallery_video' => 'array',
];


    // Optional: Accessors for logo, banner, or gallery images (public URL)
    public function getBrandLogoUrlAttribute()
    {
        return $this->brand_logo ? asset('storage/' . $this->brand_logo) : null;
    }

    public function getBannerImagesUrlAttribute()
    {
        return array_map(function ($path) {
            return asset('storage/' . $path);
        }, $this->banner_images ?? []);
    }

    public function getImageGalleryUrlsAttribute()
    {
        return array_map(function ($path) {
            return asset('storage/' . $path);
        }, $this->image_gallery ?? []);
    }
}
