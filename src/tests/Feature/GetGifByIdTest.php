<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Giphy\GiphyAPI;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class GetGifByIdTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        Passport::actingAs($user);
    }

    private function getFixture($filename)
    {
        $path = base_path('tests/Fixtures/' . $filename);
        return json_decode(file_get_contents($path), true);
    }

    public function test_search_gif_by_id_returns_successfully()
    {
        $gifsJson = $this->getFixture('gifs.json');
        $this->mock(GiphyAPI::class)
            ->shouldReceive('getGifById')
            ->with(123)
            ->once()
            ->andReturn($gifsJson[0]);

        $response = $this->get('/api/gifs/123');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => $gifsJson[0],
            'message' => 'GIF retrieved successfully.'
        ]);
    }

    public function test_search_gif_by_id_returns_404_error_when_not_found()
    {
        $this->mock(GiphyAPI::class)
            ->shouldReceive('getGifById')
            ->with(123)
            ->once()
            ->andReturn([]);

        $response = $this->get('/api/gifs/123');

        $response->assertStatus(404);
        $response->assertJson([
            'success' => false,
            'message' => 'Invalid GIF id'
        ]);
    }
}
