<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CandidatureController;
use App\Http\Controllers\FormationController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//creation de role
 Route::post('/role', [UserController::class ,'ajouterRole']);
Route::post('inscrirCandidat', [UserController::class ,'inscrirCandidat']);
Route::post('inscrirAdmin', [UserController::class ,'inscrirAdmin']);
Route::post('login', [UserController::class ,'login']);
// Route::post('login', [UserController::class ,'login']);
Route::post('/formation', [FormationController::class ,'store']);
//CANDIDATURE
Route::post('AJOUTER/{formation_id}', [CandidatureController::class ,'store'])->middleware('auth:api');


// Route::apiResource('/formation',FormationController::class);
// Route::apiResource('/formation{formationid}',FormationController::class);

// Route::post('inscrirCandidat', 'inscrirCandidat');
    Route::middleware('auth:api')->group(function(){
 //FORMATION
 
 Route::post('index', [FormationController::class ,'index']);
 Route::post('show/{id}', [FormationController::class ,'show']);
 Route::post('update/{id}', [FormationController::class ,'update']);
 Route::post('destroy/{id}', [FormationController::class ,'destroy']);
 Route::post('/accepter-candidature/{candidatureId}', [CandidatureController::class, 'acceptCandidature']);
 Route::post('/refuser-candidature/{candidatureId}', [CandidatureController::class, 'refuseCandidature']);
 Route::post(' show', [CandidatureController::class ,'listCandidaturesEnAttente']);
// Route::post(' store/{id}', [CandidatureController::class ,'store']);

 
 });
 Route::post(' /listrefuser', [CandidatureController::class ,'listCandidaturesRefusees']);
 Route::post(' showrAccepter', [CandidatureController::class ,' listAcceptes']);
Route::controller(AuthController::class)->group(function () {
    // Route::post('login', 'login');
    // Route::post('formation', 'formation.store');
   
    // Route::post('inscrirAdmin', 'inscrirAdmin');
    // Route::post('logout', 'logout');
    // Route::post('refresh', 'refresh');
    // Route::post('ajouterRole','ajouterRole');
});