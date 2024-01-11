<?php

use App\Http\Controllers\ClientDetailController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\MailController;
use App\Http\Middleware\Admins;
use App\Http\Middleware\SAdmin;
use App\Http\Middleware\userTypeAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });



// Users

Route::post("/user/login", [UserController::class, "login"]);
Route::get("/user/getUserData", [UserController::class, "getUserData"])->middleware("auth:sanctum");
Route::post("/user/createAdminAccount", [UserController::class, "createAdminAccount"])->middleware("auth:sanctum")->middleware(SAdmin::class);
Route::post("/user/updateAdminAccount", [UserController::class, "updateAdminAccount"])->middleware("auth:sanctum")->middleware(SAdmin::class);
Route::get("/user/listAdminAccount", [UserController::class, "listAdminAccount"])->middleware("auth:sanctum")->middleware(SAdmin::class);

//client
Route::post("/client/createClient", [ClientDetailController::class, "createClient"])->middleware("auth:sanctum")->middleware(userTypeAuth::class);
Route::post("/client/updateClientData", [ClientDetailController::class, "updateClientData"])->middleware("auth:sanctum")->middleware(Admins::class);
Route::get("/client/getClientListAdmin/{pgno}", [ClientDetailController::class, "getClientListAdmin"])->middleware("auth:sanctum")->middleware(userTypeAuth::class);
Route::post("/client/createClientAccount", [ClientDetailController::class, "createClientAccount"])->middleware("auth:sanctum")->middleware(Admins::class);
Route::get("/client/getClientDetailsById/{id}", [ClientDetailController::class, "getClientDetailsById"])->middleware("auth:sanctum")->middleware(Admins::class);
Route::post("/client/createClientAccount", [ClientDetailController::class, "createClientAccount"])->middleware("auth:sanctum")->middleware(SAdmin::class);
Route::post("/client/listClientAccount/{id}", [ClientDetailController::class, "listClientAccount"])->middleware("auth:sanctum")->middleware(Admins::class);
Route::post("/client/updateClientAccount", [ClientDetailController::class, "updateClientAccount"])->middleware("auth:sanctum")->middleware(Admins::class);
Route::get("/client/deleteClient/{id}", [ClientDetailController::class, "deleteClient"])->middleware("auth:sanctum")->middleware(SAdmin::class);


// Jobs

Route::post("/job/create", [JobController::class, "createJob"])->middleware("auth:sanctum")->middleware(Admins::class);
Route::post("/job/update", [JobController::class, "updateJob"])->middleware("auth:sanctum")->middleware(Admins::class);
Route::post("/job/getJobByDateRangeByClient", [JobController::class, "getJobByDateRangeByClient"])->middleware("auth:sanctum");
Route::post("/job/getJobByDateRangeForAll", [JobController::class, "getJobByDateRangeForAll"])->middleware("auth:sanctum");
Route::get("/job/getJobListByID/{id}", [JobController::class, "getJobListByID"])->middleware("auth:sanctum");
Route::get("/job/getJobByStatus/{status}", [JobController::class, "getJobByStatus"])->middleware("auth:sanctum");
Route::get("/job/getJobByStatusByClient/{client_id}/{status}", [JobController::class, "getJobByStatusByClient"])->middleware("auth:sanctum");
Route::get("/job/getJobByInvoiceStatusByClient/{client_id}/{invoice_status}", [JobController::class, "getJobByInvoiceStatusByClient"])->middleware("auth:sanctum");
Route::get("/job/getJobByInvoiceStatusAll/{invoice_status}", [JobController::class, "getJobByInvoiceStatusAll"])->middleware("auth:sanctum");
Route::get("/job/getJobById/{job_id}", [JobController::class, "getJobById"]);
Route::get("/job/getJobListDashboard", [JobController::class, "getJobListDashboard"])->middleware("auth:sanctum")->middleware(Admins::class);


//mailer

Route::post("/mailer/sendLoad", [MailController::class, "sendLoad"]);
