<?php

namespace App\Http\Controllers;

use App\Models\ClientDetail;
use Illuminate\Http\Request;

class ClientDetailController extends Controller
{
    public function createClient(Request $request)
    {
        $userType = $request->userType;
        if ($userType == 1) {
            $validation = $request->validate([
                "client_name" => "required",
                "company_name" => "required",
                "phone" => "required",
                "sec_phone" => "required"
            ]);
            if ($validation) {

                //photo upload
                $photo = $request->file("photo");
                $photo_name = $request->client_name . " " . $request->company_name . ".jpg";
                $photo_upload_location = "public/client_photo";
                $photo_access_location = env("UPLOAD_LOCATION") . "/clientPhoto/" . $photo_name;

                if ($photo->getMimeType() == "image/jpg") {
                    $photo->storePubliclyAs($photo_upload_location, $photo_name);

                    $client = new ClientDetail();

                    $client->client_name = $request->client_name;
                    $client->company_name = $request->company_name;
                    $client->phone = $request->phone;
                    $client->sec_phone = $request->sec_phone;
                    $client->photo = $photo_access_location;
                    $client->save();

                    return response()->json([
                        "message" => "success",

                    ]);
                }
            }
        } else {
            return response()->json([
                "message" => "Unauthorised",

            ]);

        }
    }
    public function createClientAccount(Request $request)
    {

    }
}
