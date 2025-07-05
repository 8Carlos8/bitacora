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
        Schema::create('maestros', function (Blueprint $table) {
            $table->id('id_maestro');
            $table->string('num_empleado', 20)->unique();
            $table->string('nombre_completo', 255);
            $table->string('carrera', 100);
            $table->string('especialidad', 100)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->timestamp('fecha_registro')->useCurrent();
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maestros');
    }
};
