<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    // Create Job

    public function createJob(Request $request)
    {
        $userType = $request->userType;



        if ($userType == 1 || $userType == 2) {

            $validation = $request->validate([

                "job_id" => "required",
                "date" => "required",
                "multidrop" => "required",
                "job_location_data" => "required",
                "status" => "required",
                "pod" => "required",
                "invoice_status" => "required",
                "invoice" => "required",
                "eta" => "required",
                "update" => "required",
            ]);


            if ($validation) {

                $job = new Job();

                $job->user_id = $request->user()->id;
                $job->job_id = $request->job_id;
                $job->date = $request->date;
                $job->multidrop = $request->multidrop;
                $job->job_location_data = $request->job_location_data;
                $job->status = $request->status;
                $job->pod = $request->pod;
                $job->invoice_status = $request->invoice_status;
                $job->invoice = $request->invoice; //
                $job->eta = $request->eta;
                $job->update = $request->update; //

                $job->save();
                return response()->json([
                    "message" => "Saved Successfully"
                ]);
            }

        } else {

            return response()->json([
                "message" => "Unauthorized"
            ]);
        }
    }
}
