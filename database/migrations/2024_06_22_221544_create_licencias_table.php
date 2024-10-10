<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLicenciasTable extends Migration
{
    public function up(): void
    {
        Schema::create('licencias', function (Blueprint $table) {
            $table->id();
            $table->string('id_licencia')->unique();
            $table->string('programa')->nullable(false);
            $table->date('fecha_compra')->nullable(false);
            $table->date('fecha_expiracion')->nullable(); // Cambiamos a nullable
            $table->text('detalles')->nullable();
            $table->integer('estado')->nullable(false); // Agregamos un campo estado numérico
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('licencias');
    }
}
