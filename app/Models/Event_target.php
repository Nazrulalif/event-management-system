<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event_target extends Model
{
    use HasFactory, HasUuids;
    protected $keyType = 'string'; // UUIDs are strings
    public $incrementing = false;  // UUID is not auto-incrementing
    protected $table = 'event_targets';
    protected $fillable = [
        'event_guid',
        'product',
        'product',
        'arpu',
        'arpu',
        'sales_physical_target',
        'outbase',
        'inbase',
        'revenue',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
