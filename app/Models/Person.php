<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $fillable = ['user_id', 'name', 'rg', 'cpf','date_of_birth', 'blood_type', 'street', 'street_number', 'neighborhood', 'city', 'state', 'zip_code', 'conditions' ];
    protected $table = 'persons';

    public function user()
    {
        //one person belongs to one user
        return $this->belongsTo('App\Models\User');
    }

    // Relação com a tabela devices através da tabela intermediária
    public function devices()
    {
        return $this->belongsToMany(Device::class, 'persons_devices');
    }
}
