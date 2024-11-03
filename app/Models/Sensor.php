<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use MongoDB\Laravel\Eloquent\Model;

class Sensor extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'sensor_esp';
    //protected $fillable = ['aceleration', 'rotation', 'time', 'fall', 'level'];
    protected $fillable = [
        'serial_number',
        'event_type',
        'is_fall',
        'is_impact',
        'acceleration',
        'gyroscope'
    ];

    // Definindo tipos específicos para os dados embutidos (opcional, dependendo do uso)
    protected $casts = [
        'is_fall' => 'boolean',
        'is_impact' => 'boolean',
        'acceleration' => 'array',  // Armazena os dados ax, ay, az
        'gyroscope' => 'array'      // Armazena os dados gx, gy, gz
    ];
}
