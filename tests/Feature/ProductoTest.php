<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Http\Controllers\ProductoController;

class ProductoTest extends TestCase
{
    public function testObtenerProductos()
    {
        // Crea una instancia del controlador
        $productoController = new ProductoController();

        // Llama directamente a la función que deseas probar
        $productos = $productoController->obtenerProductos();

        // Verifica si $productos contiene los datos esperados
        $this->assertNotEmpty($productos);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $productos);

        $response = $this->get('/api/productos');
        $response->assertStatus(200);
        $responseContent = $response->getContent();
        $this->assertStringContainsString('Los Productos se obtuvieron correctamente', $responseContent);
       
    }
}
