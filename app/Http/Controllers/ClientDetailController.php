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
                "primary_email" => "required | email",
                "sec_email" => "required | email",
                "phone" => "required",
                "sec_phone" => "required"
            ]);
            if ($validation) {

                //photo upload
                $photo = $request->file("photo");
                $photo_name = $request->client_name . " " . $request->company_name . ".jpg";
                $photo_upload_location = "public/client_photo";
                $photo_access_location = env("UPLOAD_LOCATION") . "client_photo/" . $photo_name;

                if ($photo->getMimeType() == "image/jpeg") {
                    $photo->storePubliclyAs($photo_upload_location, $photo_name);

                    $client = new ClientDetail();

                    $client->client_name = $request->client_name;
                    $client->company_name = $request->company_name;
                    $client->primary_email = $request->primary_email;
                    $client->sec_email = $request->sec_email;
                    $client->phone = $request->phone;
                    $client->sec_phone = $request->sec_phone;
                    $client->photo = $photo_access_location;
                    $client->save();

                    return response()->json([
                        "message" => "Saved Successfully",
                        "status" => 1

                    ]);
                } else {
                    return response()->json([
                        "message" => "Image must be JPEG",
                        "status" => 0

                    ]);

                }
            }
        } else {
            return response()->json([
                "message" => "Unauthorised",
                "status" => 0
            ]);

        }
    }

    public function getClientListAdmin(Request $request, string $pgno)
    {
        if ($request->userType == 1 || $request->userType == 2) {



            $clientList = ClientDetail::all()->getIterator()->getArrayCopy();

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
        $client = ClientDetail::find($id);

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
    }
}
