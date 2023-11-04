<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use Illuminate\Support\Facades\DB;
use App\Models\DetallePedido;
class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try{
            $pedidos = Pedido::all();
    
            $response = $pedidos->toArray();
            $i = 0;
            foreach($pedidos as $pedido)
            {
                $response[$i]['user'] =$pedido->user->toArray();
                $response =$pedido->detalle_pedidos->toArray();
    
                $f=0;
                foreach($pedido->detalle_pedidos as $d)
                {
                    $detalle[$f]['producto'] = $d->producto->toArray();
                    $detalle[$f]['producto']['sabor'] = $d->producto->sabor->toArray();
                    $detalle[$f]['producto']['catalogo'] = $d->producto->catalogo->toArray();
                    $i++;
                }
                return $response[$i]['detallePedido'] = $detalle;
                $i++;
            }
           return $response;
        }catch(\Exception $e)
        {
            return $e->getMessage();
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.pedidos');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Crea una instancia de Pedido y guarda los detalles del pedido
            $pedido = new Pedido();
            $pedido->direccion_envio = $request->direccionEnvio;
            $pedido->total = $request->total;
            $pedido->telefono = $request->telefono;
            $pedido->costo_envio = $request->costoEnvio;
            $pedido->fecha = $request->fecha;
            $pedido->estado = 'Pendiente'; // Cambia esto según tu lógica de estados
            $pedido->user_id = $request->user['id'];

            if (!$pedido->save()) {
                DB::rollBack();
                return response()->json(['status' => 'fail', 'message' => 'Error al crear el pedido'], 500);
            }

            // Guarda los detalles del pedido
            foreach ($request->detallePedido as $detalle) {
                $detallePedido = new DetallePedido();
                $detallePedido->producto_id = $detalle['producto']['id'];
                $detallePedido->pedido_id = $pedido->id;
                // Agrega otros campos del detalle de pedido si los tienes
                if (!$detallePedido->save()) {
                    DB::rollBack();
                    return response()->json(['status' => 'fail', 'message' => 'Error al guardar los detalles del pedido'], 500);
                }
            }

            DB::commit();

            return response()->json(['status' => 'ok', 'message' => 'Pedido guardado con éxito'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
       // $cartItems=0;// Obtén los productos del carrito   
        //return view('carrito', ['cartItems' => $cartItems]); // Obtén los productos del carrito
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        try{
            $errores = 0;
            DB::beginTransaction();
        
        $pedido = Pedido::findOrFail($id);
        if($request->estado == 'P')
        {
            //cuando se entrega el o los vehiculos al cliente
           $pedido->estado = $request->estado;
           $pedido->total = $request->total;
           //$alquiler->observaciones = $request->observaciones;
           if($pedido->update()<=0){
            $errores++;
           }
           $detalle = $request->detallePedido;
           foreach($detalle as $key => $det)
           {
            $detallePedido = DetallePedido::findOrFail($det['id']);
            $detallePedido->precio_unitario = $det['precioUnitario'];
            $detallePedido->cantidad = $det['cantidad'];
            $detallePedido->descuento_obtenido = $det ['descuentoObtenido'];
            if($pedido->update()<=0){
                $errores++;
           }
        }
         }
        elseif($request->estado == 'C')
        { 
            //cuando el clientedevuelve los vehiculos 
            $pedido->estado = $request->estado;
            $pedido->fecha = $request->fecha;
           // $pedido->observaciones = $request->observaciones;
            if($pedido->update()<=0){
                $errores++;
               }
               $detalle = $request->detallePedido;
               foreach($detalle as $key => $det)
               {
                $detallePedido = DetallePedido::findOrFail($det['id']);
                $detallePedido->precio_unitario = $det['precioUnitario'];
                $detallePedido->cantidad = $det['cantidad'];
                $detallePedido->descuento_obtenido = $det ['decuentoObtenido'];
                if($pedido->update()<=0){
                    $errores++;
               }
             }
        }
        else
        {
            //cuando la reserva sea cancelada (el cliente ya no hara el alquiler)
            //cambiar el estado a C de cancelado el alquiler
            $pedido->estado = $request->estado;
            //$pedido->observaciones = $request->observaciones;
            if($pedido->update()<=0){
             $errores++;
            }
        }
        if ($errores == 0){
        DB::commit();
        return response()->json(['status'=>'ok','data'=>$pedido],202); 
        }else{
            DB::rollBack();
            return response()->json(['status'=>'fail','data'=>null],409);
        }
        
    } catch(\Exception $e)
    {
        DB::rollBack();
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
