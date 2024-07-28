<?php

namespace Tests\Feature;

use App\Models\User;
use App\Repositories\RequestLogRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class StoreRequestLogTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Passport::actingAs($this->user);
    }


    public function test_store_request_log_successfully()
    {
        $this->mock(RequestLogRepository::class)
            ->shouldReceive('create')
            ->once();

        $response = $this->post("/api/users/{$this->user->id}/favorite-gifs", [
            'alias' => "some_alias",
            'gif_id' => "123"
        ]);

        $response->assertStatus(200);
    }
}
