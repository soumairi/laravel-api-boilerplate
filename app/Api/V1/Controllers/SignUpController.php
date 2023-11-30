<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\SignUpRequest;
use App\Http\Controllers\Controller;
use App\Models\User;
use Config;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tymon\JWTAuth\JWTAuth;

class SignUpController extends Controller
{
    public function signUp(SignUpRequest $request, JWTAuth $JWTAuth)
    {
        $user = new User($request->all());
        if (! $user->save()) {
            throw new HttpException(500);
        }

        if (! Config::get('boilerplate.sign_up.release_token')) {
            return response()->json([
                'status' => 'ok',
            ], 201);
        }

        $token = $JWTAuth->fromUser($user);

        return response()->json([
            'status' => 'ok',
            'token' => $token,
        ], 201);
    }
}
