<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{

    public function login(Request $request)
    {

        $validator = $request->validate([
            "email" => "required | email",
            "password" => "required"
        ]);

        if ($validator) {
            $existingUser = User::whereRaw("email= '$request->email'")->get()->first();

            if ($existingUser) {
                $token = $existingUser->createToken("auth_token");

                if (Hash::check($request->password, $existingUser->password)) {
                    return response()->json([
                        "message" => "Login Successfull",
                        "token" => $token->plainTextToken,
                        "type" => $existingUser->type
                    ]);
                } else {
                    return response()->json([
                        "message" => "Incorrect password"
                    ]);

                }
            } else {
                return response()->json([
                    "message" => "User Not Found"
                ]);

            }

            return response()->json([
                "message" => "Credentials Not Match",
            ]);
        }


    }
}
