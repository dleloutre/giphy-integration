<?php

namespace App\Repositories;

use App\DTOs\AddFavoriteGifRequestData;
use App\Models\FavoriteGif;
use App\Repositories\Interfaces\FavoriteGifsRepositoryInterface;

class FavoriteGifsRepository implements FavoriteGifsRepositoryInterface
{
    public function __construct(private readonly FavoriteGif $favoriteGif)
    {}

    public function create(AddFavoriteGifRequestData $favoriteGifData): FavoriteGif
    {
        return $this->favoriteGif->create([
            'gif_id' => $favoriteGifData->gifId,
            'user_id' => $favoriteGifData->userId,
            'alias' => $favoriteGifData->alias
        ]);
    }
}