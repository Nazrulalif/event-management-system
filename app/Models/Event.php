<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $table = 'events';
    protected $fillable = [
        'event_title',
        'description',
        'status',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'created_by',
    ];
}
