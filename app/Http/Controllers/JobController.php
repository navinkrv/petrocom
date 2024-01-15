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
            $existingJob = Job::where("job_id", $request->job_id)->get()->first();
            if ($existingJob) {
                return response()->json([
                    "message" => "Job ID already Exists",
                    "status" => 0
                ]);
            }

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
            $job->job_location_data = $request->job_location_data;
            $job->vehicle = $request->vehicle;
            $job->status = $request->status;
            $job->invoice_status = $request->invoice_status;
            $job->eta = $request->eta;
            $job->update = $request->update;

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

        $jobList = Job::with("client:id,client_name")->where("client_id", $id)->get()->getIterator()->getArrayCopy();
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
        // $job = Job::with("client:id,approved")->select("*")->orderBy("id", "desc")->limit(3)->get()->getIterator()->getArrayCopy();
        $job = Job::with("client:id,client_name,approved")->select("*")->latest()->take(10)->get()->getIterator()->getArrayCopy();

        if (count($job) > 0) {
            $new_job_array = array_filter($job, function ($data) {
                if ($data->client->approved != 0) {
                    return $data;
                }
            });
            if (count($new_job_array) < 1) {
                return response()->json([
                    "message" => "No Data Available",
                    "status" => 0,
                ]);

            } else {

                return response()->json([
                    "message" => "Success",
                    "status" => 1,
                    "data" => $new_job_array
                ]);
            }
        } else {
            return response()->json([
                "message" => "Data not Available",
                "status" => 0,
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
                if ($pod) {
                    $pod_filename = "pod_" . $request->job_id . "_" . $request->date . ".pdf";
                    $pod_upload_location = "public/pod";
                    $pod_access_location = env("UPLOAD_LOCATION") . "pod/" . $pod_filename;

                }
                $invoice = $request->file("invoice");
                if ($invoice) {
                    $invoice_filename = "invoice_" . $request->client_id . "_" . $request->date . ".pdf";
                    $invoice_upload_location = "public/invoice";
                    $invoice_access_location = env("UPLOAD_LOCATION") . "invoice/" . $invoice_filename;
                }

                $job->date = $request->date;
                $job->multidrop = $request->multidrop;
                $job->job_location_data = $request->job_location_data;
                $job->vehicle = $request->vehicle;
                $job->status = $request->status;
                $job->invoice_status = $request->invoice_status;
                $job->eta = $request->eta;
                $job->update = $request->update;

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

    public function getJobByDateRangeByClient(Request $request)
    {
        $jobs = Job::with("client:id,approved")->where("client_id", $request->client_id)->get()->getIterator()->getArrayCopy();
        $fromDate = $request->from;
        $toDate = $request->to;
        // $fromDate = str_replace(" (India Standard Time)", "", $request->from);
        // $toDate = str_replace(" (India Standard Time)", "", $request->to);

        if (count($jobs) > 0) {

            $new_job_array = array_filter($jobs, function ($data) {
                if ($data->client->approved != 0) {
                    return $data;
                }
            });
            $final_job_arr = [];
            array_filter($new_job_array, function ($data) use ($fromDate, $toDate, &$final_job_arr) {

                if (date_create($data->created_at) >= date_create($fromDate) && date_create($data->created_at) <= date_create($toDate)) {
                    // return $data;
                    $final_job_arr[] = $data;
                }
            });


            if (count($final_job_arr) > 0) {
                return response()->json([
                    "status" => 1,
                    "message" => "success",
                    "data" => $final_job_arr
                ]);

            } else {
                return response()->json([
                    "status" => 0,
                    "message" => "No matching data found for the selected date range",


                ]);


            }
        } else {
            return response()->json([
                "status" => 0,
                "message" => "No Data Available"
            ]);

        }

    }

    public function getJobByDateRangeForAll(Request $request)
    {
        $jobs = Job::with("client:id,approved")->get()->getIterator()->getArrayCopy();
        $fromDate = $request->from;
        $toDate = $request->to;
        // $fromDate = str_replace(" (India Standard Time)", "", $request->from);
        // $toDate = str_replace(" (India Standard Time)", "", $request->to);

        if (count($jobs) > 0) {

            $new_job_array = array_filter($jobs, function ($data) {
                if ($data->client->approved != 0) {
                    return $data;
                }
            });
            $final_job_arr = [];
            array_filter($new_job_array, function ($data) use ($fromDate, $toDate, &$final_job_arr) {

                if (date_create($data->created_at) >= date_create($fromDate) && date_create($data->created_at) <= date_create($toDate)) {
                    // return $data;
                    $final_job_arr[] = $data;
                }
            });


            if (count($final_job_arr) > 0) {
                return response()->json([
                    "status" => 1,
                    "message" => "success",
                    "data" => $final_job_arr
                ]);

            } else {
                return response()->json([
                    "status" => 0,
                    "message" => "No matching data found for the selected date range",


                ]);


            }
        } else {
            return response()->json([
                "status" => 0,
                "message" => "No Data Available"
            ]);

        }

    }

    public function getJobByStatus(Request $request, string $status)
    {
        $jobs = Job::with("client:id,approved")->whereRaw("status='$status' or invoice_status='$status'")->get()->getIterator()->getArrayCopy();
        $new_job_array = array();

        if (count($jobs) < 1) {
            return response()->json([
                "status" => 0,
                "message" => "No data available"
            ]);

        } else {
            array_filter($jobs, function ($data) use (&$new_job_array) {
                if ($data->client->approved != 0) {
                    // echo $data;
                    // array_push($new_job_array, $data);
                    $new_job_array[] = $data;
                    // return $data;
                }
            });
            if (count($new_job_array) < 1) {
                return response()->json([
                    "status" => 0,
                    "message" => "No data Available for th selected filter",

                ]);
            } else {

                return response()->json([
                    "status" => 1,
                    "message" => "success",
                    "data" => $new_job_array
                ]);

            }
        }
    }
    public function getJobByStatusByClient(Request $request, string $client_id, string $status)
    {

        $jobs = array();
        if ($status == "paid" || $status == "due") {

            $jobs = Job::with("client:id,approved")->whereRaw("invoice_status='$status' and client_id = '$client_id'")->get()->getIterator()->getArrayCopy();
        } else {

            $jobs = Job::with("client:id,approved")->whereRaw("status='$status' and client_id = '$client_id'")->get()->getIterator()->getArrayCopy();
        }
        $new_job_array = array();


        if (count($jobs) < 1) {
            return response()->json([
                "status" => 0,
                "message" => "No data available"
            ]);

        } else {
            array_filter($jobs, function ($data) use (&$new_job_array) {
                if ($data->client->approved != 0) {
                    // echo $data;
                    // array_push($new_job_array, $data);
                    $new_job_array[] = $data;
                    // return $data;
                }
            });
            if (count($new_job_array) < 1) {
                return response()->json([
                    "status" => 0,
                    "message" => "No data Available for th selected filter",

                ]);

            } else {

                return response()->json([
                    "status" => 1,
                    "message" => "success",
                    "data" => $new_job_array
                ]);
            }

        }
    }


    public function getJobByInvoiceStatusAll(Request $request, string $invoice_status)
    {
        $jobs = Job::with("client:id,approved")->where("invoice_status", $invoice_status)->get()->getIterator()->getArrayCopy();
        $new_job_array = array();

        if (count($jobs) < 1) {
            return response()->json([
                "status" => 0,
                "message" => "No data available"
            ]);

        } else {
            array_filter($jobs, function ($data) use (&$new_job_array) {
                if ($data->client->approved != 0) {
                    // echo $data;
                    // array_push($new_job_array, $data);
                    $new_job_array[] = $data;
                    // return $data;
                }
            });
            if (count($new_job_array) < 1) {
                return response()->json([
                    "status" => 0,
                    "message" => "No data Available for th selected filter",

                ]);
            } else {

                return response()->json([
                    "status" => 1,
                    "message" => "success",
                    "data" => $new_job_array
                ]);

            }
        }
    }
    public function getJobByInvoiceStatusByClient(Request $request, string $client_id, string $invoice_status)
    {
        $jobs = Job::with("client:id,approved")->whereRaw("invoice_status='$invoice_status' and client_id = '$client_id'")->get()->getIterator()->getArrayCopy();
        $new_job_array = array();

        if (count($jobs) < 1) {
            return response()->json([
                "status" => 0,
                "message" => "No data available"
            ]);

        } else {
            array_filter($jobs, function ($data) use (&$new_job_array) {
                if ($data->client->approved != 0) {
                    // echo $data;
                    // array_push($new_job_array, $data);
                    $new_job_array[] = $data;
                    // return $data;
                }
            });
            return response()->json([
                "status" => 1,
                "message" => "success",
                "data" => $new_job_array
            ]);

        }
    }
}
