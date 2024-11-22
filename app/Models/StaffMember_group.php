<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffMember_group extends Model
{
    use HasFactory, HasUuids;
    protected $keyType = 'string'; // UUIDs are strings
    public $incrementing = false;  // UUID is not auto-incrementing
    protected $table = 'staff_member_groups';
    protected $fillable = [
        'group_guid',
        'staff_guid',
    ];
}
