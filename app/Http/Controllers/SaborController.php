<?php

namespace App\Http\Controllers;
use App\Models\Sabor;
use Illuminate\Http\Request;

class SaborController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try
        {
            $sabores = Sabor::all();
            return $sabores;
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
        return view('admin.sabores');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        try{
            $sabor = new Sabor();
            $sabor->nombre = $request->nombre;
           if ($sabor->save() >= 1)
           {
            return response()->json(['status'=>'ok','data'=>$sabor],201);
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
            $sabor = Sabor::findOrFail($id);
            return $sabor;
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
            $sabor = Sabor::findOrFail($id);
            $sabor->nombre = $request->nombre;
           if ($sabor->update() >= 1)
           {
            return response()->json(['status'=>'ok','data'=>$sabor],202);
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
            $sabor = Sabor::findOrFail($id);
           if ($sabor->delete() >= 1)
           {
            return response()->json(['status'=>'ok','data'=>$sabor],200);
           }
         } catch(\Exception $e)
                 {
                    return $e->getMessage();
                 }
    }
}
