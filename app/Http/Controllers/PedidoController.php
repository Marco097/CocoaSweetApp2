<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use Illuminate\Support\Facades\DB;
use App\Models\DetallePedido;
//use Darryldecode\Cart\Cart;
use Cart;
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
                $response[$i]['user'] = $pedido->user->toArray();
                $response = $pedido->detalle_pedidos->toArray();
    
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
            $pedido->estado = 'P'; // Cambia esto según tu lógica de estados
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
    public function show($estado)
    {
        try{
            $pedidos = Pedido::where('estado', '=', $estado)->get();
    
            $response = $pedidos->toArray();
            $i = 0;
            foreach($pedidos as $pedido)
            {
                $response[$i]['user'] = $pedido->user->toArray();
                $detalle = $pedido->detalle_pedidos->toArray();
    
                $f=0;
                foreach($pedido->detalle_pedidos as $d)
                {
                    $detalle[$f]['producto'] = $d->producto->toArray();
                    $detalle[$f]['producto']['relleno'] = $d->producto->relleno->toArray();
                    $detalle[$f]['producto']['catalogo'] = $d->auto->catalogo->toArray();
                    $f++;
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
        $pedido-> estado =$request->estado;
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
            if (!$detallePedido->save()) {
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
            if(!$pedido->save()){
                $errores++;
               }
               $detalle = $request->detallePedido;
               foreach($detalle as $key => $det)
               {
                $detallePedido = DetallePedido::findOrFail($det['id']);
                $detallePedido->precio_unitario = $det['precioUnitario'];
                $detallePedido->cantidad = $det['cantidad'];
                $detallePedido->descuento_obtenido = $det ['descuentoObtenido'];
                if(!$pedido->save()){
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
        if (!$pedido->save()) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'message' => 'Error al actualizar el pedido'], 500);
        }

        DB::commit();

        return response()->json(['status' => 'ok', 'message' => 'Pedido actualizado con éxito'], 200);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function finalizar(Request $request)
{
    // Validar los datos del formulario
    $this->validate($request, [
        'direccion_envio' => 'required',
        'telefono' => 'required',
        'fecha_entrega' => 'required|date|after_or_equal:today' ,
    ]);

    // Crear un nuevo pedido
    $pedido = new Pedido();
    $pedido->correlativo = $this->getCorrelativo();
    $pedido->direccion_envio = $request->direccion_envio;
    $pedido->telefono = $request->telefono;
    $pedido->fecha_entrega = $request->fecha_entrega;
    $pedido->total = Cart::getTotal();
    $pedido->estado = 'P'; // Cambia esto según lógica de estados
    $pedido->user_id = auth()->user()->id; // que el usuario esté autenticado

    if (!$pedido->save()) {
        return back()->with('error', 'Hubo un problema al guardar el pedido.');
    }

    // Guardar los detalles del pedido
    foreach (Cart::getContent() as $item) {
        $detallePedido = new DetallePedido();
        $detallePedido->pedido_id = $pedido->id;
        $detallePedido->producto_id = $item->id;
        $detallePedido->cantidad = $item->quantity;
       // $detallePedido->precio_uni = $item->price;
        //$detallePedido->cobertura_id = $item->attributes->cobertura;

        if (!$detallePedido->save()) {
            return back()->with('error', 'Hubo un problema al guardar los detalles del pedido.');
        }
    }

    // Vaciar el carrito
    Cart::clear();

    return back()->with('success', 'Pedido realizado con éxito.');
}

private function getCorrelativo()
{
    $result = DB::select("SELECT
    CONCAT(TRIM(YEAR(CURDATE())),LPAD(TRIM(MONTH(CURDATE())),2,0),LPAD(IFNULL(MAX(TRIM(SUBSTRING(correlativo,7,4))),0)+1,4,0)) as correlativo FROM pedidos WHERE SUBSTRING(correlativo,1,6) = CONCAT(TRIM(YEAR(CURDATE())),LPAD(TRIM(MONTH(CURDATE())),2,0))");
    return $result[0]->correlativo;
}

public function showByState(Request $request)
    {
        try{
            $pedidos = Pedido::where('estado', '=', $request->estado)->get();
    
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
                    $detalle[$f]['producto']['relleno'] = $d->producto->relleno->toArray();
                    $detalle[$f]['producto']['catalogo'] = $d->auto->catalogo->toArray();
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


    public function changeState(Request $request)
    {
        $pedido = Pedido::findOrFail($request->id);
        if($request->estado == 'E')
        {
            //cuando se entrega el o los vehiculos al cliente
            
        }
        else
        {
            //cuando la reserva sea cancelada (el cliente ya no hara el alquiler)
            //cambiar el estado a C de cancelado el alquiler
        }

    }
}
