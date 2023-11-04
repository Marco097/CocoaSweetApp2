<?php

namespace App\Http\Controllers;
use App\Models\Producto;
use App\Models\ProductoCobertura;
use App\Models\ProductoPromocion;
use App\Models\ProductoSabor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
//use App\Http\Controllers\CoberturaController;
use App\Models\Cobertura;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try {
            // Obtener todos los productos con sus relaciones
            $productos = Producto::with(['producto_sabores', 'relleno', 'catalogo', 'producto_promociones', 'producto_coberturas'])->get();
            
            // Iterar sobre los productos y cargar los sabores en un array
            $response = $productos->map(function ($producto) {
                $productoArray = $producto->toArray();
                
                // Obtener los sabores y agregarlos al array del producto
                $sabores = $producto->producto_sabores->map(function ($productoSabor) {
                    return $productoSabor->sabor->toArray();
                });
                
                $productoArray["sabores"] = $sabores->toArray();
                
                return $productoArray;
            });
            
            return $response;
        } catch (\Exception $e) {
            return response()->json(['status' => 'fail', 'data' => null], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.productos');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        try{
            $errores = 0;
            DB::beginTransaction();
            $producto = new Producto();
            $producto->nombre = $request->nombre;
            $producto->descripcion = $request->descripcion;
            $producto->precio = $request->precio;
            $producto->existencias = $request->existencias;
            $producto->hecho = $request->hecho;
            $producto->vencimiento = $request->vencimiento;
            //$producto->imagen = $request->imagen;
            $producto->relleno_id = $request->relleno['id'];
            $producto->catalogo_id = $request->catalogo['id'];
            
            //comprovando si viene una imagen
            if($request->hasFile('imagen')){
                //obteniendo el archivo de una imagen
                $imagen = $request->file('imagen');
                //generando un nombre unico para la imagen
                $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
                //subiendo la imagen a una carpeta del servidor
                $imagen->move(public_path('images/productos/'),$nombreImagen);
                $producto->imagen = $nombreImagen;
            }else{
                $producto->imagen = "none.jpg";
            }

            if($producto->save()<=0)
            {
                $errores++;
            }
            // Guardar las relaciones con sabores
            $sabore = $request->productoSabor;
            if (!is_null($sabore) && is_array($sabore)) 
            {
                foreach ($sabore as $key => $sb) {
                    $productoSabor = new ProductoSabor();
                    $productoSabor->sabor_id = $sb['id'];
                    $productoSabor->producto_id = $producto->id;
    
                    if ($productoSabor->save() <= 0) {
                        $errores++;
                    }
                }
            }
            //guardando la relcion de promociones 
            $promocio = $request->productoPromocion;
           if (!is_null($promocio) && is_array($promocio)) 
            {
                foreach ($promocio as $key => $promo) {
                    $productoPromocion = new ProductoPromocion();
                    $productoPromocion->promocion_id = $promo['id'];
                    $productoPromocion->producto_id = $producto->id;
    
                    if ($productoPromocion->save() <= 0) {
                        $errores++;
                    }
                }
            }
            //GUARDANDO LA COBERTURA
            
            $cobertu = $request->productoCobertura;
            if (!is_null($cobertu) && is_array($cobertu))
            {
                foreach ($cobertu as $key => $cob) {
                    $productoCobertura = new ProductoCobertura();
                    $productoCobertura->cobertura_id = $cob['id'];
                    $productoCobertura->producto_id = $producto->id;
    
                    if ($productoCobertura->save() <= 0) {
                        $errores++;
                    }
                }
            }       

            if($errores == 0)
            {
                DB::commit();
                return response()->json(['status'=>'ok','data'=>$producto],201);
            }else{
                DB::rollBack();
                return response()->json(['status'=>'fail','data'=>null],409);
            }
        }catch(\Exception $e)
            {
                DB::rollBack();
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
            $producto = Producto::findOrFail($id);
            //convirtienod en array
            $response = $producto->toArray();
            $response["relleno"]= $producto->relleno->toArray();
            $response["catalogo"]= $producto->catalogo->toArray();
            $response["sabor"] = $producto->sabor->toArray();
            $response["promocion"] = $producto->promocion->toArray();  
            $response["cobertura"] = $producto->cobertura->toArray(); 
          // Calcula el costo total, incluyendo las coberturas adicionales
        $costoTotal = $producto->precio;

        // Recorre las coberturas seleccionadas por el cliente y suma sus costos
        $coberturasSeleccionadas = $response->input('coberturasSeleccionadas'); // Asegúrate de que este campo coincida con el nombre en tu solicitud
        if (!empty($coberturasSeleccionadas) && is_array($coberturasSeleccionadas)) {
            foreach ($coberturasSeleccionadas as $coberturaId) {
                // Obtén la información de la cobertura desde tu base de datos
                $cobertura = Cobertura::findOrFail($coberturaId); // Asegúrate de que el modelo de Cobertura esté importado

                // Suma el costo de la cobertura al costo total
                $costoTotal += $cobertura->precio;
            }
        }

        // Agrega el costo total al array de respuesta
        $response["costo_total"] = $costoTotal;

        return response()->json(['status' => 'ok', 'data' => $response], 200);
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
            $errores = 0;
            DB::beginTransaction();
            $producto = Producto::findOrFail($id);
            $producto->nombre = $request->nombre;
            $producto->descripcion = $request->descripcion;
            $producto->precio = $request->precio;
            $producto->existencias = $request->existencias;
            $producto->hecho = $request->hecho;
            $producto->vencimiento = $request->vencimiento; 
            //$producto->imagen = $request->imagen;
            $producto->relleno_id = $request->relleno_id;
            $producto->catalogo_id = $request->catalogo_id;
            $imagenAnterior = $producto->imagen;
            //COMPROBANDO SI VIENE UNA IMAGEN
            if($request->hasFile('imagen'))
            {
                //eliminando archivo anterior
                $imagePath = public_path() . '/images/productos/' .$imagenAnterior;
                if($imagenAnterior != 'none.jpg')
                {
                    unlink($imagePath);
                }
            
            //obteniend el archivo de imagen
            $imagen = $request->file('imagen');
            //GENERANDO UN NOMBRE UNICO PARA LA IMAGEN 
            $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
            //subiendo la imagen a una carpeta del servidor
            $imagen->move(public_path('/images/productos/'),$nombreImagen);
            $producto->imagen = $nombreImagen;
        }else{
            $producto->imagen = 'none.jpg';            
        } 
        $producto->save();

         //Guardar las relaciones con sabores
         $sabore = $request->productoSabor;
         if (!is_null($sabore) && is_array($sabore)) 
         {
             foreach ($sabore as $key => $sb) {
                 $productoSabor = ProductoSabor::findOrFail($sb['id']);
                 $productoSabor->sabor_id = $sb['id'];
                 $productoSabor->producto_id = $producto->id;
 
                 if ($productoSabor->update() <= 0) {
                 }
             }
            
         }
         //guardando la relcion de promociones 
         $promocio = $request->productoPromocion;
        if (!is_null($promocio) && is_array($promocio)) 
         {
             foreach ($promocio as $key => $promo) {
                 $productoPromocion = ProductoPromocion::findOrFail($promo['id']);
                 $productoPromocion->promocion_id = $promo['id'];
                 $productoPromocion->producto_id = $producto->id;
 
                 if ($productoPromocion->update() <= 0) {
                 }
             }
         }
         //GUARDANDO LA COBERTURA
         
         $cobertu = $request->productoCobertura;
         if (!is_null($cobertu) && is_array($cobertu))
         {
             foreach ($cobertu as $key => $cob) {
                 $productoCobertura = ProductoCobertura::findOrFail($cob['id']);
                 $productoCobertura->cobertura_id = $cob['id'];
                 $productoCobertura->producto_id = $producto->id;
 
                 if ($productoCobertura->update() <= 0) {
                 }
             }
         }       
            
         if ($errores == 0){
            DB::commit();
            return response()->json(['status'=>'ok','data'=>$producto],202); 
            }else{
                DB::rollBack();
                return response()->json(['status'=>'fail','data'=>null],409);
            }
            
        } catch(\Exception $e)
        {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);        
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function inactivos(Request $request)
    {
       $ProduUpdate = Producto::findOrFail($request->id)->update(['estatus' => $request->estatus]);

       if($request->estatus ==0){
       }
       
    }
}
