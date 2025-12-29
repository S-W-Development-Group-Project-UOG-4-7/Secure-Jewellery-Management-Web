<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LockerVerification extends Model
{
    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'locker_id',
        'stage',
        'verified_by',
        'notes',
    ];

    /**
     * A verification has many verification items
     */
    public function items()
    {
        return $this->hasMany(LockerVerificationItem::class);
    }

    /**
     * A verification belongs to a locker
     */
    public function locker()
    {
        return $this->belongsTo(Locker::class);
    }

    /**
     * The user who verified the locker
     */
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
