<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',   // Custom: Used for login instead of name
        'email',
        'password',
        'role',       // Custom: Admin, Customer, etc.
        'otp_code',   // Custom: Stores the temporary OTP
        'otp_expiry', // Custom: Stores OTP expiration time
        'is_active',  // Custom: To ban/activate users
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'otp_expiry' => 'datetime', // OPTIONAL: Casts this to a Carbon object automatically
            'is_active' => 'boolean',   // OPTIONAL: Casts this to true/false automatically
        ];
    }
}