<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Paralelo;
use App\Models\Estudiante;

class ParaleloTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        //creamos dos paralelos de prueba
        Paralelo::factory()->create(['nombre' => 'P1']);
        Paralelo::factory()->create(['nombre' => 'P2']);

        //cambiamos la ruta de raiz por la ruta de paralelos
        //$response = $this->get('/');
        $response = $this->getJson('/api/paralelos');

        // enviamos la respuesta de ecisto
        //$response->assertStatus(200);
        $response->assertStatus(200)
                 ->assertJsonFragment(['nombre' => 'P1'])
                 ->assertJsonFragment(['nombre' =>'P2']);
    }
}
