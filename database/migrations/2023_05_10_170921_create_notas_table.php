<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notas', function (Blueprint $table) {
            $table->id();
            $table->string('chave')->unique();
            $table->string('numero');
            $table->unsignedBigInteger('remetente_id');
            $table->unsignedBigInteger('transportador_id');
            $table->unsignedBigInteger('destinatario_id');
            $table->enum('status', ['COMPROVADO', 'ABERTO']);
            $table->integer('volumes');
            $table->double('valor');
            $table->dateTime('dt_emis');
            $table->dateTime('dt_entrega')->nullable();
            $table->timestamps();

            $table->foreign('destinatario_id')->references('id')->on('destinatarios');
            $table->foreign('transportador_id')->references('id')->on('transportadores');
            $table->foreign('remetente_id')->references('id')->on('remetentes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notas');
    }
};
