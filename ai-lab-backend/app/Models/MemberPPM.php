<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberPPM extends Model
{
    protected $table = 'member_ppm';

    protected $fillable = [
        'member_id',
        'title',
        'year',
        'description'
    ];

    public function member() {
        return $this->belongsTo(Member::class);
    }
}
