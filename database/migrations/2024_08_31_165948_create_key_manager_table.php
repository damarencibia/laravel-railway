<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('key_manager', function (Blueprint $table) {
            $table->id();
            $table->string('serial')->unique()->nullable(false);
            $table->string('key')->unique()->nullable(false);
            $table->date('fecha_compra')->default(Carbon::now())->startOfDay();
            $table->date('fecha_expiracion')->nullable();
            $table->integer('estado')->nullable(false);
            $table->integer('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('key_manager');
    }
};
