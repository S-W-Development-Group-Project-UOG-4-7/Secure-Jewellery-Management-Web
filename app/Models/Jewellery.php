<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jewellery extends Model
{
    use HasFactory;

    protected $table = 'jewellery';

    protected $fillable = [
        'name',
        'type',
        'metal',
        'weight',
        'value',
        'status',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'value' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function verificationItems()
    {
        return $this->hasMany(LockerVerificationItem::class, 'jewellery_id');
    }
}
