<?php

namespace App\Services\Giphy;

use App\DTOs\Giphy\SearchGifsRequestData;
use App\Exceptions\GiphyAPIException;
use App\Exceptions\GiphyServiceException;
use Illuminate\Support\Collection;

class GiphyService
{
    public function __construct(
        private readonly GiphyAPI $giphyClient
    )
    {}

    /**
     * @param SearchGifsRequestData $searchGifsRequestData
     * @return Collection
     * @throws GiphyAPIException
     */
    public function searchGifs(SearchGifsRequestData $searchGifsRequestData): Collection
    {
        return $this->giphyClient->searchGifs(
            $searchGifsRequestData->query,
            $searchGifsRequestData->limit,
            $searchGifsRequestData->offset
        );
    }

    /**
     * @param $gifId
     * @return array|mixed
     * @throws GiphyServiceException|GiphyAPIException
     */
    public function getGifById($gifId): mixed
    {
        $gif = $this->giphyClient->getGifById($gifId);
        if (empty($gif)) {
            throw new GiphyServiceException("Invalid GIF id", 404);
        }
        return $gif;
    }
}
