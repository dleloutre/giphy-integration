<?php

namespace App\Http\Controllers;

use App\DTOs\AddFavoriteGifRequestData;
use App\Exceptions\FavoriteGifsServiceException;
use App\Services\FavoriteGifsService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserController extends BaseController
{
    public function __construct(private readonly FavoriteGifsService $favoriteGifsService)
    {}

    /**
     * @param Request $request
     * @param int $userId
     * @return JsonResponse
     */
    public function store(Request $request, int $userId): JsonResponse
    {
        try {
            $favoriteGifData = AddFavoriteGifRequestData::fromRequest($request, $userId);
            $gif = $this->favoriteGifsService->addFavoriteGif($favoriteGifData);
            return $this->sendResponse($gif, 'Favorite GIF successfully created.');
        } catch (Exception $e) {
            if ($e instanceof ValidationException) {
                return $this->sendError($e->getMessage(), 400);
            }

            if ($e instanceof FavoriteGifsServiceException) {
                return $this->sendError($e->getMessage(), $e->getCode());
            }

            return $this->sendError($e->getMessage());
        }
    }
}
