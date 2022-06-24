<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
//use Tymon\JWTAuth\JWTAuth;
//use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function test(){
        echo "TEst";
    }

    public function login(Request $res){
        $email = $res->email;
        $password = $res->password;
        if(empty($email) OR empty($password)){
            return response()->json(['status'=>'error','message'=>'You must fill all fields']);
        }
        $client = new \GuzzleHttp\Client();
        try{
            return $client->post('v1/oauth/token',[
                "form_params" =>[
                        "client_secret" => "ERDHQDRoJ2SQqhqNQhCgwziKvjsujG2D1K9VVEVE",
                        "grant_type" => "password",
                        "client_id" => 4,
                        "username" => 'swati@gmail.com',
                        "password" => 'Swati@3127'
                    ]
            ]);
        }catch(BadResponseException $e){
            return response()->json(['status'=>'error','message'=>$e->getMessage()]);
        }
        // $client = new \GuzzleHttp\Client();
        // $response = $client->request('POST', url('v1/oauth/tokens'), [
        //     'json'=>[
        //         "auth"=> [
        //             "identity"=> [
        //                 "methods"=> [
        //                     "password"
        //                 ],
        //                 "password"=> [
        //                     "user"=> [
        //                         "name"=> "username",
        //                         "password"=> "mypassword",
        //                         "domain"=> [
        //                             "name"=> "mydomain"
        //                         ]
        //                     ]
        //                 ]
        //             ],
        //             "scope"=> [
        //                 "project"=> [
        //                     "name"=> "projectname"
        //                 ]
        //             ]
        //         ]
        //     ]
        // ]);
    }

    public function loginnew(Request $request){
        $email = $request->email;
        $password = $request->password;
        //check if empty field
        if(empty($email) || empty($password)){
            return response()->json(['status'=>'error','message'=>'you must fill all the fields']);
        }
        $credential = request(['email','password']);
        if(! $token = auth('api')->attempt($credential)){
            return response()->json(['error'=>'Unauthorized'],401);
        }
        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }



    public function registernew(Request $request){
        //$name = $request->name;
        $email = $request->email;
        $password = $request->password;
        //checking for empty field
        if( empty($email) || empty($password)){
            return response()->json(['status'=>'error','message'=>'You must fill all the fields']);
        }

        //checking for valid email
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return response()->json(['status'=>'error','message'=>'you must enter valid email']);
        }

        //check if password is greater than 5 character
        if(strlen($password) <6){
            return response()->json(['status'=>'error','message'=>'Password should be min 6 character']);
        }

        //check user is alredy exist
        if(User::where('email','=',$email)->exists()){
            return response()->json(['status'=>'error','message'=>'User already exists with this email']);
        }

        try{
            $user= new User();
           // $user->name = $name;
            $user->email = $email;
            $user->password = Hash::make($password);
            if($user->save()){
                return $this->loginnew($request);
            }
        }catch(Exception $e){
            return response()->json(['status'=>'error','message'=>$e->getMessage()]);
        }
    }

    public function logoutnew(Request $request){
        try{
                Auth::user()->tokens()->each(function($token){
                $token->delete();
                });
                return response()->json(['status'=>'success','message'=>'Logged out successfully']);
        }catch(Exception $e){
            return response()->json(['status'=>'error','message'=>$e->getMessage()]);
        }
    }


}
