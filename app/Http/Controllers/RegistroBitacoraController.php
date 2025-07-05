<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistroBitacora;
use App\Models\Maestro;
use App\Models\Laboratorio;

class RegistroBitacoraController extends Controller
{
    public function index()
    {
        $registros = RegistroBitacora::with(['maestro', 'laboratorio'])->orderBy('fecha', 'desc')->get();
        return view('registro.index', compact('registros'));
    }
    
    public function create()
    {
        $maestros = Maestro::all();
        $laboratorios = Laboratorio::all();
        return view('registro.create', compact('maestros', 'laboratorios'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_maestro' => 'required|exists:maestros,id_maestro',
            'id_laboratorio' => 'nullable|exists:laboratorios,id_laboratorio',
            'hora_entrada' => 'required',
            'hora_salida' => 'required|after:hora_entrada',
            'fecha' => 'required|date',
            'cuatrimestre' => 'required|string|max:50',
            'grupo' => 'required|string|max:50',
            'num_alumnos' => 'required|integer|min:1',
            'nombre_practica' => 'required|string|max:255',
            'observaciones' => 'nullable|string',
        ]);

        try {
            RegistroBitacora::create($validated);
            return redirect()->back()->with('success', 'Registro creado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Ocurrió un error al guardar: ' . $e->getMessage()]);
        }
    }
}


