<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LockerVerificationItem extends Model
{
    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'locker_verification_id',
        'jewellery_id',
        'result',
        'remarks',
        'proof_image',
    ];

    /**
     * Item belongs to a locker verification
     */
    public function verification()
    {
        return $this->belongsTo(LockerVerification::class, 'locker_verification_id');
    }

    /**
     * Item belongs to a jewellery item
     */
    public function jewellery()
    {
        return $this->belongsTo(Jewellery::class);
    }
}
