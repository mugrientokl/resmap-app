<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    // Registrar una nueva venta en el POS
    public function store(Request $request)
    {
        // Validación básica de los datos entrantes
        $request->validate([
            'tipo_documento' => 'required|string', // 'Boleta Electrónica' o 'Factura Electrónica'
            'medio_pago' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'id_cliente' => 'nullable|exists:clientes,id_cliente',
            'detalles' => 'required|array|min:1',
            'detalles.*.id_producto' => 'required|exists:productos,id_producto',
            'detalles.*.cantidad' => 'required|integer|min:1',
        ]);

        try {
            // Usamos una transacción para asegurar la integridad (si falla algo, no se descuenta stock a medias)
            $resultado = DB::transaction(function () use ($request) {
                $neto = 0;
                $detallesCalculados = [];

                // 1. Calcular subtotales y total neto (en Chile los precios suelen incluir IVA, 
                // aquí calculamos asumiendo que el precio del producto incluye el 19%)
                foreach ($request->detalles as $item) {
                    $producto = Producto::findOrFail($item['id_producto']);
                    
                    if ($producto->stock < $item['cantidad']) {
                        throw new \Exception("Stock insuficiente para el producto: {$producto->nombre}");
                    }

                    $subtotal = $producto->precio * $item['cantidad'];
                    $neto += $subtotal;

                    $detallesCalculados[] = [
                        'producto' => $producto,
                        'cantidad' => $item['cantidad'],
                        'precio_unitario' => $producto->precio,
                        'subtotal' => $subtotal
                    ];
                }

                // Cálculo de IVA (19% en Chile) y Total
                // Si el neto ingresado ya tiene IVA, se desglosa matemáticamente:
                $totalBruto = $neto; 
                $montoNeto = round($totalBruto / 1.19, 2);
                $montoIva = round($totalBruto - $montoNeto, 2);

                // 2. Crear la Venta principal
                $venta = Venta::create([
                    'fecha' => now(),
                    'tipo_documento' => $request->tipo_documento,
                    'folio_sii' => null, // Se asignará al timbrar electrónicamente con el SII
                    'neto' => $montoNeto,
                    'iva' => $montoIva,
                    'total' => $totalBruto,
                    'medio_pago' => $request->medio_pago,
                    'estado_sii' => 'Emitido',
                    'user_id' => $request->user_id,
                    'id_cliente' => $request->id_cliente,
                ]);

                // 3. Registrar los detalles y descontar el stock del inventario
                foreach ($detallesCalculados as $det) {
                    DetalleVenta::create([
                        'id_venta' => $venta->id_venta,
                        'id_producto' => $det['producto']->id_producto,
                        'cantidad' => $det['cantidad'],
                        'precio_unitario' => $det['precio_unitario'],
                        'subtotal' => $det['subtotal']
                    ]);

                    // Descontar stock
                    $det['producto']->decrement('stock', $det['cantidad']);
                }

                return $venta;
            });

            return response()->json([
                'message' => 'Venta registrada con éxito y stock actualizado.',
                'venta' => $resultado
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }
}