<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Catalogo;

class CatalogoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try
        {
            $catalogos = Catalogo::all();
            return $catalogos;
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
        return view('admin.catalogos');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        try{
            $catalogo = new Catalogo();
            $catalogo->nombre = $request->nombre;
           if ($catalogo->save() >= 1)
           {
            return response()->json(['status'=>'ok','data'=>$catalogo],201);
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
            $catalogo = Catalogo::findOrFail($id);
            return $catalogo;
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
            $catalogo = Catalogo::findOrFail($id);
            $catalogo->nombre = $request->nombre;
           if ($catalogo->update() >= 1)
           {
            return response()->json(['status'=>'ok','data'=>$catalogo],202);
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
            $catalogo = Catalogo::findOrFail($id);
           if ($catalogo->delete() >= 1)
           {
            return response()->json(['status'=>'ok','data'=>$catalogo],200);
           }
         } catch(\Exception $e)
                 {
                    return $e->getMessage();
                 }  
    }
}
