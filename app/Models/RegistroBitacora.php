<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistroBitacora extends Model
{
    protected $table = 'registros_bitacora';
    protected $primaryKey = 'id_registro';
    public $timestamps = false;

    protected $fillable = [
        'id_maestro',
        'id_laboratorio',
        'hora_entrada',
        'hora_salida',
        'fecha',
        'cuatrimestre',
        'grupo',
        'num_alumnos',
        'nombre_practica',
        'observaciones',
        'fecha_creacion',
        'fecha_modificacion',
    ];

    public function maestro()
    {
        return $this->belongsTo(Maestro::class, 'id_maestro');
    }

    public function laboratorio()
    {
        return $this->belongsTo(Laboratorio::class, 'id_laboratorio');
    }
}
