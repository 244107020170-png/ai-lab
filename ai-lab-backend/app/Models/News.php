<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';

    protected $fillable = [
        'title',
        'category',
        'date',
        'image_thumb',
        'image_detail',
        'excerpt',
        'content',
        'quote',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // Ambil data yang status = main untuk web profile
    public static function publicNews()
    {
        return self::where('status', 'main')
            ->orderBy('date', 'desc')
            ->get();
    }
}
