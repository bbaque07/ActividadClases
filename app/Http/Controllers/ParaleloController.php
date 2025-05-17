<?php

namespace App\Http\Controllers;

use App\Models\Paralelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 

/**
 * @OA\Schema(
 *     schema="Paralelo",
 *     type="object",
 *     title="Paralelo",
 *     required={"id", "nombre"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nombre", type="string", example="Paralelo A")
 * )
 */
class ParaleloController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/paralelos",
     *     summary="Listar todos los paralelos",
     *     tags={"Paralelos"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de paralelos",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Paralelo"))
     *     )
     * )
     */
    public function index()
    {
        $paralelo = Paralelo::all();
        return $paralelo;
    }

    /**
     * @OA\Post(
     *     path="/api/paralelos",
     *     summary="Crear un nuevo paralelo",
     *     tags={"Paralelos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre"},
     *             @OA\Property(property="nombre", type="string", example="Paralelo A")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Paralelo creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Paralelo creado exitosamente"),
     *             @OA\Property(property="paralelo", ref="#/components/schemas/Paralelo")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
    public function store(Request $request)
    {
        Log::info('Datos que vienen en la peticion:', $request->all());
        $request->validate([
            'nombre' => 'required|string|max:100|unique:paralelos'
        ]);
        
        $paralelo = Paralelo::create([
            'nombre' => $request->nombre
        ]);
        Log::info('Paralelo creado con el ID:'. $paralelo->id);

        return response()->json([
            'message' => 'Paralelo creado exitosamente',
            'paralelo' => $paralelo
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    /**
     * @OA\Get(
     *     path="/api/paralelos/{id}",
     *     summary="Mostrar un paralelo específico",
     *     tags={"Paralelos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Paralelo encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/Paralelo")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Paralelo no encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        Log::info('Iniciando solicitud con el id:', ['id' => $id]);
        $paralelo = Paralelo::find($id);

        if(!$paralelo){
            Log::info('Datos no encontrado:', ['id' => $id]);
            return response()->json(['message'=> 'Paralelo no encontrado'], 404);
        }
        Log::info('Datos encontrado:', ['paralelo' => $paralelo]);
        return $paralelo;
    }

    /**
     * @OA\Put(
     *     path="/api/paralelos/{id}",
     *     summary="Actualizar un paralelo existente",
     *     tags={"Paralelos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre"},
     *             @OA\Property(property="nombre", type="string", example="Paralelo B")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Paralelo actualizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Paralelo actualizado"),
     *             @OA\Property(property="paralelo", ref="#/components/schemas/Paralelo")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Paralelo no encontrado"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        Log::info('Iniciando solicitud con el id:', ['id' => $id]);
        $paralelo = Paralelo::find($id);
        if(!$paralelo){
            Log::info('Datos no encontrado:', ['id' => $id]);
            return response()->json(['message'=> 'Paralelo no encontrado'], 404);
        }
        $request->validate([
            'nombre' => 'required|string|max:100',
        ]);

        $paralelo->update($request->all());

        Log::info('Datos actualizado:', ['paralelo' => $paralelo]);
        return response()->json(['message' => 'Paralelo actualizado', 'paralelo' => $paralelo], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * @OA\Delete(
     *     path="/api/paralelos/{id}",
     *     summary="Eliminar un paralelo",
     *     tags={"Paralelos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Paralelo eliminado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Paralelo eliminado"),
     *             @OA\Property(property="paralelo", ref="#/components/schemas/Paralelo")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Paralelo no encontrado"
     *     )
     * )
     */
    public function destroy($id)
    {
        Log::info('Iniciando solicitud con el id:', ['id' => $id]);
        $paralelo = Paralelo::find($id);
        if(!$paralelo){
            Log::info('Datos no encontrado:', ['id' => $id]);
            return response()->json(['message'=> 'Paralelo no encontrado'], 404);
        }
        $paralelo->delete();
        Log::info('Datos eliminado:', ['paralelo' => $paralelo]);
        return response()->json(['message' => 'Paralelo eliminado', 'paralelo' => $paralelo], 200);
    }
}
