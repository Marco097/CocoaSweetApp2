<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Http\Controllers\SaborController;
use App\Models\Sabor;
use Tests\TestCase;

class SaborTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testObtenerSabores()
    {
        // Crea una instancia del controlador
        $saborController = new SaborController();

        // Llama directamente a la función que deseas probar
        $sabores = $saborController->obtenerSabores();

        // Verifica si $sabores contiene los datos esperados
        $this->assertNotEmpty($sabores);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $sabores);

        $response = $this->get('/api/sabores');
        $response->assertStatus(200);
        $responseContent = $response->getContent();
        $this->assertStringContainsString('Los Sabores se obtuvieron correctamente', $responseContent);
       
    }
}
