<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event_schedule extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'event_schedules'; // UUIDs are strings
    protected $keyType = 'string'; // UUIDs are strings
    public $incrementing = false;  // UUID is not auto-incrementing
    protected $fillable = [
        'id',
        'event_guid',
        'day_name',
        'event_date',
        'activity',
        'target',
        'event_vanue',
        'business_zone',
    ];

    public function event()
    {
        // Specify the foreign key explicitly if it's not the default 'event_id'
        return $this->belongsTo(Event::class, 'event_guid', 'guid');
    }
}
