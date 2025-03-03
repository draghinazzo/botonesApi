<?php

namespace App\Http\Controllers;

use App\Models\Entrega;
use Illuminate\Http\Request;

/**
 * @OA\Info(title="API de Entregas", version="1.0")
 */
class EntregaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/entregas",
     *     summary="Lista todas las entregas",
     *     @OA\Response(response=200, description="Lista de entregas")
     * )
     */
    public function index()
    {
        return response()->json(Entrega::all());
    }

    /**
     * @OA\Post(
     *     path="/api/entregas",
     *     summary="Crear una nueva entrega",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre_entrega", "nombre_negocio", "fecha", "tipo_boton"},
     *             @OA\Property(property="nombre_entrega", type="string"),
     *             @OA\Property(property="nombre_negocio", type="string"),
     *             @OA\Property(property="fecha", type="string", format="date"),
     *             @OA\Property(property="tipo_boton", type="string", enum={"wifi", "ethernet"}),
     *             @OA\Property(property="observaciones", type="string"),
     *             @OA\Property(property="componentes", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(response=201, description="Entrega creada")
     * )
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre_entrega' => 'required|string',
            'nombre_negocio' => 'required|string',
            'fecha' => 'required|date',
            'tipo_boton' => 'required|in:wifi,ethernet',
            'observaciones' => 'nullable|string',
        ]);

        // Determinar componentes según tipo de botón
        $componentes = $request->input('tipo_boton') === 'wifi'
            ? ['placa_wifi', 'cargador', 'caja', 'led']
            : ['placa_ethernet', 'microcontrolador_atmega', 'leds', 'cargador', 'caja'];

        $entrega = Entrega::create(array_merge($validatedData, ['componentes' => json_encode($componentes)]));

        return response()->json($entrega, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/entregas/{id}",
     *     summary="Obtener una entrega por ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Detalles de la entrega")
     * )
     */
    public function show($id)
    {
        return response()->json(Entrega::findOrFail($id));
    }

    /**
     * @OA\Put(
     *     path="/api/entregas/{id}",
     *     summary="Actualizar una entrega",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true, @OA\JsonContent(
     *         @OA\Property(property="nombre_entrega", type="string"),
     *         @OA\Property(property="nombre_negocio", type="string"),
     *         @OA\Property(property="fecha", type="string", format="date"),
     *         @OA\Property(property="tipo_boton", type="string", enum={"wifi", "ethernet"}),
     *         @OA\Property(property="observaciones", type="string")
     *     )),
     *     @OA\Response(response=200, description="Entrega actualizada")
     * )
     */
    public function update(Request $request, $id)
    {
        $entrega = Entrega::findOrFail($id);
        $entrega->update($request->all());
        return response()->json($entrega);
    }

    /**
     * @OA\Delete(
     *     path="/api/entregas/{id}",
     *     summary="Eliminar una entrega",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Entrega eliminada")
     * )
     */
    public function destroy($id)
    {
        Entrega::destroy($id);
        return response()->json(null, 204);
    }
}
