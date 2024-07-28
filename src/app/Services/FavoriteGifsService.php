<?php

namespace App\Services;

use App\DTOs\AddFavoriteGifRequestData;
use App\Exceptions\FavoriteGifsServiceException;
use App\Models\FavoriteGif;
use App\Repositories\FavoriteGifsRepository;
use \Exception;

class FavoriteGifsService {
    const ERROR_CODE_INTEGRITY_CONSTRAINT = 23000;

    public function __construct(private readonly FavoriteGifsRepository $favoriteGifsRepository)
    {}

    /**
     * @throws FavoriteGifsServiceException
     */
    public function addFavoriteGif(AddFavoriteGifRequestData $favoriteGifRequestData): FavoriteGif {
        try {
            return $this->favoriteGifsRepository->create($favoriteGifRequestData);
        } catch (Exception $e) {
            if ($e->getCode() == self::ERROR_CODE_INTEGRITY_CONSTRAINT) {
                throw new FavoriteGifsServiceException("Invalid User ID for given GIF ID", 400);
            }

            throw $e;
        }
    }
}
