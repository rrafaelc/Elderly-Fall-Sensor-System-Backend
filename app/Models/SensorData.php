<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorData extends Model
{
    protected $fillable = [
        'serial_number',
        'event_type',
        'is_fall',
        'is_impact',
        'ax',
        'ay',
        'az',
        'gx',
        'gy',
        'gz'
    ];
}
