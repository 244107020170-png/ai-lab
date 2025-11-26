<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'action_type',
        'target_table',
        'target_id',
        'note',
    ];

    public $timestamps = false; // ⚠ WAJIB untuk menghindari error
}
