<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Device extends Model
{


    use HasFactory;
    protected $fillable = ['user_id', 'whatsapp_number',
        'name',
    ];



    // Relação com a tabela persons através da tabela intermediária
    public function persons()
    {
        return $this->belongsToMany(Person::class, 'persons_devices');
    }
}
