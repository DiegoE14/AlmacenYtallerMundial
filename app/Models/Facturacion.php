<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facturacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventario_id',
        'cantidad',
        'total',
    ];

    public function inventario()
    {
        return $this->belongsTo(Inventario::class);
    }
}
