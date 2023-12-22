<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\Candidature;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreCandidatureRequest;
use App\Http\Requests\UpdateCandidatureRequest;
use OpenAi\Annotations as OA;




/**
 * @OA\Info(
 *     title="la doccumentation  du projet",
 *     version="1.0.0",
 *     title="Swagger Petstore"
 * )
 * @OA\PathItem(path="/candidature")
 */

class CandidatureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

   /**
     * @OA\Post(
     *     path="/candidature/{formation_id}",
     *     summary="Soumettre une nouvelle candidature",
     *     @OA\Parameter(name="formation_id", in="path", required=true, description="ID de la formation"),
     *     @OA\Response(response="200", description="Candidature effectuée avec succès"),
     *     @OA\Response(response="422", description="Non autorisé ou erreur de validation")
     * )
     */
    public function store(Request $request,Formation $formation_id)
    {
       
         if (!Auth::check()) {
        return response()->json(['errors' => 'Vous ne pouvez pas vous candidater, veuillez vous connecter d\'abord.'], 422);
    }

    // L'utilisateur est connecté, continuer avec la création de candidature
    $formation = Formation::findOrFail($formation_id->id);

    // Créer une nouvelle instance de Candidature
    $candidature = new Candidature();

    // Attribuer les valeurs aux propriétés de la candidature
    $candidature->formation_id = $formation->id;
    $candidature->user_id = Auth::id();  // Utilisez simplement Auth::id() pour obtenir l'ID de l'utilisateur connect
    $candidature->save();
  

    return response()->json(['message' => 'Candidature effectuée avec succès.'], 200);
   

    

    }

/**
     * @OA\Patch(
     *     path="/candidature/accepter/{candidatureId}",
     *     summary="Accepter une candidature",
     *     @OA\Parameter(name="candidatureId", in="path", required=true, description="ID de la candidature"),
     *     @OA\Response(response="200", description="Candidature acceptée avec succès")
     * )
     */
    public function acceptCandidature($candidatureId)
    {
        $candidature = Candidature::findOrFail($candidatureId);
    
        // Vérifier si l'utilisateur actuel a le droit d'accepter la candidature (vous pouvez personnaliser cela selon vos besoins)
    
        $candidature->status = 'accepter';
        $candidature->save();
    
        return response()->json(['message' => 'Candidature acceptée avec succès.'], 200);
    }


    /**
     * @OA\Patch(
     *     path="/candidature/refuser/{candidatureId}",
     *     summary="Refuser une candidature",
     *     @OA\Parameter(name="candidatureId", in="path", required=true, description="ID de la candidature"),
     *     @OA\Response(response="200", description="Candidature refusée avec succès")
     * )
     */
    public function refuseCandidature($candidatureId)
    {
        $candidature = Candidature::findOrFail($candidatureId);
    
        // Vérifier si l'utilisateur actuel a le droit de refuser la candidature (vous pouvez personnaliser cela selon vos besoins)
    
        $candidature->status = 'refuser';
        $candidature->save();
    
        return response()->json(['message' => 'Candidature refusée avec succès.'], 200);
    }
    /**
     * @OA\Get(
     *     path="/candidature/en_attente",
     *     summary="Liste des candidatures en attente",
     *     @OA\Response(response="200", description="Liste des candidatures en attente")
     * )
     */
    public function listCandidaturesEnAttente(Request $request)
    {
        // $candidature=Candidature::all();
        //  return response()->json(compact('candidature'), 200);
        $candidaturesEnAttente = Candidature::where('status', 'En_Attente')->get();
    
        return response()->json(['candidatures' => $candidaturesEnAttente], 200);
    }
    



    /**
     * @OA\Get(
     *     path="/candidature/acceptees",
     *     summary="Liste des candidatures acceptées",
     *     @OA\Response(response="200", description="Liste des candidatures acceptées")
     * )
     */
    public function candidatAcceptes (Request $request)
    {
        
        $candidaturesAcceptees = Candidature::where('status', 'accepter')->get();
    
        return response()->json(['candidatures' => $candidaturesAcceptees], 200);
    }
    

public function ListeCandidat()
{
$candidature = Candidature::all();
return response(['candidture'=>$candidature ],200);
}

    /**
     * @OA\Get(
     *     path="/candidature/refusees",
     *     summary="Liste des candidatures refusées",
     *     @OA\Response(response="200", description="Liste des candidatures refusées")
     * )
     */
    public function listCandidaturesRefusees(Request $request)
    {
        
        $candidaturesRefusees = Candidature::where('status', 'refuser')->get();
    
        return response()->json(['candidatures' => $candidaturesRefusees], 200);
    }
}
