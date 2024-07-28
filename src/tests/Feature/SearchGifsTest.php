<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Giphy\GiphyAPI;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class SearchGifsTest extends TestCase
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

    public function test_search_gifs_returns_successfully()
    {
        $gifsJson = $this->getFixture('gifs.json');
        $mockedCollection = new Collection($gifsJson);
        $this->mock(GiphyAPI::class)
            ->shouldReceive('searchGifs')
            ->withArgs(['prex', 10, 2])
            ->once()
            ->andReturn($mockedCollection);

        $response = $this->get('/api/gifs/search?limit=10&offset=2&query=prex');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => $gifsJson,
            'message' => 'GIFs retrieved successfully.'
        ]);
    }

    public function test_search_gifs_returns_empty_data_successfully()
    {
        $mockedCollection = new Collection([]);
        $this->mock(GiphyAPI::class)
            ->shouldReceive('searchGifs')
            ->withArgs(['prex', 10, 2])
            ->once()
            ->andReturn($mockedCollection);

        $response = $this->get('/api/gifs/search?limit=10&offset=2&query=prex');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [],
            'message' => 'GIFs retrieved successfully.'
        ]);
    }

    public function test_search_gifs_returns_400_error_when_invalid_limit()
    {
        $response = $this->get('/api/gifs/search?limit=100&offset=2&query=prex');

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'message' => 'The limit field must not be greater than 50.'
        ]);
    }

    public function test_search_gifs_returns_400_error_when_invalid_offset()
    {
        $response = $this->get('/api/gifs/search?limit=10&offset=5000&query=prex');

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'message' => 'The offset field must not be greater than 4999.'
        ]);
    }

    public function test_search_gifs_returns_400_error_when_too_long_query()
    {
        $response = $this->get('/api/gifs/search?limit=10&offset=5&query=prextestingtest_search_gifs_returns_400_error_when_too_long_query');

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'message' => 'The query field must not be greater than 50 characters.'
        ]);
    }
}
