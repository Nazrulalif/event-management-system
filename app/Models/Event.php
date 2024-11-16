<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory, HasUuids;
    protected $keyType = 'string'; // UUIDs are strings
    public $incrementing = false;  // UUID is not auto-incrementing
    protected $table = 'events';
    protected $fillable = [
        'event_title',
        'platform',
        'poster_path',
        'period',
        'state',
        'segment',
        'objective',
        'status',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'created_by',
    ];

    protected $dates = ['start_date', 'end_date'];
}
