<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PassportAuthController extends Controller
{
    /**
     * Registration
     */

     public function register(Request $request)
     {
         $this->validate($request,[
             'name' => 'required',
             'email' => 'required|email',
             'password' => 'required'
         ]);

         $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
         ]);

         $token = $user->createToken('LaravelAuthApp')->accessToken;
 
         return response()->json(['token' => $token], 200);

     }

     public function login( Request $request)
     {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if(Auth::attempt($data))
        {
            $token = Auth::user()->createToken('LaravelAuthApp')->accessToken;
            return response()->json(['token' =>$token], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
     }

     public function logout(Request $request)
     {
        $request->user()->token()->revoke(); //Logout
        // $request->user()->token()->delete(); // Delete row
        return response()->json([
            'status' => 'success',
            "message" => "User logged out successfully."
        ]);
     }
}
