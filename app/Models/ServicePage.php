<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicePage extends Model
{
    protected $fillable = [
        'brand_strategy',
        'creative',
        'packaging',
        'social_media',
        'digital_media',
        'seo_website_ecommerce',
    ];
}
