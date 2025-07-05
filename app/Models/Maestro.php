<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maestro extends Model
{
    use HasFactory;
    protected $table = "maestros";
    protected $primaryKey = 'id_maestro';
    public $timestamps = false;

    protected $fillable = [
        'num_empleado',
        'nombre_completo',
        'carrera',
        'especialidad',
        'email',
        'telefono',
        'fecha_registro',
    ];

}
