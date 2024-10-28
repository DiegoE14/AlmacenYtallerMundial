<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddValorFinalToFacturacionesTable extends Migration
{
    public function up()
    {
        Schema::table('facturaciones', function (Blueprint $table) {
            $table->decimal('valor_final', 10, 2)->after('cantidad'); // Agrega la columna valor_final
        });
    }

    public function down()
    {
        Schema::table('facturaciones', function (Blueprint $table) {
            $table->dropColumn('valor_final'); // Elimina la columna valor_final si se revierte la migración
        });
    }
}
