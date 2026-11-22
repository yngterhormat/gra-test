<?php

namespace Tests\Feature\Api;

use Tests\TestCase;

class HealthApiTest extends TestCase
{
    public function test_health_returns_ok_response(): void
    {
        $this->get('/health');

        $this->assertResponseOk();

        $data = json_decode($this->response->getContent(), true);

        $this->assertSame('OK', $data['message']);
        $this->assertSame(['status' => 'ok'], $data['data']);
    }
}
