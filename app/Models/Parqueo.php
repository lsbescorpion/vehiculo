<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parqueo extends Model
{
    use HasFactory;

    protected $table = 'parqueo';

    protected $primaryKey = 'id';

    public $timestamps = false;

    public function vehiculos()
    {
        return $this->belongsTo(Vehiculo::class, 'vehiculo_id', 'id');
    }
}
