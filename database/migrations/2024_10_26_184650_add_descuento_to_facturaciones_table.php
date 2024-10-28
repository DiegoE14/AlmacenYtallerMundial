<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDescuentoToFacturacionesTable extends Migration
{
    public function up()
    {
        Schema::table('facturaciones', function (Blueprint $table) {
            $table->boolean('descuento')->default(false)->after('valor_final'); // Agrega la columna descuento
        });
    }

    public function down()
    {
        Schema::table('facturaciones', function (Blueprint $table) {
            $table->dropColumn('descuento'); // Elimina la columna descuento si se revierte la migración
        });
    }
}
