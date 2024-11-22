<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff_group extends Model
{
    use HasFactory, HasUlids;
    protected $keyType = 'string'; // UUIDs are strings
    public $incrementing = false;  // UUID is not auto-incrementing
    protected $table = 'staff_groups';
    protected $fillable = [
        'event_guid',
        'group_name',
        'mentor',
        'leader',
        'vehicle',
    ];

    // Staff_group model
    public function members()
    {
        return $this->hasManyThrough(
            User::class,
            StaffMember_group::class,
            'group_guid',  // Foreign key on staff_member_groups table
            'id',          // Foreign key on users table
            'id',          // Local key on staff_groups table
            'staff_guid'   // Local key on staff_member_groups table
        );
    }

    // Staff_group model
    public function agent_members()
    {
        return $this->hasManyThrough(
            Agent::class,
            AgentMember_Group::class,
            'group_guid',  // Foreign key on staff_member_groups table
            'id',          // Foreign key on users table
            'id',          // Local key on staff_groups table
            'agent_guid'   // Local key on staff_member_groups table
        );
    }

    public function members_update()
    {
        return $this->belongsToMany(User::class, 'staff_member_groups', 'group_guid', 'staff_guid')
            ->withTimestamps();
    }
}
