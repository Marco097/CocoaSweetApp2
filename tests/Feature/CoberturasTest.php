<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Http\Controllers\CoberturaController;
use App\Models\Cobertura;
use Tests\TestCase;

class CoberturasTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testObtenerCoberturas()
    {
        // Crea una instancia del controlador
        $coberturaController = new CoberturaController();

        // Llama directamente a la función que deseas probar
        $coberturas = $coberturaController->obtenerCoberturas();

        // Verifica si $coberturas contiene los datos esperados
        $this->assertNotEmpty($coberturas);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $coberturas);

        $response = $this->get('/api/coberturas');
        $response->assertStatus(200);
        $responseContent = $response->getContent();
        $this->assertStringContainsString('Las Coberturas se obtuvieron correctamente', $responseContent);
       
    }
}