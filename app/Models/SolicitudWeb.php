<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudWeb extends Model
{
    use HasFactory;

    protected $table = 'solicitud_webs';
    protected $primaryKey = 'id_solicitud';

    protected $fillable = [
        'fecha',
        'estado',
        'id_cliente',
        'detalles_productos'
    ];

    protected $casts = [
        'detalles_productos' => 'array',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }
}