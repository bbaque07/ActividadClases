<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estudiante;
use Illuminate\Support\Facades\Log;

class EstudianteController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    /**
     * @OA\Get(
     *     path="/api/estudiantes",
     *     summary="Lista de estudiantes",
     *     @OA\Response(response=200, description="Lista obtenida correctamente")   
     * )
     */
    public function index(){
        $estudiantes = Estudiante::with('paralelo')->get();

        $resultado = $estudiantes->map(function ($est) {
            return [
                'id' => $est->id,
                'nombre' => $est->nombre,
                'cedula' => $est->cedula,
                'correo' => $est->correo,
                'paralelo' => $est->paralelo->nombre ?? null,
            ];
        });

        return response()->json($resultado);
    }

    /**
     * Store a newly created resource in storage.
     */

    /**
     * @OA\Post(
     *     path="/api/estudiantes",
     *     summary="Crear un nuevo estudiante",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre","cedula","correo","paralelo_id"},
     *             @OA\Property(property="nombre", type="string", example="Juan Perez"),
     *             @OA\Property(property="cedula", type="string", example="1101234567"),
     *             @OA\Property(property="correo", type="string", format="email", example="juan@example.com"),
     *             @OA\Property(property="paralelo_id", type="integer", example=1),
     *           )
     *        ),
     *        @OA\Parameter(
     *            name="Accept",
     *            in="header",
     *            required=true,
     *            @OA\Schema(type="string", default="application/json"),
     *            description="Indica que se espera una respuesta en formato JSON"
     *        ),
     *        @OA\Response(response=201, description="Estudiante creado exitosamente"),
     *        @OA\Response(response=422, description="Errores de validacion")
     * )
     */
    public function store(Request $request){
        Log::info('Intentando crear estudiante', $request->all());
        $request->validate([
            'nombre' => 'required|string|max:100',
            'cedula' => 'required|string|max:10|unique:estudiantes',
            'correo' => 'required|email',
            'paralelo_id' => 'required|exists:paralelos,id'
        ]);

        $estudiante = Estudiante::create($request->all());
        Log::info('Estudiante creado con ID:' . $estudiante->id);
        return response()->json([
            'mensaje' => 'Estudiante creado exitosamente',
            'estudiante' => $estudiante
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    /**
     * @OA\Get(
     *     path="/api/estudiantes/{id}",
     *     summary="Mostrar un estudiante especifico",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del estudiante",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Estudiante encontrado"),
     *     @OA\Response(response=404, description="Estudiante no encontrado")
     * )
     */
    public function show(string $id){
        $estudiante = Estudiante::with('paralelo')->find($id);

        if(!$estudiante){
            return response()->json(['mensaje' => 'Estudiante no encontrado'],404);
        }

        return response()->json([
            'id' => $estudiante->id,
            'nombre' => $estudiante->nombre,
            'correo' => $estudiante->correo,
            'paralelo' => $estudiante->paralelo->nombre ?? null,
        ]);
    }

    /**
     * Update the specified resource in storage
     */
    /**
     * @OA\Put(
     *     path="/api/estudiantes/{id}",
     *     summary="Actualizar un estudiante existente",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del estudiante a actualizar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string", example="Pedro Gomez"),
     *             @OA\Property(property="cedula", type="string", example="1101234568"),
     *             @OA\Property(property="correo", type="string", format="email", example="Pedro@example.com"),
     *             @OA\Property(property="paralelo_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Estudiante actualizado correctamente"),
     *     @OA\Response(response=404, description="Estudiante no encontrado")
     * )
     */
    public function update(Request $request, string $id){
        $estudiante = Estudiante::find($id);

        if(!$estudiante){
            return response()->json(['mensaje' => 'Estudiante no encontrado'], 404);
        }

        $request->validate([
            'nombre' => 'sometimes|required|string|max:100',
            'cedula' => 'sometimes|required|string|max:10|unique:estudiantes,cedula,' . $id,
            'correo' => 'sometimes|required|string|email',
            'paralelo_id' => 'sometimes|required|exists:paralelos,id'
        ]);

        $estudiante->update($request->all());

        return response()->json([
            'mensaje' => 'Estudiante actualizado correctamente',
            'estudiante' => $estudiante
        ]);
    }
    
    /**
     * Remove the specified resource from storage.
     */
    /**
     * @OA\Delete(
     *     path="/api/estudiantes/{id}",
     *     summary="Eliminar un estudiante",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del estudiante a eliminar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Estudiante Eliminado"),
     *     @OA\Response(response=404, description="Estudiante no encontrado")
     * )
     */
    public function destroy($id)
    {
        $estudiante = Estudiante::find($id);

        if(!$estudiante){
            return response()->json(['mensaje' => 'Estudiante no encontrado'], 404);
        }

        $estudiante->delete();

        return response()->json(['mensaje' => 'Estudiante Eliminado']);
    }
}
