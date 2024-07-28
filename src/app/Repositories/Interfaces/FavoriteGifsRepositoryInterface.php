<?php

namespace App\Repositories\Interfaces;

use App\DTOs\AddFavoriteGifRequestData;
use App\Models\FavoriteGif;

interface FavoriteGifsRepositoryInterface {
    public function create(AddFavoriteGifRequestData $favoriteGifData): FavoriteGif;
}