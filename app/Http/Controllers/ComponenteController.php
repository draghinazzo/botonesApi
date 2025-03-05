<?php
namespace App\Http\Controllers;

use App\Models\Componente;
use Illuminate\Http\Request;

class ComponenteController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/componentes",
     *     summary="Lista todos los componentes",
     *     @OA\Response(response=200, description="Lista de componentes")
     * )
     */
    public function index()
    {
        return response()->json(Componente::where('activo', true)->get());
    }

    /**
     * @OA\Post(
     *     path="/api/componentes",
     *     summary="Crear un nuevo componente",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"nombre", "activo"},
     *                 @OA\Property(property="nombre", type="string"),
     *                 @OA\Property(property="descripcion", type="string"),
     *                 @OA\Property(property="activo", type="boolean"),
     *                 @OA\Property(property="imagen", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Componente creado")
     * )
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string',
            'descripcion' => 'nullable|string',
            'activo' => 'required|boolean',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png|max:2048' // Validación de imagen
        ]);

        // Si hay una imagen, guardarla y obtener la ruta
        if ($request->hasFile('imagen')) {
            $imagenPath = $request->file('imagen')->store('imagenes_componentes', 'public');
            $validatedData['imagen'] = asset('storage/' . $imagenPath); // Guardamos la URL pública
        }
        print "XXXXXXXXXXXXXXX";
        // Crear el componente con la imagen si existe
        $componente = Componente::create($validatedData);

        return response()->json($componente, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/componentes/{id}",
     *     summary="Obtener un componente por ID",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Detalles del componente")
     * )
     */
    public function show($id)
    {
        return response()->json(Componente::findOrFail($id));
    }

    /**
     * @OA\Put(
     *     path="/api/componentes/{id}",
     *     summary="Actualizar un componente",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string"),
     *             @OA\Property(property="descripcion", type="string"),
     *             @OA\Property(property="activo", type="boolean")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Componente actualizado")
     * )
     */
    public function update(Request $request, $id)
    {
        $componente = Componente::findOrFail($id);
        $componente->update($request->all());
        return response()->json($componente);
    }

    /**
     * @OA\Delete(
     *     path="/api/componentes/{id}",
     *     summary="Desactivar un componente",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Componente desactivado")
     * )
     */
    public function destroy($id)
    {
        $componente = Componente::findOrFail($id);
        $componente->update(['activo' => false]);
        return response()->json(['message' => 'Componente desactivado'], 200);
    }
}
