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
                "vehicle" => "required",
                "status" => "required",
                "invoice_status" => "required",
                "eta" => "required",
                "update" => "required",
            ]);


            if ($validation) {



                $job = new Job();

                // file upload handling
                $pod = $request->file("pod");
                $invoice = $request->file("invoice");

                $pod_filename = "pod_" . $request->job_id . "_" . $request->date . ".pdf";
                $pod_upload_location = "public/pod";
                $pod_access_location = env("UPLOAD_LOCATION") . "pod/" . $pod_filename;


                $invoice_filename = "invoice_" . $request->user()->id . "_" . $request->date . ".pdf";
                $invoice_upload_location = "public/invoice";
                $invoice_access_location = env("UPLOAD_LOCATION") . "invoice/" . $invoice_filename;

                $job->user_id = $request->user()->id;
                $job->job_id = $request->job_id;
                $job->date = $request->date;
                $job->multidrop = $request->multidrop;
                $job->job_location_data = $request->job_location_data;
                $job->vehicle = $request->vehicle;
                $job->status = $request->status;
                $job->pod = $pod_access_location;
                $job->invoice_status = $request->invoice_status;
                $job->invoice = $invoice_access_location;
                $job->eta = $request->eta;
                $job->update = $request->update;

                if ($request->file("pod")->getMimeType() == "application/pdf") {
                    $request->file("pod")->storePubliclyAs($pod_upload_location, $pod_filename);

                    if ($request->file("invoice")->getMimeType() == "application/pdf") {
                        $request->file("invoice")->storePubliclyAs($invoice_upload_location, $invoice_filename);

                        $job->save();
                        return response()->json([
                            "message" => "Saved Successfully"
                        ]);
                    } else {
                        return response()->json([
                            "message" => "Invoice is not a pdf"
                        ]);

                    }
                } else {
                    return response()->json([
                        "message" => "POD is not a pdf"
                    ]);

                }
            }

        } else {

            return response()->json([
                "message" => "Unauthorized"
            ]);
        }
    }


    public function getJobListAdmin(Request $request)
    {
        $userType = $request->userType;

        if ($userType == 1 || $userType == 2) {
            $jobList = Job::all()->getIterator()->getArrayCopy();
            return response()->json([
                "message" => "success",
                "data" => $jobList
            ]);

        } else {
            return response()->json([
                "message" => "Unauthorized"
            ]);

        }

    }
}
