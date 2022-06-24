<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use App\Models\User;
use App\Http\Helper\ResponseBuilder;
class ExampleController extends Controller
{
     /**
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function postLogin(Request $request)
    {
       // return "Hello";
        $this->validate($request, [
            'email'    => 'required|email|max:255',
            'password' => 'required',
        ]);

        try {

            if (! $token = $this->jwt->attempt($request->only('email', 'password'))) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], 500);

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], 500);

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent' => $e->getMessage()], 500);

        }

        return response()->json(compact('token'));
    }

    public function test(){
        $data =  User::all();
        $status = true;
        $info = "Data is listed successfully";
        return ResponseBuilder::result($status,$info,$data);

    }

    public function order(Request $request){
        $user = new User();
        $user->id = $request->input('id');
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        $user->created_at = $request->input('created_at');
        $user->updated_at = $request->input('updated_at');
        $result = $user->save();

        if($result == 1 ){
            $status = true;
            $info = "Data is inserted successfully";
        }else{
            $status = false;
            $info = "Data is not inserted successfully";
        }
        return ResponseBuilder::result($status,$info);
    }

}
