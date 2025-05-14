<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paralelo extends Model
{
    //activar la funcion para poder agregar registros desde este modelo
    protected $fillabel = ['nombre'];

    //activar la funcion que me permita relacionar con las otras tablas
    public function paralelo(){
        return $this->HasMany(Estudiante::class);
    }
}
