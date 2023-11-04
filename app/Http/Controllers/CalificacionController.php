<?php

namespace App\Http\Controllers;
use App\Models\Calificacion;
use Illuminate\Http\Request;

class CalificacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try
        {
            $calificaciones = Calificacion::all();
            $response = $calificaciones->toArray();
            $i = 0;
            foreach($calificaciones as $calificacion) {
                $response[$i]["user"] = $calificacion->user->toArray();
                $i++;
            }
            return $calificaciones;
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
            $calificacion = new Calificacion();
            $calificacion->calificacion = $request->calificacion;
            $calificacion->comentario = $request->comentario;
            $calificacion->user_id = $request->user['id'];
            
            if($calificacion->save() >= 1){
                return response()->json(['status'=>'ok','data'=> $calificacion],201);
            }else{
                return response()->json(['status'=>'fail','data'=> null],409);
            }
      }catch(\Exception $e){
          return $e->getMessage();
      }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        try {
            $calificacion = Calificacion::findOrFail($id);
            $response = $calificacion->toArray();
                $response["user"] = $calificacion->user->toArray();
            return $response;
        } catch (\Exception $e) {
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
            $calificacion = Calificacion::findOrFail($id);
            $calificacion->calificacion = $request->calificacion;
            $calificacion->comentario = $request->comentario;
            $calificacion->user_id = $request->user['id'];
           
            if ($calificacion->update() >= 1) {
                return response()->json(['status'=>'ok','data'=>$calificacion],202);
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
        try {
            $calificacion = Calificacion::findOrFail($id);
            if ($calificacion->delete() >= 1) {
                return response()->json(['status' => 'ok', 'data' => null], 201);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
