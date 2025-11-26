<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberPublication extends Model
{
    protected $table = 'member_publications';

    protected $fillable = [
        'member_id',
        'title',
        'publisher',
        'year',
        'link'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
