<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
//use Darryldecode\Cart\Cart;
use App\Models\Producto;
use Cart;
use Darryldecode\Cart\Cart as CartCart;

use App\Models\Cobertura;


class CartController extends Controller
{
    public function shop()
    {
        try {
            $products = Producto::with('sabores')->where('estado', 'activo')->get();
    
            return view('shop')->with(['products' => $products]);
        } catch (\Exception $e) {
            // Manejar cualquier excepción si es necesario
            return response()->json(['status' => 'fail', 'data' => null], 500);
        }
    }

    public function searchProducts(Request $request){
        $query = $request->input('search');
        $products = Producto::where('estado', 'activo')
            ->where('nombre', 'like', "%$query%")
            ->get();
    
        return view('shop', compact('products', 'query'));
    }
    public function cart()  {
        $cartCollection = \Cart::getContent();
        $coberturas = Cobertura::all();
    
        return view('cart', compact('cartCollection', 'coberturas'));
    }

    public function add(Request $request){
        //$id = $request->input('id');
        $quantity = $request->input('quantity');
        $cobertura = $request-> input('cobertura');
        //$personalizacion = $request->input('personalizacion');
        //$cobertura->precio = $request->input('precio'); 
        $product = Producto::find($request->id);
        \Cart::add([
            'id' => $request->id,
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $quantity,
            'attributes' => [
                'img' => $product->imagen,
                'slug' => $request->slug,
                //'cobertura' => $cobertura,
            ],
        ]);

        return redirect()->route('cart.index')->with('success_msg', 'Se agrego un producto a su carrito');
    }

    public function update(Request $request)
{
    
    $id = $request->input('id');
    $quantity = $request->input('quantity');
    $cobertura = $request->input('cobertura');
    $personalizacion = $request->input('personalizacion');

    
    // Encuentra el elemento en el carrito por su ID
    $cartItem = \Cart::get($id);

    

    // Verifica si el elemento ya existe en el carrito
    if ($cartItem) {
        // Actualiza la cantidad y los atributos
        \Cart::update($id, array(
            'quantity' => array(
                'relative' => false,
                'value' => $quantity,
            ),
        ));
    }

    // Redirige de regreso a la página del carrito
    return redirect()->route('cart.index')->with('success_msg', 'Su carrito a sido actualizado');
}

    public function clear(){
        Cart::clear();
        return redirect()->route('cart.index')->with('success_msg', 'Su carrito a sido eliminado');
    }
    
    public function delete($id){
    // Lógica para eliminar el producto del carrito
    \Cart::remove($id);

    // Puedes redirigir a la página del carrito o a donde desees
    return redirect()->route('cart.index')->with('success_msg', 'El producto fue eliminado');
  }

}
