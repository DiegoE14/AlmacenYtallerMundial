<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNumeroFacturaToFacturacionesTable extends Migration
{
    public function up()
    {
        Schema::table('facturaciones', function (Blueprint $table) {
            // Elimina la restricción de unicidad
            $table->integer('numero_factura')->after('id'); // Asegúrate de que no tenga una restricción única
        });
    }

    public function down()
    {
        Schema::table('facturaciones', function (Blueprint $table) {
            $table->dropColumn('numero_factura'); // Elimina la columna si se revierte la migración
        });
    }
}
