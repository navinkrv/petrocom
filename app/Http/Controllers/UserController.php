<?php

namespace App\Http\Controllers;

use App\Models\ClientDetail;
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
                if ($existingUser->type == 3) {
                    $client = ClientDetail::where("id", $existingUser->client_id)->get()->first();

                    if ($client->approved == 1) {
                        $token = $existingUser->createToken("auth_token");
                        if (Hash::check($request->password, $existingUser->password)) {
                            return response()->json([
                                "message" => "Login Successfull",
                                "status" => 1,
                                "token" => $token->plainTextToken,
                                "type" => $existingUser->type,
                                "client_data" => $client
                            ]);
                        } else {
                            return response()->json([
                                "message" => "Incorrect password",
                                "status" => 0
                            ]);

                        }

                    } else {
                        return response()->json([
                            "message" => "Account Disabled contact Administrator",
                            "status" => 0
                        ]);

                    }

                } else {
                    $token = $existingUser->createToken("auth_token");
                    if (Hash::check($request->password, $existingUser->password)) {
                        return response()->json([
                            "message" => "Login Successfull",
                            "status" => 1,
                            "token" => $token->plainTextToken,
                            "type" => $existingUser->type
                        ]);
                    } else {
                        return response()->json([
                            "message" => "Incorrect password",
                            "status" => 0
                        ]);

                    }
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
            $existingUser = User::where("email", $request->email)->get()->first();

            if (!$existingUser) {
                $user = new User();
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $user->type = 2;


                $user->save();

                return response()->json([
                    "message" => "Created Successfully",
                    "status" => 1
                ]);

            } else {
                return response()->json([
                    "message" => "Account already exists",
                    "status" => 0
                ]);

            }


        }
    }

    public function listAdminAccount(Request $request)
    {
        $admins = User::where("type", 2)->get()->getIterator()->getArrayCopy();
        if (count($admins) > 0) {
            return response()->json([
                "message" => "Data found",
                "status" => 1,
                "data" => $admins
            ]);

        } else {
            return response()->json([
                "message" => "Data not found",
                "status" => 0,

            ]);

        }
    }
    public function updateAdminAccount(Request $request)
    {
        $validation = $request->validate([
            "id" => "required",
            "password" => "required",
            "email" => "required"
        ]);

        $user = User::find($request->id);
        $user->password = Hash::make($request->password);
        $user->email = $request->email;

        $user->save();
        return response()->json([
            "message" => "Updated Successfully",
            "status" => 1
        ]);

    }

}
