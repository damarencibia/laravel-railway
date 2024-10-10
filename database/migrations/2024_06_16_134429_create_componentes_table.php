<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComponentesTable extends Migration
{
    public function up()
    {
        Schema::create('componentes', function (Blueprint $table) {
            $table->id();
            $table->string('nro_serie')->unique();
            $table->string('marca')->nullable(false);
            $table->enum('tipo_componente', ['placa_base', 'memoria_ram', 'lector_cd', 'disco_duro'])->nullable(false);
            $table->boolean('disponible')->default(1);
            $table->integer('user_id')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('componentes');
    }
};
