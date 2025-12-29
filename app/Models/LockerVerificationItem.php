<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LockerVerificationItem extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'verification_id',
        'jewellery_id',
    ];

    public function verification()
    {
        return $this->belongsTo(LockerVerification::class, 'verification_id');
    }

    public function jewellery()
    {
        return $this->belongsTo(Jewellery::class, 'jewellery_id');
    }
}
