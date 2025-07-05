<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('registros_bitacora', function (Blueprint $table) {
            $table->id('id_registro');
            $table->unsignedBigInteger('id_maestro');
            $table->unsignedBigInteger('id_laboratorio')->nullable();
            $table->time('hora_entrada');
            $table->time('hora_salida');
            $table->date('fecha');
            $table->string('cuatrimestre', 50);
            $table->string('grupo', 50);
            $table->integer('num_alumnos');
            $table->string('nombre_practica', 255);
            $table->text('observaciones')->nullable();
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('fecha_modificacion')->useCurrent()->useCurrentOnUpdate();
            //$table->timestamps();

            $table->foreign('id_maestro')->references('id_maestro')->on('maestros');
            $table->foreign('id_laboratorio')->references('id_laboratorio')->on('laboratorios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registro_bitacoras');
    }
};
