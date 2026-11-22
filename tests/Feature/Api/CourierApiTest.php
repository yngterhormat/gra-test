<?php

namespace Tests\Feature\Api;

use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class CourierApiTest extends TestCase
{
    use DatabaseMigrations;

    public function test_index_returns_ok_with_paginated_couriers(): void
    {
        $this->get('/api/v1/couriers');

        $this->assertResponseOk();

        $data = json_decode($this->response->getContent(), true);

        $this->assertSame('OK', $data['message']);
        $this->assertArrayHasKey('data', $data);
    }

    public function test_create_returns_created_courier(): void
    {
        $this->json('POST', '/api/v1/couriers', [
            'name' => 'Test Courier',
            'level' => 1,
        ]);

        $this->assertResponseStatus(201);

        $data = json_decode($this->response->getContent(), true);

        $this->assertSame('Created', $data['message']);
        $this->assertSame('Test Courier', $data['data']['name']);
        $this->assertSame(1, $data['data']['level']);
    }

    public function test_create_validation_fails(): void
    {
        $this->json('POST', '/api/v1/couriers', [
            'name' => '',
            'level' => 99999,
        ]);

        $this->assertResponseStatus(422);

        $data = json_decode($this->response->getContent(), true);

        $this->assertSame('Validation failed', $data['message']);
    }

    public function test_show_returns_courier(): void
    {
        $this->json('POST', '/api/v1/couriers', [
            'name' => 'Test Courier',
            'level' => 1,
        ]);

        $id = json_decode($this->response->getContent(), true)['data']['id'];

        $this->get('/api/v1/couriers/'.$id);

        $this->assertResponseOk();

        $data = json_decode($this->response->getContent(), true);

        $this->assertSame($id, $data['data']['id']);
    }

    public function test_show_returns_not_found_for_missing_courier(): void
    {
        $this->get('/api/v1/couriers/00000000-0000-4000-8000-000000000000');

        $this->assertResponseStatus(404);
    }

    public function test_update_returns_updated_courier(): void
    {
        $this->json('POST', '/api/v1/couriers', [
            'name' => 'Old Courier',
            'level' => 1,
        ]);

        $id = json_decode($this->response->getContent(), true)['data']['id'];

        $this->put('/api/v1/couriers/'.$id, [
            'name' => 'New Courier',
            'level' => 2,
        ]);

        $this->assertResponseOk();

        $data = json_decode($this->response->getContent(), true);

        $this->assertSame('New Courier', $data['data']['name']);
    }

    public function test_delete_returns_ok(): void
    {
        $this->json('POST', '/api/v1/couriers', [
            'name' => 'Temp Courier',
            'level' => 1,
        ]);

        $id = json_decode($this->response->getContent(), true)['data']['id'];

        $this->delete('/api/v1/couriers/'.$id);

        $this->assertResponseOk();
    }
}
