<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VolunteerRegistration extends Model
{
    protected $table = 'volunteer_registrations';

    protected $fillable = [
        'full_name',
        'nickname',
        'study_program',
        'semester',
        'email',
        'phone',
        'areas',
        'skills',
        'motivation',
        'availability'
    ];

    protected $casts = [
        'areas' => 'array'
    ];
}
