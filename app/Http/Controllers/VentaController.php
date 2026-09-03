<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\User;
use App\Models\Venta;
use App\Notifications\ProductoStockCritico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    // Registrar una nueva venta en el POS con sincronización automática de cliente por RUT
    public function store(Request $request)
    {
        // Validación de datos entrantes incluyendo los campos del cliente
        $request->validate([
            'tipo_documento' => 'required|string', // 'Boleta Electrónica' (39) o 'Factura Electrónica' (33)
            'medio_pago' => 'required|string',
            // Datos del cliente para búsqueda o creación automática por RUT
            'rut' => 'required|string',
            'nombre_cliente' => 'required|string|max:255',
            'correo_cliente' => 'nullable|email|max:255',
            'telefono_cliente' => 'nullable|string|max:50',
            'direccion_cliente' => 'nullable|string|max:255',
            // Detalles de los repuestos
            'detalles' => 'required|array|min:1',
            'detalles.*.id_producto' => 'required|exists:productos,id_producto',
            'detalles.*.cantidad' => 'required|integer|min:1',
        ]);

        try {
            // Usamos una transacción para asegurar la integridad total de la venta y el stock
            $resultado = DB::transaction(function () use ($request) {

                // 1. Buscar el cliente por RUT o crearlo automáticamente si es nuevo en el sistema
                $cliente = Cliente::firstOrCreate(
                    ['rut' => $request->rut],
                    [
                        'nombre' => $request->nombre_cliente,
                        'correo' => $request->correo_cliente,
                        'telefono' => $request->telefono_cliente,
                        'direccion' => $request->direccion_cliente,
                    ]
                );

                // Actualizamos los datos por si el cliente ya existía y vino con información nueva
                $cliente->update([
                    'nombre' => $request->nombre_cliente,
                    'correo' => $request->correo_cliente ?? $cliente->correo,
                    'telefono' => $request->telefono_cliente ?? $cliente->telefono,
                    'direccion' => $request->direccion_cliente ?? $cliente->direccion,
                ]);

                $neto = 0;
                $detallesCalculados = [];

                // 2. Calcular subtotales, total neto y validar stock disponible de los repuestos
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
                        'subtotal' => $subtotal,
                    ];
                }

                // Cálculo de IVA (19% en Chile) y Total
                $totalBruto = $neto;
                $montoNeto = round($totalBruto / 1.19, 2);
                $montoIva = round($totalBruto - $montoNeto, 2);

                // 3. Crear la Venta principal asociada al cliente sincronizado
                $venta = Venta::create([
                    'fecha' => now(),
                    'tipo_documento' => $request->tipo_documento,
                    'folio_sii' => null, // Se asignará al timbrar electrónicamente con el SII
                    'neto' => $montoNeto,
                    'iva' => $montoIva,
                    'total' => $totalBruto,
                    'medio_pago' => $request->medio_pago,
                    'estado_sii' => 'Emitido',
                    'user_id' => $request->user()->id,
                    'id_cliente' => $cliente->id_cliente,
                ]);

                // 4. Registrar los detalles y descontar el stock del inventario
                foreach ($detallesCalculados as $det) {
                    DetalleVenta::create([
                        'id_venta' => $venta->id_venta,
                        'id_producto' => $det['producto']->id_producto,
                        'cantidad' => $det['cantidad'],
                        'precio_unitario' => $det['precio_unitario'],
                        'subtotal' => $det['subtotal'],
                    ]);

                    // Descontar stock
                    $det['producto']->decrement('stock', $det['cantidad']);
                }

                return [
                    'venta' => $venta,
                    'cliente' => $cliente,
                ];
            });

            foreach ($resultado['venta']->detalles as $detalle) {
                if ($detalle->producto->stock <= $detalle->producto->stock_critico) {
                    User::whereIn('rol', ['Administrador', 'Vendedor'])->get()
                        ->each(fn (User $user) => $user->notify(new ProductoStockCritico($detalle->producto)));
                }
            }

            return response()->json([
                'message' => 'Venta registrada con éxito, stock actualizado y cliente sincronizado.',
                'resultado' => $resultado,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
