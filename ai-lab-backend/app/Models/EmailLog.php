<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    protected $fillable = [
        'to_email',
        'from_email',
        'subject',
        'body',
        'related_table',
        'related_id',
        'status',
        'sent_at',
    ];
}