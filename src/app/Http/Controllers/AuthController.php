<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends BaseController
{
    /**
     * Handle an incoming authentication request
     */
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (auth()->attempt($validated)) {
            $token = auth()->user()->createToken($validated['email']);
            $success['access_token'] = $token->accessToken;
            $success['expires_at'] = $token->token->expires_at;

            return $this->sendResponse($success, 'User logged in successfully');
        }

        return $this->sendError('Unauthorized', 401);
    }
}
