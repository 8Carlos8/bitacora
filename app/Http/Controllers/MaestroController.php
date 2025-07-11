<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Maestro;
use App\Notifications\MaestroRegistradoNotification;
use Illuminate\Support\Facades\Log;
use App\Services\TwilioService;


class MaestroController extends Controller
{

    protected $twilio;

    public function __construct(TwilioService $twilio)
    {
        $this->twilio = $twilio;
    }

    public function register(Request $request){

        $validator = Validator::make($request->all(), [
            'num_empleado' => 'required|integer',
            'nombre_completo' => 'required|string',
            'carrera' => 'required|string',
            'especialidad' => 'required|string',
            'email' => 'string|email',
            'telefono' => 'string',
            'fecha_registro' => 'required|date',
        ]);

        //Si la validación falla, devolver un error
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $maestro = Maestro::create([
            'num_empleado' => $request->num_empleado,
            'nombre_completo' => $request->nombre_completo,
            'carrera' => $request->carrera,
            'especialidad' => $request->especialidad,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'fecha_registro' => $request->fecha_registro,
        ]);

        // Generar código de verificación
        $verificationCode = rand(100000, 999999);

        // Guardar el código de verificación en la base de datos
        $maestro->codigo_verificacion = $verificationCode;
        $maestro->save();
        
        // Enviar correo con el código de verificación
        $maestro->notify(new MaestroRegistradoNotification($verificationCode));

        // Enviar por WhatsApp
        try {
            $this->twilio->sendSms(
                'whatsapp:+521'.$maestro->telefono,
                "Tu código de verificación es: $verificationCode"
            );
        } catch (\Exception $e) {
            Log::error("Error al enviar WhatsApp: " . $e->getMessage());
            return response()->json(['message' => 'Usuario no encontrado.'], 404);
        }

        return response()->json([
            'message' => 'Usuario registrado con éxito. Se ha enviado un correo para verificar tu cuenta.',
        ], 201);
    }

    public function update(Request $request){
        
        $maestro = Maestro::find($request->input('id_maestro'));

        //Valida si el maestro existe
        if (!$maestro) {
            return response()->json(['message' => 'Maestro no encontrado'], 404);
        }

        $maestro->num_empleado = $request->input('num_empleado', $maestro->num_empleado);
        $maestro->nombre_completo = $request->input('nombre_completo', $maestro->nombre_completo);
        $maestro->carrera = $request->input('carrera', $maestro->carrera);
        $maestro->especialidad = $request->input('especialidad', $maestro->especialidad);
        $maestro->email = $request->input('email', $maestro->email);
        $maestro->telefono = $request->input('telefono', $maestro->telefono);
        $maestro->fecha_registro = $request->input('fecha_registro', $maestro->fecha_registro);

        $maestro->save();

        return response()->json(['message' => 'Maestro actualizado exitosamente.', 'maestro' => $maestro], 200);
    }

    public function delete(Request $request){
        
        $maestro = Maestro::find($request->input('id_maestro'));

        //Valida si el maestro existe
        if (!$maestro) {
            return response()->json(['message' => 'Maestro no encontrado'], 404);
        }

        $maestro->delete();

        return response()->json(['message' => 'Maestro eliminado exitosamente.', 'maestro' => $maestro], 200);
    }

    public function verMaestro(Request $request){

        //Validar ID
        $id = $request->input('id_maestro');

        //Busca el maestro por ID
        $maestro = Maestro::find($id);
        //Valida si el maestro existe
        if (!$maestro) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        //Regresa una respuesta con los datos del maestro
        return response()->json(['maestro' => $maestro], 200);
    }

    public function listaMaestros(Request $request){

        //Busca la lista de maestros
        try {
            $maestros = Maestro::all();
        } catch (\Exception $e) {
            //Si ocurre un error al obtener la lista de maestros, regresa un error
            return response()->json(['message' => 'Error al obtener la lista de maestros', 'error' => $e->getMessage()], 500);
        }

        //Regresa una respuesta con la lista de usuarios
        return response()->json(['maestros' => $maestros], 200);
    }
}
