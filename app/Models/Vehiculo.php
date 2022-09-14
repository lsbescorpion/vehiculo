<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;

    protected $table = 'vehiculo';

    protected $primaryKey = 'id';

    public $timestamps = false;

    public function parqueos()
    {
        return $this->hasMany(Parqueo::class, 'vehiculo_id', 'id');
    }
}
