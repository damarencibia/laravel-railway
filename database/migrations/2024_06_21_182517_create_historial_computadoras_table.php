<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistorialComputadorasTable extends Migration
{
    public function up()
    {
        Schema::create('historial_computadoras', function (Blueprint $table) {
            $table->id();
            $table->string('computadora_id');
            $table->enum('operacion', ['crear', 'modificar', 'eliminar']);
            $table->text('campo_modificado')->nullable();
            $table->text('valor_anterior')->nullable();
            $table->text('valor_nuevo')->nullable();
            $table->text('detalles')->nullable();
            $table->timestamps();
        }); 
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_computadoras');
    }
}
