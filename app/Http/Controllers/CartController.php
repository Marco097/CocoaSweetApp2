<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
//use Darryldecode\Cart\Cart;
use App\Models\Producto;
use Cart;
use Darryldecode\Cart\Cart as CartCart;

use App\Models\Cobertura;
use App\Models\Sabor;

class CartController extends Controller
{
    public function shop()
    {
        $products = Producto::all();
        //$sabore = Sabor::all();
       //dd($products);
        return view('shop')->with(['products' => $products]);
    }
    public function cart()  {
        $cartCollection = Cart::getContent();
        $coberturas = Cobertura::all();
    
        return view('cart', compact('cartCollection', 'coberturas'));
    }
    public function remove(Request $request){
        Cart::remove($request->id);

        // Obtén los datos actualizados del carrito y devuélvelos como JSON
        $cartCollection = Cart::getContent();
    
        return redirect()->route('cart.index')->with('success_msg', 'El producto fue eliminado');
        //return response()->json(['message' => 'Item is removed!', 'cart' => $cartCollection]);
    }

    public function add(Request $request){
        //$id = $request->input('id');
        $quantity = $request->input('quantity');
        $cobertura = $request-> input('cobertura');
        //$personalizacion = $request->input('personalizacion');
        //$cobertura->precio = $request->input('precio'); 
        $product = Producto::find($request->id);
        Cart::add([
            'id' => $request->id,
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $quantity,
            'attributes' => [
                'img' => $product->imagen,
                'slug' => $request->slug,
                'cobertura' => $cobertura,
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
            'attributes' => array(
                'cobertura' => $cobertura,
                'personalizacion' => $personalizacion,
            ),
        ));
    }

    // Redirige de regreso a la página del carrito
    return redirect()->route('cart.index');
}


    public function clear(){
        Cart::clear();
        return redirect()->route('cart.index')->with('success_msg', 'su Carrito a sido eliminado');
    }
}
