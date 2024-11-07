<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorData extends Model
{

    protected $connection = 'mysql';
    protected $table = 'sensor_data';
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



