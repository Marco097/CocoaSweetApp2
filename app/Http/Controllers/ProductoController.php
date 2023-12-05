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
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
    
        // Consulta para obtener productos activos
        $productosActivos = Producto::where('estado', 'activo')
            ->where('nombre', 'like', "%$search%")
            ->with(['producto_sabores', 'relleno', 'catalogo', 'producto_coberturas'])
            ->get();
        
        // Consulta para obtener productos inactivos
        $productosInactivos = Producto::where('estado', 'inactivo')
            ->where('nombre', 'like', "%$search%")
            ->with(['producto_sabores', 'relleno', 'catalogo', 'producto_coberturas'])
            ->get();
    
        // Combina los resultados de ambas consultas
        $response = [
            'productos_activos' => $productosActivos,
            'productos_inactivos' => $productosInactivos,
        ];
    
        return $response;
        
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
            $producto->imagen = $request->imagen;
            $producto->relleno_id = $request->relleno_id;
            $producto->catalogo_id = $request->catalogo_id;
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

            if($producto->save() <= 0)
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
           /* $promocio = $request->productoPromocion;
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
            }*/
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
                //DB::rollBack();
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
            $response["sabor"] = $producto->producto_sabores->pluck('sabor')->toArray(); // Modificado para obtener los sabores
           // $response["promocion"] = $producto->promocion->toArray();  
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

    public function update(Request $request, string $id)
    {
        try {
            $errores = 0;
            DB::beginTransaction();
            $producto = Producto::findOrFail($id);
    
            $imagenAnterior = $producto->imagen;
            $producto->nombre = $request->nombre;
            $producto->descripcion = $request->descripcion;
            $producto->precio = $request->precio;
            $producto->existencias = $request->existencias;
            $producto->hecho = $request->hecho;
            $producto->vencimiento = $request->vencimiento;
            $producto->imagen = $request->imagen;
            $producto->relleno_id = $request->relleno_id;
            $producto->catalogo_id = $request->catalogo_id;
    
            // COMPROBANDO SI VIENE UNA IMAGEN
            if ($request->hasFile('imagen')) {
                // eliminando el archivo anterior
                $imagePath = public_path() . '/images/productos/' . $imagenAnterior;
                if ($imagenAnterior != 'none.jpg') {
                    unlink($imagePath);
                }
                // obteniendo el archivo de imagen
                $imagen = $request->file('imagen');
                // generando un nombre único para la imagen
                $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
                // subiendo la imagen a una carpeta del servidor
                $imagen->move(public_path('images/productos/'),$nombreImagen);
                $producto->imagen = $nombreImagen;
            }
            
            if($producto->update() <= 0)
                {
                    $errores++;
                }
    
            // Guardar las relaciones con sabores
            $sabores = $request->productoSabor;
            if (!is_null($sabores) && is_array($sabores)) {
                foreach ($sabores as $key => $sabor) {
                    $productoSabor = ProductoSabor::findOrFail($sabor['id']);
                    $productoSabor->sabor_id = $sabor['id'];
                    $productoSabor->producto_id = $producto->id;
    
                    if ($productoSabor->update() <= 0) {
                        $errores++;
                    }
                }
            }
    
            // Guardando la relación de promociones
            $promociones = $request->productoPromocion;
            if (!is_null($promociones) && is_array($promociones)) {
                foreach ($promociones as $key => $promocion) {
                    $productoPromocion = ProductoPromocion::findOrFail($promocion['id']);
                    $productoPromocion->promocion_id = $promocion['id'];
                    $productoPromocion->producto_id = $producto->id;
    
                    if ($productoPromocion->update() <= 0) {
                        $errores++;
                    }
                }
            }
    
            // GUARDANDO LA COBERTURA
            $coberturas = $request->productoCobertura;
            if (!is_null($coberturas) && is_array($coberturas)) {
                foreach ($coberturas as $key => $cobertura) {
                    $productoCobertura = ProductoCobertura::findOrFail($cobertura['id']);
                    $productoCobertura->cobertura_id = $cobertura['id'];
                    $productoCobertura->producto_id = $producto->id;
    
                    if ($productoCobertura->update() <= 0) {
                        $errores++;
                    }
                }
            }
    
            if($errores == 0)
            {
                DB::commit();
                return response()->json(['status'=>'ok','data'=>$producto],202);
            }else{
                DB::rollBack();
                return response()->json(['status'=>'fail','data'=>null],409);
            }
        }catch(\Exception $e)
            {
                //DB::rollBack();
            return $e->getMessage();
            }        
    }
    

    /**
     * Update an active product.
     */
    

public function desactivarProducto($id)
{
    try {
        $producto = Producto::findOrFail($id);

        // Verifica si el producto ya está inactivo antes de intentar desactivarlo nuevamente
        if ($producto->estado === 'inactivo') {
            return response()->json(['message' => 'El producto ya está inactivo'], 200);
        }

        $producto->estado = 'inactivo';
        $producto->save();

        return response()->json(['message' => 'Producto desactivado con éxito']);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json(['message' => 'Producto no encontrado'], 404);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Error al desactivar el producto', 'error' => $e->getMessage()], 500);
    }
}

public function activarProducto($id)
{
    try {
        $producto = Producto::findOrFail($id);

        // Verifica si el producto ya está inactivo antes de intentar desactivarlo nuevamente
        if ($producto->estado === 'activo') {
            return response()->json(['message' => 'El producto ya está activo'], 200);
        }

        $producto->estado = 'activo';
        $producto->save();

        return response()->json(['message' => 'Producto activo con éxito']);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json(['message' => 'Producto no encontrado'], 404);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Error al activar el producto', 'error' => $e->getMessage()], 500);
    }
}

}