<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promocion;
class PromocionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try
        {
            $promociones = Promocion::all();
            return $promociones;
        }
            catch(\Exception $e)
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
        return view('admin.promociones');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        try{
            $promocion = new Promocion();
            $promocion->nombre = $request->nombre;
            $promocion->descripcion = $request->descripcion;
            $promocion->tipo = $request->tipo;
            $promocion->descuento = $request->descuento;
            $promocion->fecha_inicio = $request->fecha_inicio;
            $promocion->fecha_final = $request->fecha_final;
            $promocion->precio_promocion = $request->precio_promocion;
            $promocion->estado = $request->estado;
           if ($promocion->save() >= 1)
           {
            return response()->json(['status'=>'ok','data'=>$promocion],201);
           }else{
            return response()->json(['status'=>'fail','data'=>null],409);
           }
         } catch(\Exception $e)
                 {
                    return $e->getMessage();
                 }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        try{
            $promocion = Promocion::findOrFail($id);
            return $promocion;
        }
        catch(\Exception $e)
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
            $promocion = Promocion::findOrFail($id);
            if($request->estado == 'A')
            {
                $promocion->nombre = $request->nombre;
            $promocion->descripcion = $request->descripcion;
            $promocion->tipo = $request->tipo;
            $promocion->descuento = $request->descuento;
            $promocion->fecha_inicio = $request->fecha_inicio;
            $promocion->fecha_final = $request->fecha_final;
            $promocion->precio_promocion = $request->precio_promocion;
           if ($promocion->update() >= 1)
           {
            return response()->json(['status'=>'ok','data'=>$promocion],202);
           }
         
    }elseif($request->estado == 'I')
    {
            $promocion->nombre = $request->nombre;
            $promocion->descripcion = $request->descripcion;
            $promocion->tipo = $request->tipo;
            $promocion->descuento = $request->descuento;
            $promocion->fecha_inicio = $request->fecha_inicio;
            $promocion->fecha_final = $request->fecha_final;
            $promocion->precio_promocion = $request->precio_promocion;
           if ($promocion->update() >= 1)
           {
            return response()->json(['status'=>'ok','data'=>$promocion],202);
           }
    }
} catch(\Exception $e)
    {
       return $e->getMessage();
    }
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        try{
            $promocion = Promocion::findOrFail($id);
           if ($promocion->delete() >= 1)
           {
            return response()->json(['status'=>'ok','data'=>$promocion],200);
           }
         } catch(\Exception $e)
                 {
                    return $e->getMessage();
                 }
    }

    public function showByState(Request $request)
    {
        try{
            $promociones = Promocion::where('estado', '=', $request->estado)->get();
    
            $response = $promociones->toArray();
            //$i = 0;

           return $response;
        }catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }
}
