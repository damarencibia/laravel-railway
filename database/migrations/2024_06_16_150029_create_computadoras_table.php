<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateComputadorasTable extends Migration
{
    public function up()
    {
        Schema::create('computadoras', function (Blueprint $table) {
            $table->id();
            $table->string('nro_expediente')->unique()->nullable(false);
            $table->string('departamento')->unique()->nullable(false);
            $table->string('usuario')->nullable(false);
            $table->string('cpu_torre')->unique()->nullable(false);
            $table->string('monitor')->unique()->nullable(false);
            $table->string('mouse')->unique()->nullable(false);
            $table->string('teclado')->unique() ->nullable(false);
            $table->string('ups')->unique()->nullable(false);
            $table->string('bocinas')->unique()->nullable(false);
            $table->string('placa_base')->unique()->nullable(false);
            $table->string('ram')->unique()->nullable(false);
            $table->string('lector_cd')->unique()->nullable(false);
            $table->string('disco_duro')->unique()->nullable(false);
            $table->boolean('local_climatizado')->nullable(false); // Usando boolean para simplificar
            $table->boolean('local_sd_mcmpt')->nullable(false); // Usando boolean para simplificar
            $table->string('so')->nullable(false);
            $table->string('responsable')->nullable(false);
            $table->string('jefe_seg_inf')->nullable(false);

            // Definiciones de claves foráneas
            $table->foreign('cpu_torre')->references('nro_inventario')->on('piezas');
            $table->foreign('monitor')->references('nro_inventario')->on('piezas');
            $table->foreign('mouse')->references('nro_inventario')->on('piezas');
            $table->foreign('teclado')->references('nro_inventario')->on('piezas');
            $table->foreign('ups')->references('nro_inventario')->on('piezas');
            $table->foreign('bocinas')->references('nro_inventario')->on('piezas');
            $table->foreign('placa_base')->references('nro_serie')->on('componentes');
            $table->foreign('ram')->references('nro_serie')->on('componentes');
            $table->foreign('lector_cd')->references('nro_serie')->on('componentes');
            $table->foreign('disco_duro')->references('nro_serie')->on('componentes');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('computadoras');
    }
};
