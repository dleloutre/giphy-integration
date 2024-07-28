<?php

namespace Tests\Feature;

use App\DTOs\AddFavoriteGifRequestData;
use App\Models\FavoriteGif;
use App\Models\User;
use App\Repositories\FavoriteGifsRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Mockery;
use Tests\TestCase;

class StoreFavoriteGifTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Passport::actingAs($this->user);
    }

    private function getFixture()
    {
        $path = base_path('tests/Fixtures/favorite_gif.json');
        return json_decode(file_get_contents($path), true);
    }

    public function test_store_valid_gif_successfully()
    {
        $mockedFavoriteGif = $this->getFixture();
        $favoriteGif = new FavoriteGif($mockedFavoriteGif);
        $favoriteGifData = new AddFavoriteGifRequestData("123", "some_alias", $this->user->id);
        $this->mock(FavoriteGifsRepository::class)
            ->shouldReceive('create')
            ->with(Mockery::on(function ($arg) use ($favoriteGifData) {
                return $arg->gifId === $favoriteGifData->gifId &&
                    $arg->alias === $favoriteGifData->alias &&
                    $arg->userId === $favoriteGifData->userId;
            }))
            ->once()
            ->andReturn($favoriteGif);

        $response = $this->post("/api/users/{$this->user->id}/favorite-gifs", [
            'alias' => "some_alias",
            'gif_id' => "123"
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => $mockedFavoriteGif,
            'message' => 'Favorite GIF successfully created.'
        ]);
    }

    public function test_store_gif_returns_400_error_when_missing_alias()
    {
        $response = $this->post("/api/users/{$this->user->id}/favorite-gifs", [
            'gif_id' => "123"
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'message' => 'The alias field is required.'
        ]);
    }

    public function test_store_gif_returns_400_error_when_missing_gif_id()
    {
        $response = $this->post("/api/users/{$this->user->id}/favorite-gifs", [
            'alias' => "123"
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'message' => 'The gif id field is required.'
        ]);
    }

    public function test_store_gif_returns_400_error_when_invalid_alias()
    {
        $response = $this->post("/api/users/{$this->user->id}/favorite-gifs", [
            'gif_id' => "123",
            'alias' => 123
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'message' => 'The alias field must be a string.'
        ]);
    }

    public function test_store_gif_returns_400_error_when_invalid_gif_id()
    {
        $response = $this->post("/api/users/{$this->user->id}/favorite-gifs", [
            'gif_id' => 123,
            'alias' => "123"
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'message' => 'The gif id field must be a string.'
        ]);
    }

    public function test_store_gif_returns_error_when_already_exists()
    {
        $this->post("/api/users/{$this->user->id}/favorite-gifs", [
            'gif_id' => "123",
            'alias' => "alias"
        ]);

        $response = $this->post("/api/users/{$this->user->id}/favorite-gifs", [
            'gif_id' => "123",
            'alias' => "alias"
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'message' => 'Invalid User ID for given GIF ID'
        ]);
    }
}
