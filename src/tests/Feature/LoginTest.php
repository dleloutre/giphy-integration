<?php

namespace Tests\Feature;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('passport:install');
        $this->artisan('passport:keys');
        $this->user = User::factory()->create([
            'email' => 'test@prex.com',
            'password' => bcrypt('pwd')
        ]);
    }

    public function test_login_successfully()
    {
        $response = $this->post('/api/login', [
            'email' => 'test@prex.com',
            'password' => 'pwd'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'access_token',
                'expires_at'
            ],
            'message'
        ]);
        $response->assertJson([
            'success' => true,
            'message' => 'User logged in successfully'
        ]);
    }

    public function test_login_fails_when_missing_email()
    {
        $response = $this->post('/api/login', [
            'password' => 'pwd'
        ]);

        $response->assertStatus(302);
    }

    public function test_session_expires_after_30_minutes()
    {
        $in30Minutes = Carbon::now()->addMinutes(30)->timestamp;
        $response = $this->post('/api/login', [
            'email' => 'test@prex.com',
            'password' => 'pwd'
        ]);

        $response = $response->json('data');
        $expiresAt = Carbon::parse($response['expires_at'])->timestamp;

        $this->assertLessThanOrEqual($expiresAt, $in30Minutes);
    }
}
