<?php
use App\Http\Controllers\BancoController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SaborController;
use App\Http\Controllers\RellenoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\CoberturaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\UserController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// le agregue el home
Route::get('/', function () {
    return view('welcome');
});

// Route::get('/promociones', function () {
//     return view('home'); // Esto asume que 'home.blade.php' está en la carpeta 'resources/views'
// });

Route::get('productos', function () {
    return view('home'); 
});
Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/admin', [HomeController::class,'dash'])->middleware('can:admin.dash')->name('admin.dash');
Route::resource('sabores', SaborController::class)->middleware('can:admin.dash')->names('admin.sabores');
Route::resource('users', UserController::class)->only(['index', 'edit', 'update'])->middleware('can:admin.dash')->names('admin.users');

 Route::resource('productos', ProductoController::class)->middleware('can:admin.dash')->names('admin.productos');

 Route::post('/productos/{producto}', [ProductoController::class, 'update']);

Route::resource('rellenos', RellenoController::class)->middleware('can:admin.dash')->names('admin.rellenos');
Route::resource('pedidos', PedidoController::class)->middleware('can:admin.dash')->names('admin.pedidos');
Route::resource('catalogos', CatalogoController::class)->middleware('can:admin.dash')->names('admin.categorias');
Route::resource('coberturas', CoberturaController::class)->middleware('can:admin.dash')->names('admin.cobertura');
Route::resource('bancos', BancoController::class)->middleware('can:admin.dash')->names('admin.bancos');

//Route::put('pedidos/change',[PedidoController::class,'changeState']);
//Route::resource('rellenos', RellenoController::class);
Route::get('/productos-reservas', [ProductoController::class, 'index']);
//Route::get('/productos-cart', [ProductoController::class, 'index']);
//Route::get('/', [CartController::class, 'shop'])->name('shop');
//Route::get('/cart', [CartController::class, 'cart'])->name('cart.index');



//Route::post('/cart-add', [CartController::class])->name('cart.add');
//Route::get('/cart-checkout',[CartController::class])->name('cart.checkout');
//Route::post('/cart-clear', [CartController::class])->name('cart.clear');
//Route::post('/cart-removeitem', CartController::class);
Route::resource('cart', CartController::class);
Route::get('/shop', [CartController::class, 'shop']);
Route::get('/cart', [CartController::class, 'cart'])->name('cart.index');
Route::post('/add', [CartController::class, 'add'])->name('cart.store');
Route::post('/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/clear', [CartController::class, 'clear'])->name('cart.clear');

//Route::post('/cart-add', [CartController::class])->name('cart.add');
//Route::get('/cart-checkout',[CartController::class])->name('cart.checkout');
//Route::post('/cart-clear', [CartController::class])->name('cart.clear');
//Route::post('/cart-removeitem', [CartController::class])->name('cart.removeitem');


