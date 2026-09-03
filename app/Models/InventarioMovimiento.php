<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventarioMovimiento extends Model
{
    protected $table = 'inventario_movimientos';

    protected $primaryKey = 'id_movimiento';

    protected $fillable = [
        'id_producto', 'user_id', 'tipo', 'cantidad', 'stock_anterior',
        'stock_nuevo', 'motivo', 'id_venta',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'id_venta', 'id_venta');
    }
}
