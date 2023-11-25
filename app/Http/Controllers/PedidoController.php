<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use Illuminate\Support\Facades\DB;
use App\Models\DetallePedido;
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
            foreach($pedidos as $pedido){
                $response[$i]['user'] = $pedido->user->toArray();
                $detalle = $pedido->detalle_pedidos->toArray();
                $f=0;
                foreach($pedido->detalle_pedidos as $d){
                    $detalle[$f]['producto'] = $d->producto->toArray();
                    $detalle[$f]['producto']['catalogo'] = $d->producto->catalogo->toArray();
                    $f++;
                }
                return $response[$i]['detallePedidos'] = $detalle;
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
            $pedido->hora = $request->hora;
            $pedido->total = $request->total;
            $pedido->telefono = $request->telefono;
            $pedido->costo_envio = $request->costoEnvio;
            $pedido->fecha = $request->fecha;
            $pedido->estado = 'R'; // Cambia esto según tu lógica de estados
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
            $pedidos = Pedido::where('estado','=',$estado)->get();
            $response = $pedidos->toArray();
            $i=0;
            foreach($pedidos as $pedido){
            $response[$i]['user'] = $pedido->user->toArray();
            $detalle = $pedido->detalle_pedidos->toArray();

            $f=0;
            foreach($pedido-> detalle_pedidos as $d){
                    $detalle[$f]['producto'] = $d->producto->toArray();
                    $detalle[$f]['producto']['relleno'] = $d->producto->relleno->toArray();
                    $detalle[$f]['producto']['catalogo'] = $d->producto->catalogo->toArray();
                    $detalle[$f]['cantidad'] = $d->cantidad;
                    $f++;
                }
                $response[$i]['detallePedidos'] = $detalle;
                $i++;
            }
            return $response;
        }catch(\Exception $e){
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
        if($request->estado == 'E'){
            //cuando se entrega el o los vehiculos al cliente
           $pedido->estado = $request->estado;
           $pedido->total = $request->total;
           //$alquiler->observaciones = $request->observaciones;
           if($pedido->update()<=0){
            $errores++;
           }
           $detalle = $request->detallePedido;
           foreach($detalle as $key => $det){
            $detallePedido = DetallePedido::findOrFail($det['id']);
            $detallePedido->cantidad = $det['cantidad'];
            if (!$detallePedido->update()<=0) {
                $errores++;
            }
            
        }
         }elseif($request->estado == 'A'){ 
            //cuando el clientedevuelve los vehiculos 
            $pedido->estado = $request->estado;
            $pedido->fecha_entrega = $request->fecha_entrega;
           // $pedido->observaciones = $request->observaciones;
            if(!$pedido->update()<=0){
                $errores++;
               }
               $detalle = $request->detallePedido;
               foreach($detalle as $key => $det){
                $detallePedido = DetallePedido::findOrFail($det['id']);
                $detallePedido->precio_unitario = $det['precioUnitario'];
                $detallePedido->cantidad = $det['cantidad'];
                if($detallePedido->update()<=0){
                    $errores++;
               }
             }
        }else{
            //cuando la reserva sea cancelada (el cliente ya no hara el alquiler)
            //cambiar el estado a C de cancelado el alquiler
            $pedido->estado = $request->estado;
            //$pedido->observaciones = $request->observaciones;
            if($pedido->update()<=0){
             $errores++;
            }
        }
        if ($errores == 0) {
            DB::commit();
            return response()->json(['status'=>'ok','data'=>$pedido],202);
        }else{
            DB::rollBack();
            return response()->json(['status'=>'fail','data'=>null],409);
        }
    }catch(\Exception $e){
       return $e->getMessage(); 
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
    ], [
        'direccion_envio.required' => 'El campo Dirección de Envío es obligatorio.',
        'telefono.required' => 'El campo Teléfono es obligatorio.',
        'fecha_entrega.required' => 'El campo Fecha de Entrega es obligatorio.',
        'fecha_entrega.date' => 'El campo Fecha de Entrega debe ser una fecha válida.',
        'fecha_entrega.after_or_equal' => 'El campo Fecha de Entrega debe ser igual o posterior a hoy.',
    ]);

    // Crear un nuevo pedido
    $pedido = new Pedido();
    $pedido->correlativo = $this->getCorrelativo();
    $pedido->direccion_envio = $request->direccion_envio;
    $pedido->hora = $request->hora;
    $pedido->telefono = $request->telefono;
    $pedido->fecha_entrega = $request->fecha_entrega;
    $pedido->total = Cart::getTotal();
    $pedido->estado = 'R'; // Cambia esto según lógica de estados
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

    return redirect()->route('cart.index')->with('success_msg', 'Pedido realizado con éxito.');
}

private function getCorrelativo()
{
    $result = DB::select("SELECT
    CONCAT(TRIM(YEAR(CURDATE())),LPAD(TRIM(MONTH(CURDATE())),2,0),LPAD(IFNULL(MAX(TRIM(SUBSTRING(correlativo,7,4))),0)+1,4,0)) as correlativo FROM pedidos WHERE SUBSTRING(correlativo,1,6) = CONCAT(TRIM(YEAR(CURDATE())),LPAD(TRIM(MONTH(CURDATE())),2,0))");
    return $result[0]->correlativo;
}

 /**
     * Cambiar el estado de un pedido.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeState(Request $request, $id)
    {
        try {
            // Buscar el pedido por ID
            $pedido = Pedido::findOrFail($id);

            // Validar el nuevo estado (puedes personalizar esto según tus estados)
            $nuevoEstado = $request->input('nuevoEstado');
            if (!in_array($nuevoEstado, ['R', 'E', 'A'])) {
                return response()->json(['status' => 'fail', 'message' => 'Estado no válido'], 422);
            }

            // Cambiar el estado del pedido
            $pedido->estado = $nuevoEstado;

            // Guardar el pedido actualizado
            if ($pedido->save()) {
                return response()->json(['status' => 'ok', 'message' => 'Estado del pedido cambiado con éxito'], 200);
            } else {
                return response()->json(['status' => 'fail', 'message' => 'Error al cambiar el estado del pedido'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error interno del servidor'], 500);
        }
    }

    public function cancelarPedido($id)
{
    try {
        $pedido = Pedido::findOrFail($id);
        $pedido->estado = 'C';  // Cambia 'C' según tus estados
        $pedido->save();

        return response()->json(['status' => 'ok', 'message' => 'Pedido cancelado con éxito'], 200);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => 'Error al cancelar el pedido'], 500);
    }
}
}
    

