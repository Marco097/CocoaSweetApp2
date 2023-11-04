<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormaPago;

class FormaPagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try
        {
            $pagos = FormaPago::all();
            return $pagos;
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
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        try{
            $pago = new FormaPago();
            $pago->metodo_pago = $request->metodo_pago;
           if ($pago->save() >= 1)
           {
            return response()->json(['status'=>'ok','data'=>$pago],201);
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
            $pago = FormaPago::findOrFail($id);
            return $pago;
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
            $pago = FormaPago::findOrFail($id);
            $pago->metodo_pago = $request->metodo_pago;
           if ($pago->update() >= 1)
           {
            return response()->json(['status'=>'ok','data'=>$pago],202);
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
            $pago = FormaPago::findOrFail($id);
           if ($pago->delete() >= 1)
           {
            return response()->json(['status'=>'ok','data'=>$pago],200);
           }
         } catch(\Exception $e)
                 {
                    return $e->getMessage();
                 }
    }
}
