<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';
    protected $fillable = [
        'title','category','date',
        'image_thumb','image_detail',
        'excerpt','content','quote'
    ];

    protected $casts = [
        'date' => 'date',
    ];
}
