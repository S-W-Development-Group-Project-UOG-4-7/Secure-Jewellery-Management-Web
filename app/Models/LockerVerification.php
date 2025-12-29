<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LockerVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'locker_id',
        'verified_by',
        'before_notes',
        'after_notes',
        'before_image',
        'after_image',
        'status',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function locker()
    {
        return $this->belongsTo(Locker::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function items()
    {
        return $this->hasMany(LockerVerificationItem::class, 'verification_id');
    }

    public function jewelleryItems()
    {
        return $this->belongsToMany(Jewellery::class, 'locker_verification_items', 'verification_id', 'jewellery_id');
    }
}
