<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabPermitRequest extends Model
{
    protected $table = 'lab_permit_requests';

    protected $fillable = [
        'user_id',
        'full_name',
        'study_program',
        'semester',
        'phone',
        'email',
        'reason',
        'status',
        'admin_id',
        'admin_notes'
    ];
}
