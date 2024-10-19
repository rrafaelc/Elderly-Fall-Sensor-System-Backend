<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonDevice extends Model
{
    protected $connection = 'mysql';
    use HasFactory;
    protected $table = 'persons_devices'; // Especificando o nome da tabela
    protected $fillable = ['user_id', 'person_id',
    'device_id',
];

}
