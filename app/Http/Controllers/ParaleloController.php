<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ParaleloController extends Controller
{
    //metodos
    //metodos para obtener todos los registros de la tabla
    public function index(){
        return Paralelo::all();
    }

    //metodo para almacenar registros en la tabla
    public function store(Request $request){
        $request->validate([
            'nombre' =>'required|string|max:100|unique:paralelos'
        ]);

        return Paralelo::create($request->all());
    }
}
