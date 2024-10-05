<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'name','email', 'whatsapp_number', 'address', 'rg', 'cpf'];
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
