<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturacionInventarioTable extends Migration
{
    public function up()
    {
        Schema::create('facturacion_inventario', function (Blueprint $table) {
            $table->id(); // Si decides usar un ID para la tabla pivote
            $table->unsignedBigInteger('facturacion_id'); // Referencia a la tabla facturaciones
            $table->unsignedBigInteger('inventario_id'); // Referencia a la tabla inventarios
            $table->integer('cantidad'); // La cantidad del producto
            $table->decimal('descuento', 5, 2); // Por ejemplo, 0.10 para un 10%
            $table->decimal('valor_final', 10, 2); // Valor final del producto
            $table->timestamps();

            // Claves foráneas
            $table->foreign('facturacion_id')->references('id')->on('facturaciones')->onDelete('cascade');
            $table->foreign('inventario_id')->references('id')->on('inventarios')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('facturacion_inventario');
    }
}
