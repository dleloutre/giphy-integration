<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class AddFavoriteGifRequestData
{
    public function __construct(
        public string $gifId,
        public string $alias,
        public int $userId
    )
    {}

    public static function fromRequest(Request $request, int $userId): self
    {
        $validated = $request->validate([
            'gif_id' => 'required|string',
            'alias' => 'required|string'
        ]);

        return new self(
            gifId: $validated['gif_id'],
            alias: $validated['alias'],
            userId: $userId
        );
    }
}