<?php

use App\Models\Client;
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
        Schema::create('premises', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->
            on('clients')->onDelete('cascade');
            $table->string('titulo');
            $table->string('descripcion');
            $table->integer('cantidad_habitaciones');
            $table->integer('cantidad_camas');
            $table->integer('cantidad_banos');
            $table->integer('max_personas');
            $table->boolean('tiene_wifi');
            $table->enum('tipo_propiedad', ['0', '1', '2', '3', '4', '5', '6'])->
            comment('0: Casa, 1: Departamento, 2: Cabaña, 3: Loft, 4: Hostal, 5: Hotel, 6: Otro');
            $table->decimal('precio_por_noche', 10, 2);
            $table->decimal('ubicacion_lat', 10, 7);
            $table->decimal('ubicacion_long', 10, 7);
            $table->string('ubicacion_ciudad');
            $table->decimal('tarifa_limpieza', 10, 2);
            $table->timestamps();
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('premises');
    }
};
