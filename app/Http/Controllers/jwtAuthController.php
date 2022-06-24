<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use App\User;
use Tymon\JWTAuth\Exceptions\JWTException;


class jwtAuthController extends Controller
{
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }
    public function Signin(Request $request)
    {
        try {
            if (!$token = $this->jwt->attempt($request->only('email', 'password'))) {
                return response()->json(['The credentials provided are invalid.'], 404);
            }
        }  catch (JWTException $e){
            return response()->json([
                'message' => 'We could not sign you in. Try again later.'
            ], 500);
        }

        return response()->json(compact('token'));
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    public function LogoutUser(Request $request){
        $token = $this->jwt->getToken();

        if($this->jwt->invalidate($token)){
            return response()->json([
                'message' => 'User logged off successfully!'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed to logout user. Try again.'
            ], 500);
        }
    }
}
