<?php

namespace App\Http\Controllers;
use App\Models\Relleno;
use Illuminate\Http\Request;

class RellenoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try
        {
            $rellenos = Relleno::all();
            return $rellenos;
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
        return view('admin.rellenos');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        try{
            $relleno = new Relleno();
            $relleno->nombre = $request->nombre;
           if ($relleno->save() >= 1)
           {
            return response()->json(['status'=>'ok','data'=>$relleno],201);
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
            $relleno = Relleno::findOrFail($id);
            return $relleno;
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
            $relleno = Relleno::findOrFail($id);
            $relleno->nombre = $request->nombre;
           if ($relleno->update() >= 1)
           {
            return response()->json(['status'=>'ok','data'=>$relleno],202);
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
            $relleno = Relleno::findOrFail($id);
           if ($relleno->delete() >= 1)
           {
            return response()->json(['status'=>'ok','data'=>$relleno],200);
           }
         } catch(\Exception $e)
                 {
                    return $e->getMessage();
                 }
    }
}
