<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event_reward extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'event_rewards'; // UUIDs are strings
    protected $keyType = 'string'; // UUIDs are strings
    public $incrementing = false;  // UUID is not auto-incrementing
    protected $fillable = [
        'id',
        'event_guid',
        'category',
        'prize',
        'condition',
    ];
}
