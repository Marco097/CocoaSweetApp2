<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Http\Controllers\BancoController;
use App\Models\Banco;

class BancoTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testObtenerBancos()
    {
        // Crea una instancia del controlador
        $bancoController = new BancoController();

        // Llama directamente a la función que deseas probar
        $bancos = $bancoController->obtenerBancos();

        // Verifica si $bancos contiene los datos esperados
        $this->assertNotEmpty($bancos);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $bancos);

        $response = $this->get('/api/bancos');
        $response->assertStatus(200);
        $responseContent = $response->getContent();
        $this->assertStringContainsString('Los Bancos se obtuvieron correctamente', $responseContent);
       
    }
}