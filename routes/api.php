<?php

use App\Http\Controllers\ClientDetailController;
use App\Http\Controllers\JobController;
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
Route::get("/user/createAdminAccount", [UserController::class, "createAdminAccount"])->middleware("auth:sanctum")->middleware(SAdmin::class);

//client
Route::post("/client/createClient", [ClientDetailController::class, "createClient"])->middleware("auth:sanctum")->middleware(userTypeAuth::class);
Route::get("/client/getClientListAdmin/{pgno}", [ClientDetailController::class, "getClientListAdmin"])->middleware("auth:sanctum")->middleware(userTypeAuth::class);
Route::post("/client/createClientAccount", [ClientDetailController::class, "createClientAccount"])->middleware("auth:sanctum")->middleware(Admins::class);
Route::get("/client/getClientDetailsById/{id}", [ClientDetailController::class, "getClientDetailsById"])->middleware("auth:sanctum")->middleware(Admins::class);
Route::post("/client/createClientAccount", [ClientDetailController::class, "createClientAccount"])->middleware("auth:sanctum")->middleware(Admins::class);
Route::post("/client/listClientAccount/{id}", [ClientDetailController::class, "listClientAccount"])->middleware("auth:sanctum")->middleware(Admins::class);
Route::post("/client/updateClientPassword", [ClientDetailController::class, "updateClientPassword"])->middleware("auth:sanctum")->middleware(Admins::class);


// Jobs

Route::post("/job/create", [JobController::class, "createJob"])->middleware("auth:sanctum")->middleware(userTypeAuth::class);
Route::get("/job/getJobListAdmin", [JobController::class, "getJobListAdmin"])->middleware("auth:sanctum")->middleware(userTypeAuth::class);
