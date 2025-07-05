<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Laboratorio;


class LaboratorioController extends Controller
{
    public function register(Request $request){

        $validator = Validator::make($request->all(), [
            'nombre_laboratorio' => 'required|string',
            'ubicacion' => 'required|string',
            'capacidad' => 'required|integer',
        ]);

        //Si la validación falla, devolver un error
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $laboratorio = Laboratorio::create([
            'nombre_laboratorio' => $request->nombre_laboratorio,
            'ubicacion' => $request->ubicacion,
            'capacidad' => $request->capacidad,
        ]);

        return response()->json(['message' => 'Laboratorio registrado exitosamente.', 'laboratorio' => $laboratorio], 201);
    }

    public function update(Request $request){

        $laboratorio = Laboratorio::find($request->input('id_laboratorio'));

        //Valida si el laboratorio existe
        if (!$laboratorio) {
            return response()->json(['message' => 'Laboratorio no encontrado'], 404);
        }

        $laboratorio->nombre_laboratorio = $request->input('nombre_laboratorio', $laboratorio->nombre_laboratorio);
        $laboratorio->ubicacion = $request->input('ubicacion', $laboratorio->ubicacion);
        $laboratorio->capacidad = $request->input('capacidad', $laboratorio->capacidad);

        $laboratorio->save();

        return response()->json(['message' => 'Laboratorio actualizado exitosamente.', 'laboratorio' => $laboratorio], 200);
    }

    public function delete(Request $request){

        $laboratorio = Laboratorio::find($request->input('id_laboratorio'));

        //Valida si el laboratorio existe
        if (!$laboratorio) {
            return response()->json(['message' => 'Laboratorio no encontrado'], 404);
        }

        $laboratorio->delete();

        return response()->json(['message' => 'Laboratorio eliminado exitosamente.', 'laboratorio' => $laboratorio], 200);
    }

    public function verLaboratorio(Request $request){

        $id = $request->input('id_laboratorio');

        $laboratorio = Laboratorio::find($id);

        if (!$laboratorio) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        //Regresa una respuesta con los datos del laboratorio
        return response()->json(['laboratorio' => $laboratorio], 200);
    }

    public function listaLaboratorio(Request $request){

        //Busca la lista de laboratorio
        try {
            $laboratorios = Laboratorio::all();
        } catch (\Exception $e) {
            //Si ocurre un error al obtener la lista de laboratorios, regresa un error
            return response()->json(['message' => 'Error al obtener la lista de laboratorios', 'error' => $e->getMessage()], 500);
        }

        //Regresa una respuesta con la lista de usuarios
        return response()->json(['laboratorios' => $laboratorios], 200);
    }
}
