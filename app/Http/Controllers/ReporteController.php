<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $desde = $request->date('desde')?->startOfDay() ?? now()->startOfMonth();
        $hasta = $request->date('hasta')?->endOfDay() ?? now()->endOfDay();
        $ventas = Venta::with(['cliente', 'user'])->whereBetween('fecha', [$desde, $hasta])->latest('fecha')->paginate(20, ['*'], 'ventas_page')->withQueryString();
        $resumen = Venta::whereBetween('fecha', [$desde, $hasta])->selectRaw('COUNT(*) as cantidad, COALESCE(SUM(total), 0) as total, COALESCE(SUM(iva), 0) as iva')->first();
        $masVendidos = DB::table('detalle_ventas')->join('ventas', 'ventas.id_venta', '=', 'detalle_ventas.id_venta')->join('productos', 'productos.id_producto', '=', 'detalle_ventas.id_producto')->whereBetween('ventas.fecha', [$desde, $hasta])->select('productos.nombre', 'productos.codigo_barra', DB::raw('SUM(detalle_ventas.cantidad) as cantidad'))->groupBy('productos.id_producto', 'productos.nombre', 'productos.codigo_barra')->orderByDesc('cantidad')->limit(10)->get();
        $stockCritico = Producto::whereColumn('stock', '<=', 'stock_critico')->orderBy('stock')->limit(20)->get();

        return view('reportes.index', compact('ventas', 'resumen', 'masVendidos', 'stockCritico', 'desde', 'hasta'));
    }

    public function auditoria(Request $request)
    {
        $auditorias = Auditoria::with('usuario')->when($request->filled('accion'), fn ($query) => $query->where('accion', $request->string('accion')->toString()))->latest()->paginate(30)->withQueryString();

        return view('reportes.auditoria', compact('auditorias'));
    }

    public function exportar(Request $request, string $formato)
    {
        abort_unless(in_array($formato, ['csv', 'pdf'], true), 404);
        $desde = $request->date('desde')?->startOfDay() ?? now()->startOfMonth();
        $hasta = $request->date('hasta')?->endOfDay() ?? now()->endOfDay();
        $ventas = Venta::with(['cliente', 'user'])->whereBetween('fecha', [$desde, $hasta])->latest('fecha')->get();

        if ($formato === 'csv') {
            return response()->streamDownload(function () use ($ventas): void {
                $handle = fopen('php://output', 'w');
                fwrite($handle, "\xEF\xBB\xBF");
                fputcsv($handle, ['Fecha', 'Cliente', 'Usuario', 'Medio de pago', 'Total', 'IVA'], ';');
                foreach ($ventas as $venta) {
                    fputcsv($handle, [$venta->fecha->format('Y-m-d H:i'), $venta->cliente?->nombre, $venta->user?->name, $venta->medio_pago, $venta->total, $venta->iva], ';');
                }
                fclose($handle);
            }, 'reporte-ventas-'.$desde->format('Y-m-d').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
        }

        return Pdf::loadView('reportes.export-pdf', compact('ventas', 'desde', 'hasta'))->download('reporte-ventas-'.$desde->format('Y-m-d').'.pdf');
    }
}
