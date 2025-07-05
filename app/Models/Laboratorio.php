<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laboratorio extends Model
{
    use HasFactory;
    protected $table = "laboratorios";
    protected $primaryKey = 'id_laboratorio';
    public $timestamps = false;

    protected $fillable = [
        'nombre_laboratorio',
        'ubicacion',
        'capacidad',
    ];

}
