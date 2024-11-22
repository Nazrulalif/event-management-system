<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent_member extends Model
{
    use HasFactory, HasUlids;
    protected $keyType = 'string'; // UUIDs are strings
    public $incrementing = false;  // UUID is not auto-incrementing
    protected $table = 'agent_members';
    protected $fillable = [
        'group_guid',
        'agent_guid',
    ];
}
