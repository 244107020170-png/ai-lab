<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberActivity extends Model
{
    protected $table = 'member_activities';

    protected $fillable = [
        'member_id',
        'title',
        'year',
        'location'
    ];

    public function member() {
        return $this->belongsTo(Member::class);
    }
}
