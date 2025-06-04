<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'name',
        'organisation_name',
        'email',
        'phone_number',
        'website_or_social_link',
    ];
}
