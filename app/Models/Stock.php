<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'stock';

    protected $fillable = [
        'item_name',
        'quantity',
        'last_updated',
        'updated_by',
    ];

    protected $casts = [
        'last_updated' => 'datetime',
    ];

    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public static function updateOrCreateStock($itemName, $quantityToAdd, $userId)
    {
        $stock = self::firstOrNew(['item_name' => $itemName]);
        $stock->quantity = ($stock->quantity ?? 0) + $quantityToAdd;
        $stock->last_updated = now();
        $stock->updated_by = $userId;
        $stock->save();

        return $stock;
    }
}
