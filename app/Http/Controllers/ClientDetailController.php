<?php

namespace App\Http\Controllers;

use App\Models\ClientDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClientDetailController extends Controller
{
    public function createClient(Request $request)
    {
        $userType = $request->userType;
        if ($userType == 1) {
            $validation = $request->validate([
                "client_name" => "required",
                "company_name" => "required",
                "primary_email" => "required | email",

                "phone" => "required",

            ]);
            if ($validation) {

                //photo upload
                $photo = $request->file("photo");
                $photo_name = $request->client_name . " " . $request->company_name . $photo->getClientOriginalExtension();
                $photo_upload_location = "public/client_photo";
                $photo_access_location = env("UPLOAD_LOCATION") . "client_photo/" . $photo_name;


                $photo->storePubliclyAs($photo_upload_location, $photo_name);

                $client = new ClientDetail();

                $client->client_name = $request->client_name;
                $client->company_name = $request->company_name;
                $client->primary_email = $request->primary_email;
                $client->sec_email = $request->sec_email;
                $client->phone = $request->phone;
                $client->sec_phone = $request->sec_phone;
                $client->photo = $photo_access_location;
                $client->approved = 1;
                $client->save();

                return response()->json([
                    "message" => "Saved Successfully",
                    "status" => 1

                ]);

            }
        } else {
            return response()->json([
                "message" => "Unauthorised",
                "status" => 0
            ]);

        }
    }
    public function updateClientData(Request $request)
    {
        //photo upload
        $photo = $request->file("photo");
        if ($photo) {

            $photo_name = $request->client_name . " " . $request->company_name . $photo->getClientOriginalExtension();
            $photo_upload_location = "public/client_photo";
            $photo_access_location = env("UPLOAD_LOCATION") . "client_photo/" . $photo_name;
        }

        $client = ClientDetail::find($request->client_id);
        if ($photo) {


            $photo->storePubliclyAs($photo_upload_location, $photo_name);
            $client->photo = $photo_access_location;

        }


        $client->client_name = $request->client_name;
        $client->company_name = $request->company_name;
        $client->primary_email = $request->primary_email;
        $client->sec_email = $request->sec_email;
        $client->phone = $request->phone;
        $client->sec_phone = $request->sec_phone;

        $client->save();

        return response()->json([
            "message" => "Updated Successfully",
            "status" => 1

        ]);

    }

    public function getClientListAdmin(Request $request, string $pgno)
    {
        if ($request->userType == 1 || $request->userType == 2) {



            $clientList = ClientDetail::where("approved", 1)->get()->getIterator()->getArrayCopy();

            if (count($clientList) != 0) {

                $dataCount = count($clientList);
                $pageCount = 0;
                if ($dataCount % 10 == 0) {
                    $pageCount = $dataCount / 10;
                } else {
                    $pageCount = ($dataCount / 10) + 1;
                }

                $clientListFinal = array_slice($clientList, ($pgno - 1) * 10, 10);


                return response()->json([
                    "message" => "success",
                    "data" => $clientListFinal,
                    "status" => 1
                ]);
            } else {
                return response()->json([
                    "message" => "No data found",
                    "status" => 0
                ]);
            }
        } else {
            return response()->json([
                "message" => "Unauthorised",
                "status" => 0
            ]);

        }
    }

    public function getClientDetailsById(Request $request, string $id)
    {
        $client = ClientDetail::where("id", $id)->with("user")->get()->first();

        if ($client) {
            return response()->json([
                "message" => "Data found",
                "data" => $client,
                "status" => 1
            ]);

        } else {
            return response()->json([
                "message" => "Invalid ID",
                "status" => 0
            ]);

        }
    }
    public function createClientAccount(Request $request)
    {
        $validation = $request->validate([
            "client_id" => "required",
            'email' => "required | email",
            "password" => "required"
        ]);

        if ($validation) {
            $existingUser = User::where("email", $request->email)->get()->first();

            if ($existingUser) {
                return response()->json([
                    "message" => "Account already Exists",
                    "status" => 0
                ]);
            }
            $user = new User();
            $user->client_id = $request->client_id;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->type = 3;

            $user->save();
            return response()->json([
                "message" => "Created Successfully",
                "status" => 1
            ]);

        }
    }

    public function listClientAccount(Request $request, string $id)
    {
        $accounts = User::select("id,client_id,email")->where("client_id", $id)->get()->getIterator()->getArrayCopy();

        if (count($accounts) < 1) {
            return response()->json([
                "message" => "No data found",
                "status" => 0
            ]);

        } else {
            return response()->json([
                "message" => "Data found",
                "status" => 1,
                "data" => $accounts
            ]);

        }
    }

    public function deleteClient(Request $request, string $id)
    {
        $client = ClientDetail::where("id", $id)->get()->first();
        if ($client) {
            $client->approved = 0;
            $client->save();
            return response()->json([
                "message" => "Successfully deleted",
                "status" => 1
            ]);

        } else {
            return response()->json([
                "message" => "Invalid ID",
                "status" => 0
            ]);

        }
    }
    public function updateClientAccount(Request $request)
    {
        $validation = $request->validate([
            "user_id" => "required",
            "email" => "required",
            "password" => "required"
        ]);

        if ($validation) {
            $user = User::find($request->user_id);

            if ($user) {
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $user->save();
                return response()->json([
                    "message" => "Password Updated",
                    "status" => 1,
                ]);

            } else {
                return response()->json([
                    "message" => "Something went wrong",
                    "status" => 0
                ]);

            }

        }
    }
}
