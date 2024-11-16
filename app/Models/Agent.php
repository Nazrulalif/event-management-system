<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory, HasUuids;
    protected $keyType = 'string'; // UUIDs are strings
    public $incrementing = false;  // UUID is not auto-incrementing
    protected $table = 'agents';
    protected $fillable = [
        'name',
        'email',
        'channel',
        'phone_number',
        'attribute',
    ];
}
