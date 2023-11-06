<?php

namespace App\Http\Controllers;
use App\Models\Banco;
use Illuminate\Http\Request;
class BancoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try
        {
            $bancos = Banco::all();
            return $bancos;
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
        return view('admin.bancos');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        try{
            $banco = new Banco();
            $banco->nombre = $request->nombre;
           if ($banco->save() >= 1)
           {
            return response()->json(['status'=>'ok','data'=>$banco],201);
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
            $banco = Banco::findOrFail($id);
            return $banco;
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
            $banco = Banco::findOrFail($id);
            $banco->nombre = $request->nombre;
           if ($banco->update() >= 1)
           {
            return response()->json(['status'=>'ok','data'=>$banco],202);
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
            $banco = Banco::findOrFail($id);
           if ($banco->delete() >= 1)
           {
            return response()->json(['status'=>'ok','data'=>$banco],200);
           }
         } catch(\Exception $e)
                 {
                    return $e->getMessage();
                 }
    }

    //No borrar
    public function obtenerBancos()
{
    $bancos = Banco::all();
    return $bancos;
}
}
