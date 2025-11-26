<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'role',
        'photo',
        'expertise',
        'description',
        'linkedin',
        'scholar',
        'researchgate',
        'orcid',
    ];

    // Tambah relasi DI DALAM CLASS
    public function backgrounds()
    {
        return $this->hasMany(MemberBackground::class);
    }

    public function publications()
    {
        return $this->hasMany(MemberPublication::class);
    }
    public function research()
{
    return $this->hasMany(MemberResearch::class);
}

public function ips()
{
    return $this->hasMany(MemberIP::class);
}

public function ppm()
{
    return $this->hasMany(MemberPPM::class);
}

public function activities()
{
    return $this->hasMany(MemberActivity::class);
}

}

