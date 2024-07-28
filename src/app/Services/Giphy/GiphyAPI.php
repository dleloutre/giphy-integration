<?php

namespace App\Services\Giphy;

use App\Exceptions\GiphyAPIException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class GiphyAPI
{
    const STATUS_CODE_OK = 200;

    public function __construct(
        private readonly string $uri,
        private readonly string $apikey
    )
    {}

    /**
     * @param string $path
     * @return string
     */
    public function url(string $path): string
    {
        return "{$this->uri}/{$path}";
    }

    /**
     * @param $keywords
     * @param $limit
     * @param $offset
     * @return Collection
     * @throws GiphyAPIException
     */
    public function searchGifs($keywords, $limit, $offset): Collection
    {
        $response = Http::get(
            $this->url('search'), [
            'q' => $keywords,
            'limit' => $limit,
            'offset' => $offset,
            'api_key' => $this->apikey
        ])->json();

        if ($response['meta']['status'] == self::STATUS_CODE_OK) {
            return collect($response['data']);
        }

        throw new GiphyAPIException("Error searching GIFs: {$response['error_code']}", $response['meta']['status']);
    }

    /**
     * @param $gifId
     * @return array|mixed
     * @throws GiphyAPIException
     */
    public function getGifById($gifId): mixed
    {
        $response = Http::get(
            $this->url($gifId),
            ['api_key' => $this->apikey]
        )->json();

        if ($response['meta']['status'] == self::STATUS_CODE_OK) {
            return $response['data'];
        }

        throw new GiphyAPIException("Error getting GIF by id: {$response['error_code']}", $response['meta']['status']);
    }
}