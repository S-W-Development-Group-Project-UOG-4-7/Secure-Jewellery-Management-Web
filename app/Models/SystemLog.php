<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SystemLog extends Model
{
    use HasFactory;

    // Mass-assignable fields
    protected $fillable = [
        'level', 'action', 'message', 'context', 'user_id'
    ];

    // Cast context JSON to array automatically
    protected $casts = [
        'context' => 'array',
    ];

    /**
     * Relation to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
