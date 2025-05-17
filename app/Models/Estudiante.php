<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    //activar la funcion para poder agregar registros desde este modelo
    protected $fillable = ['nombre','cedula','correo','paralelo_id'];

    //activar la funcion que me permita relacionar con las otras tablas
    public function paralelo(){
        return $this->belongsTo(Paralelo::class);
    }
}
