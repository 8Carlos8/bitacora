<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistroBitacora;
use App\Models\Maestro;
use App\Models\Laboratorio;
use Barryvdh\DomPDF\Facade\Pdf;

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
            $bitacora = RegistroBitacora::create($validated);
            return response()->json([
                'message' => 'Registro creado correctamente.',
                'bitacora' => $bitacora
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ocurrió un error al guardar: ' . $e->getMessage()], 500);
        }
    }

    public function listaBitacoras(Request $request)
    {
        try {
            $bitacoras = RegistroBitacora::all();
            return response()->json(['bitacoras' => $bitacoras], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener la lista de bitacoras', 'error' => $e->getMessage()], 500);
        }
    }

    public function generarReportePDF()
    {
        $registros = RegistroBitacora::with(['maestro', 'laboratorio'])->orderBy('fecha', 'desc')->get();

        $pdf = Pdf::loadHtml($this->buildHtml($registros))->setPaper('A4', 'portrait');

        return $pdf->download('reporte_bitacora.pdf');
    }

    private function buildHtml($registros)
    {
        $html = '
        <html><head><meta charset="UTF-8"><style>
            body { font-family: DejaVu Sans; font-size: 12px; }
            h1 { text-align: center; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid #000; padding: 5px; text-align: left; }
            th { background-color: #f2f2f2; }
        </style></head><body>';

        $html .= '<h1>Reporte de Bitácora</h1><table><thead>
            <tr>
                <th>ID</th>
                <th>Maestro</th>
                <th>Laboratorio</th>
                <th>Fecha</th>
                <th>Hora Entrada</th>
                <th>Hora Salida</th>
                <th>Grupo</th>
                <th>Práctica</th>
            </tr>
        </thead><tbody>';

        foreach ($registros as $r) {
            $html .= '<tr>
                <td>' . $r->id . '</td>
                <td>' . ($r->maestro->nombre ?? 'N/A') . '</td>
                <td>' . ($r->laboratorio->nombre ?? 'N/A') . '</td>
                <td>' . $r->fecha . '</td>
                <td>' . $r->hora_entrada . '</td>
                <td>' . $r->hora_salida . '</td>
                <td>' . $r->grupo . '</td>
                <td>' . $r->nombre_practica . '</td>
            </tr>';
        }

        $html .= '</tbody></table></body></html>';

        return $html;
    }
}






