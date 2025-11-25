<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomeTest extends TestCase
{
    public function test_root_redirects_to_login(): void
    {
        $response = $this->get('/');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }
}
