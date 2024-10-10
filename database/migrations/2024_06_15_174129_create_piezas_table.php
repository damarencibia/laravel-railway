<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePiezasTable extends Migration
{
    public function up()
    {
        Schema::create('piezas', function (Blueprint $table) {
            $table->id();
            $table->string('nro_inventario')->unique()->nullable();
            $table->string('marca')->nullable();
            $table->string('color')->nullable();
            $table->enum('tipo_de_pieza', ['cpu_torre', 'monitor', 'mouse', 'teclado', 'ups', 'bocinas'])->nullable();
            $table->boolean('disponible')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('piezas');
    }
}
