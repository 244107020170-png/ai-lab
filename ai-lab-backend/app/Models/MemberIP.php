<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberIP extends Model
{
    use HasFactory;

    protected $table = 'member_ips'; // ← WAJIB

    protected $fillable = [
        'member_id',
        'title',
        'year',
        'registration_number'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}

