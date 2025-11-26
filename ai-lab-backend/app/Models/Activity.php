<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'title',
        'label',
        'short_description',
        'full_description',
        'published_at',
        'thumbnail_image',
        'banner_image',
    ];
}
