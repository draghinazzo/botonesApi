<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('entregas', function (Blueprint $table) {
        $table->id();
        $table->string('nombre_entrega');
        $table->string('nombre_negocio');
        $table->date('fecha');
        $table->enum('tipo_boton', ['wifi', 'ethernet']);
        $table->text('observaciones')->nullable();
        $table->json('componentes'); // Guardará la lista de componentes como JSON
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entregas');
    }
};
