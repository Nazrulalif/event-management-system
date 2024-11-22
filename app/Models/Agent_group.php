<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent_group extends Model
{
    use HasFactory, HasUlids;
    protected $keyType = 'string'; // UUIDs are strings
    public $incrementing = false;  // UUID is not auto-incrementing
    protected $table = 'agent_groups';
    protected $fillable = [
        'event_guid',
        'nazir',
    ];

    public function members()
    {
        return $this->hasManyThrough(
            Agent::class,
            Agent_member::class,
            'group_guid',  // Foreign key on staff_member_groups table
            'id',          // Foreign key on users table
            'id',          // Local key on staff_groups table
            'agent_guid'   // Local key on staff_member_groups table
        );
    }
}
