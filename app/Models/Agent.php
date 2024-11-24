<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; // Add this

class Agent extends Authenticatable
{
    use HasFactory, HasUuids;
    protected $keyType = 'string'; // UUIDs are strings
    public $incrementing = false;  // UUID is not auto-incrementing
    protected $table = 'agents';
    protected $fillable = [
        'name',
        'email',
        'password',
        'channel',
        'phone_number',
        'attribute',
    ];
    // Optionally, hide the password field when returning the model as JSON
    protected $hidden = [
        'password',
        'remember_token', // Add this for authentication
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
