<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget_item extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'budget_items'; // UUIDs are strings
    protected $keyType = 'string'; // UUIDs are strings
    public $incrementing = false;  // UUID is not auto-incrementing
    protected $fillable = [
        'budget_guid',
        'description',
        'days',
        'frequency',
        'price_per_unit',
        'total_budget',
        'remark',
    ];

    public function budget()
    {
        return $this->belongsTo(Event_budget::class, 'budget_guid', 'id');
    }
}
