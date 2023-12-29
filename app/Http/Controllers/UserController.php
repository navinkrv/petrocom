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

        }


    }

    // 2. Get User Data
    public function getUserData(Request $request)
    {
        $user = User::where("id", $request->user()->id)->with("clientDetails")->get()->first();
        return response()->json([
            "message" => "Data Found",
            "status" => 0,
            "data" => $user
        ]);
    }

    public function createAdminAccount(Request $request)
    {
        $validation = $request->validate([
            "email" => "required",
            "password" => "required"
        ]);

        if ($validation) {
            $user = new User();
            $user->email = $request->email;
            $user->password = $request->password;
            $user->save();

            return response()->json([
                "message" => "Saved Successfully",
                "status" => 1
            ]);

        }
    }

    public function listAdminAccount()
    {
    }
    public function updateAdminPassword(Request $request)
    {
        $validation = $request->validate([
            "id" => "required",
            "password" => "required"
        ]);

        $user = User::find($request->id);
        $user->password = Hash::make($request->password);

        $user->save();
        return response()->json([
            "message" => "Updated Successfully",
            "status" => 1
        ]);

    }

}
