<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecepcionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recepcions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('huesped_id')->unsigned();
            $table->bigInteger('habitacion_id')->unsigned();
            $table->date('fecha_entrada');
            $table->date('fecha_salida');
            $table->integer('cant_dias');
            $table->string('medio_pago');
            $table->decimal('monto',10,2);
            $table->foreign('huesped_id')->references('id')->on('huespeds')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('habitacion_id')->references('id')->on('habitacions')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recepcions');
    }
}
