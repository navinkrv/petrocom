<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    // Create Job

    public function createJob(Request $request)
    {

        $validation = $request->validate([
            "client_id" => "required",
            "job_id" => "required",
            "date" => "required",
            "multidrop" => "required",
            "job_location_data" => "required",
            "vehicle" => "required",
            "status" => "required",
            "invoice_status" => "required",
        ]);


        if ($validation) {

            $job = new Job();

            // file upload handling
            $pod = $request->file("pod");
            $invoice = $request->file("invoice");

            $pod_filename = "pod_" . $request->job_id . "_" . $request->date . ".pdf";
            $pod_upload_location = "public/pod";
            $pod_access_location = env("UPLOAD_LOCATION") . "pod/" . $pod_filename;


            $invoice_filename = "invoice_" . $request->client_id . "_" . $request->date . ".pdf";
            $invoice_upload_location = "public/invoice";
            $invoice_access_location = env("UPLOAD_LOCATION") . "invoice/" . $invoice_filename;

            $job->client_id = $request->client_id;
            $job->job_id = $request->job_id;
            $job->date = $request->date;
            $job->multidrop = $request->multidrop;
            $job->job_location_data = (string) $request->job_location_data;
            $job->vehicle = $request->vehicle;
            $job->status = $request->status;
            $job->invoice_status = $request->invoice_status;
            $job->eta = $request->eta;
            $job->update = (string) $request->update;

            if ($pod) {
                if ($request->file("pod")->getMimeType() == "application/pdf") {
                    $request->file("pod")->storePubliclyAs($pod_upload_location, $pod_filename);
                    $job->pod = $pod_access_location;
                } else {
                    return response()->json([
                        "message" => "POD is not a pdf",
                        "status" => 0
                    ]);
                }
            }
            if ($invoice) {
                if ($request->file("invoice")->getMimeType() == "application/pdf") {
                    $request->file("invoice")->storePubliclyAs($invoice_upload_location, $invoice_filename);
                    $job->invoice = $invoice_access_location;

                } else {
                    return response()->json([
                        "status" => 0,
                        "message" => "Invoice is not a pdf"
                    ]);
                }
            }

            $job->save();
            return response()->json([
                "status" => 1,
                "message" => "Created Successfully"
            ]);
        }
    }


    public function getJobListByID(Request $request, string $id)
    {

        $jobList = Job::where("client_id", $id)->get()->getIterator()->getArrayCopy();
        if (count($jobList) > 0) {

            return response()->json([
                "message" => "success",
                "status" => 1,
                "data" => (array) $jobList
            ]);
        } else {

            return response()->json([
                "message" => "No data available",
                "status" => 0
            ]);

        }

    }

    public function getJobListDashboard(Request $request)
    {
        $job = Job::select("*")->limit(3)->get();
        if (count($job) < 1) {
            return response()->json([
                "message" => "Success",
                "status" => 1,
                "data" => $job
            ]);
        } else {
            return response()->json([
                "message" => "Data not Available",
                "status" => 0
            ]);

        }
    }

    public function updateJob(Request $request)
    {
        $validation = $request->validate([
            "job_id" => "required",
            "date" => "required",
            "multidrop" => "required",
            "job_location_data" => "required",
            "vehicle" => "required",
            "status" => "required",
            "invoice_status" => "required",
        ]);


        if ($validation) {

            $job = Job::where("job_id", $request->job_id)->get()->first();
            if ($job) {

                // file upload handling
                $pod = $request->file("pod");
                $invoice = $request->file("invoice");

                $pod_filename = "pod_" . $request->job_id . "_" . $request->date . ".pdf";
                $pod_upload_location = "public/pod";
                $pod_access_location = env("UPLOAD_LOCATION") . "pod/" . $pod_filename;


                $invoice_filename = "invoice_" . $request->client_id . "_" . $request->date . ".pdf";
                $invoice_upload_location = "public/invoice";
                $invoice_access_location = env("UPLOAD_LOCATION") . "invoice/" . $invoice_filename;

                $job->date = $request->date;
                $job->multidrop = $request->multidrop;
                $job->job_location_data = (string) $request->job_location_data;
                $job->vehicle = $request->vehicle;
                $job->status = $request->status;
                $job->invoice_status = $request->invoice_status;
                $job->eta = $request->eta;
                $job->update = (string) $request->update;

                if ($pod) {
                    if ($request->file("pod")->getMimeType() == "application/pdf") {
                        $request->file("pod")->storePubliclyAs($pod_upload_location, $pod_filename);
                        $job->pod = $pod_access_location;
                    } else {
                        return response()->json([
                            "message" => "POD is not a pdf",
                            "status" => 0
                        ]);
                    }
                }
                if ($invoice) {
                    if ($request->file("invoice")->getMimeType() == "application/pdf") {
                        $request->file("invoice")->storePubliclyAs($invoice_upload_location, $invoice_filename);
                        $job->invoice = $invoice_access_location;

                    } else {
                        return response()->json([
                            "status" => 0,
                            "message" => "Invoice is not a pdf"
                        ]);
                    }
                }

                $job->save();
                return response()->json([
                    "status" => 1,
                    "message" => "Updated Successfully"
                ]);
            } else {
                return response()->json([
                    "status" => 0,
                    "message" => "Job not found with provided job ID"
                ]);

            }
        }
    }
    public function getJobById(Request $request, string $job_id)
    {
        $job = Job::where("job_id", $job_id)->get()->first();

        if ($job) {
            return response()->json([
                "status" => 1,
                "message" => "Success",
                "data" => $job
            ]);
        } else {
            return response()->json([
                "status" => 0,
                "message" => "No data available"
            ]);

        }
    }
    public function getJobByStatus(Request $request, string $status)
    {
        $jobs = Job::where("status", $status)->get()->getIterator()->getArrayCopy();

        if (count($jobs) < 1) {
            return response()->json([
                "status" => 0,
                "message" => "No data available"
            ]);

        } else {
            return response()->json([
                "status" => 1,
                "message" => "success",
                "data" => $jobs
            ]);

        }
    }
}
