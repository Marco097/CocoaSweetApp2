<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;
use App\Models\Producto;
use PDF;
class PDFController extends Controller
{
    public function pdfProductos()
    {
        $productos = Producto::join('rellenos','productos.relleno_id','rellenos.id')
        ->join('catalogos','productos.catalogo_id','catalogos.id')
        ->select(
            'productos.nombre',
            'productos.descripcion',
            'productos.precio',
            'productos.existencias',
            'productos.hecho',
            'productos.vencimiento',
            'rellenos.nombre as relleno',
            'catalogos.nombre as catalogo'
            //'autos.estado'
        )
        ->orderBy('productos.id', 'DESC')->get();
        $pdf = \PDF::loadView('admin.listProductosPDF',compact('productos'));
        return $pdf->stream('productos.pdf');
    }
    public function pdfReservas(Request $request)
    {
        $fecha1 = $request->fechaInicio;
        $fecha2 = $request->fechaFinal;

        $reservas = Pedido::join('users','pedidos.user_id','users.id')
        ->select(
            'pedidos.id','pedidos.correlativo','pedidos.direccion_envio',
            'pedidos.telefono','pedidos.total','pedidos.fecha_entrega','users.name'
        )
        ->where('pedidos.estado','=','P')
        ->whereBetween('fecha_entrega',[$fecha1,$fecha2])
        ->orderBy('pedidos.id','DESC')->get();

            $datos = $reservas;
            $i = 0;
            foreach($reservas as $p)
            {
                $detalle_p = Pedido::join('detalle_pedidos','detalle_pedidos.pedido_id','pedidos.id')
                ->join('productos','detalle_pedidos.producto_id','productos.id')
                ->join('rellenos','productos.relleno_id','rellenos.id')
                ->join('catalogos','productos.catalogo_id','catalogos.id')
                ->select('productos.nombre','rellenos.nombre as relleno','catalogos.nombre as catalogo',
                'productos.existencias','productos.precio','detalle_pedidos.cantidad')
                ->where('detalle_pedidos.pedido_id','=',$p->id)->get();
                $datos[$i]['detalle'] = $detalle_p;
                $i++;
            }
            $pdf = \PDF::loadView('admin.listProductosPDF',compact(['datos','fecha1','fecha2']));

        return $pdf->stream('reservas.pdf');
    }

    public function viewReservas()
    {
        return view('admin.reportReservas');

    }
}
