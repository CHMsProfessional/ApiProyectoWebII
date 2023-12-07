<?php

use App\Models\Client;
use App\Models\Premise;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')
                ->references('id')
                ->on('clients')
                ->onDelete('cascade');
            $table->unsignedBigInteger('premises_id');
            $table->foreign('premises_id')
                ->references('id')
                ->on('premises')
                ->onDelete('cascade');
            $table->integer('cantidad_noches');
            $table->date('fecha_inicio');
            $table->date('fecha_final');
            $table->decimal('tarifa_airbnb', 10, 2);
            $table->decimal('costo_total', 10, 2);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
