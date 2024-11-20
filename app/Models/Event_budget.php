<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event_budget extends Model
{
    use HasFactory, HasUlids;
    protected $table = 'event_budgets'; // UUIDs are strings
    protected $keyType = 'string'; // UUIDs are strings
    public $incrementing = false;  // UUID is not auto-incrementing
    protected $fillable = [
        'event_guid',
        'total',
        'fee_percent',
        'fee',
        'tax_percent',
        'tax',
        'grand_total',
    ];

    public function budget_items()
    {
        return $this->hasMany(Budget_item::class, 'budget_guid', 'id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_guid', 'id');
    }
}
