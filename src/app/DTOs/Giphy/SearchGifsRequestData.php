<?php

namespace App\DTOs\Giphy;

use Illuminate\Http\Request;

class SearchGifsRequestData
{
    const DEFAULT_LIMIT = 25;
    const DEFAULT_OFFSET = 0;

    public function __construct(
        public string $query,
        public int $limit = 25,
        public int $offset = 0
    ) {}

    public static function fromRequest(Request $request): self
    {
        $validated = $request->validate([
            'query' => 'required|string|max:50',
            'limit' => 'integer|max:50',
            'offset' => 'integer|max:4999'
        ]);

        return new self(
            query: $validated['query'],
            limit: $validated['limit'] ?? self::DEFAULT_LIMIT,
            offset: $validated['offset'] ?? self::DEFAULT_OFFSET
        );
    }
}