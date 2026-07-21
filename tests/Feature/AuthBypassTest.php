<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthBypassTest extends TestCase
{
    use RefreshDatabase;


    public function test_access_allowed(): void
    {

        config([
            'app.env' => 'local',
            'auth.enabled' => false
        ]);

        $response = $this->getJson('/api/user');

        $response->assertStatus(200);
    }

    public function test_access_denied(): void
    {
        config([
            'app.env' => 'local',
            'auth.enabled' => true,
        ]);

        $response = $this->getJson('/api/perfil');

        $response->assertStatus(401)
            ->assertJson(['error' => 'Não autorizado.']);
    }
}
