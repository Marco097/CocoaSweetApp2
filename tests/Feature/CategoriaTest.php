<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Http\Controllers\CatalogoController;
use App\Models\Catalogo;

class CategoriaTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testObtenerCatalogos()
    {
        // Crea una instancia del controlador
        $catalogoController = new CatalogoController();

        // Llama directamente a la función que deseas probar
        $catalogos = $catalogoController->obtenerCatalogos();

        // Verifica si $catalogos contiene los datos esperados
        $this->assertNotEmpty($catalogos);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $catalogos);

        $response = $this->get('/api/catalogos');
        $response->assertStatus(200);
        $responseContent = $response->getContent();
        $this->assertStringContainsString('Los Catalogos se obtuvieron correctamente', $responseContent);
       
    }
}