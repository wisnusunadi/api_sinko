<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

use App\Models\Users;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    // public function login(Request $request)
    // {

    //     $validator = Validator::make($request->all(), [
    //         'username' => 'required|min:4',
    //         'password' => 'required|min:6'
    //     ],
    //     [
    //         'required'  => ':attribute harus diisi',
    //         'min'       => ':attribute minimal :min karakter',
    //     ]);

    //     if ($validator->fails()) {
    //         $resp = [
    //             'metadata' => [
    //                     'message' => $validator->errors()->first(),
    //                     'code'    => 422
    //                 ]
    //             ];
    //         return response()->json($resp, 422);
    //         die();
    //     }

    //     $user = Users::where('username', $request->username)->first();
    //     if($user)
    //     {
    //         if( Hash::check($request->password,$user->password) )
    //         {



    //             $token = Auth::login($user);
    //             $user->update([
    //                 'api_token' => $token
    //             ]);
    //             $resp = [
    //                 'response' => [
    //                     'token'=> $token
    //                 ],
    //                 'metadata' => [
    //                     'message' => 'OK',
    //                     'code'    => 200
    //                 ]
    //             ];

    //             return response()->json($resp);
    //         }else{

    //             $resp = [
    //                 'metadata' => [
    //                     'message' => 'Username Atau Password Tidak Sesuai',
    //                     'code'    => 401
    //                 ]
    //             ];

    //             return response()->json($resp, 401);
    //         }
    //     }else{
    //         $resp = [
    //             'metadata' => [
    //                 'message' => 'Username Atau Password Tidak Sesuai',
    //                 'code'    => 401
    //             ]
    //         ];

    //         return response()->json($resp, 401);
    //     }

    // }

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    public function login(Request $request)
    {
          //validate incoming request
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);

       if( $request->username != 'emiindo') {
        return response()->json(['message' => 'Unauthorized'], 401);
       }

        $credentials = $request->only(['username', 'password']);

        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

}
