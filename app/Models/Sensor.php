<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use MongoDB\Laravel\Eloquent\Model;

class Sensor extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'sensor_esp'; 
    protected $fillable = ['acceleration', 'rotation', 'time'];
}
