<?php

namespace Tests\Feature;

use App\Http\Controllers\RellenoController;
use App\Models\Relleno;
use Tests\TestCase;

class RellenoTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testObtenerRellenos()
    {
        // Crea una instancia del controlador
        $rellenoController = new RellenoController();

        // Llama directamente a la función que deseas probar
        $rellenos = $rellenoController->obtenerRellenos();

        // Verifica si $rellenos contiene los datos esperados
        $this->assertNotEmpty($rellenos);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $rellenos);

        $response = $this->get('/api/rellenos');
        $response->assertStatus(200);
        $responseContent = $response->getContent();
        $this->assertStringContainsString('Los Rellenos se obtuvieron correctamente', $responseContent);
       
    }
}