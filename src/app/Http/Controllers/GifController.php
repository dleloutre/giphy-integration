<?php

namespace App\Http\Controllers;

use App\DTOs\Giphy\SearchGifsRequestData;
use App\Exceptions\GiphyServiceException;
use App\Services\Giphy\GiphyService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class GifController extends BaseController
{
    public function __construct(private readonly GiphyService $giphyService)
    {}

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $searchGifsRequest = SearchGifsRequestData::fromRequest($request);
            $result = $this->giphyService->searchGifs($searchGifsRequest);
            return $this->sendResponse($result, 'GIFs retrieved successfully.');
        } catch (Exception $e) {
            if ($e instanceof ValidationException) {
                return $this->sendError($e->getMessage(), 400);
            }

            return $this->sendError($e->getMessage());
        }
    }

    /**
     * @param string $gifId
     * @return JsonResponse
     */
    public function show(string $gifId): JsonResponse
    {
        try {
            $gif = $this->giphyService->getGifById($gifId);
            return $this->sendResponse($gif, 'GIF retrieved successfully.');
        } catch (Exception $e) {
            if ($e instanceof GiphyServiceException) {
                return  $this->sendError($e->getMessage(), $e->getCode());
            }
            return $this->sendError($e->getMessage());
        }

    }
}
