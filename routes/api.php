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

//CANDIDATURE


Route::middleware('auth:api','role:admin')->group( function (){
   
    
});
// Route::post('AJOUTER/{formation_id}', [CandidatureController::class ,'store']);
Route::post('update/{id}', [FormationController::class ,'update']);
 Route::post('destroy/{id}', [FormationController::class ,'destroy']);
 Route::post('/accepter-candidature/{candidatureId}', [CandidatureController::class, 'acceptCandidature']);
 Route::post('/refuser-candidature/{candidatureId}', [CandidatureController::class, 'refuseCandidature']);
 Route::get('show', [CandidatureController::class ,'listCandidaturesEnAttente']);
 Route::get('/listrefuser ', [CandidatureController::class ,'listCandidaturesRefusees']);
 Route::get('/showcandidature', [CandidatureController::class ,'candidatAcceptes']);
 Route::post('formation', [FormationController::class ,'store']);
 Route::get('liste_candidat',[CandidatureController::class,'ListeCandidat']);

//  Route::group(['middleware' => 'role:admin'], function () {
    
// });

Route::get('/index', [FormationController::class ,'index']);
Route::get('show/{id}', [FormationController::class ,'show']);