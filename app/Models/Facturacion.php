<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facturacion extends Model
{
    use HasFactory;

    // Especificar los campos que se pueden asignar masivamente
    protected $fillable = [
        'inventario_id',
        'cantidad',
        'total',
        'numero_factura',  // Agregado
        'valor_final',      // Agregado (si es necesario)
        'descuento',        // Agregado (si es necesario)
    ];

    // Definir la relación con el modelo Inventario
    public function inventario()
    {
        return $this->belongsTo(Inventario::class);
    }

    public function productos()
    {
        return $this->belongsToMany(Inventario::class)
            ->withPivot('cantidad', 'descuento', 'valor_final'); // Ajusta según lo que necesites
    }

    // Especificar el nombre correcto de la tabla
    protected $table = 'facturaciones';
}
