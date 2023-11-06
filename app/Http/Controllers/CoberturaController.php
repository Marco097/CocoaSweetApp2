<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cobertura;
class CoberturaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try
        {
            $coberturas = Cobertura::all();
            return $coberturas;
            //return view('cart');

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
        return view('admin.coberturas');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        try{
            $cobertura = new Cobertura();
            $cobertura->nombre = $request->nombre;
            $cobertura->precio = $request->precio;
           if ($cobertura->save() >= 1)
           {
            return response()->json(['status'=>'ok','data'=>$cobertura],201);
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
            $cobertura = Cobertura::findOrFail($id);
            return $cobertura;
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
            $cobertura = Cobertura::findOrFail($id);
            $cobertura->nombre = $request->nombre;
            $cobertura->precio = $request->precio;
           if ($cobertura->update() >= 1)
           {
            return response()->json(['status'=>'ok','data'=>$cobertura],202);
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
            $cobertura = Cobertura::findOrFail($id);
           if ($cobertura->delete() >= 1)
           {
            return response()->json(['status'=>'ok','data'=>$cobertura],200);
           }
         } catch(\Exception $e)
                 {
                    return $e->getMessage();
                 }
    }

    //No borrar
    public function obtenerCoberturas()
    {
    $coberturas = Cobertura::all();
    return $coberturas;
    }
}
