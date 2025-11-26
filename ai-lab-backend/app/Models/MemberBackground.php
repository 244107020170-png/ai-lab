<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberBackground extends Model
{
    protected $table = 'member_backgrounds';

    protected $fillable = [
        'member_id',
        'institute',
        'academic_title',
        'year',
        'degree'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
