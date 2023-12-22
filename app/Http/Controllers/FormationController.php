<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Formation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreFormationRequest;
use App\Http\Requests\UpdateFormationRequest;
use OpenAi\Annotations as OA;





class FormationController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('role:admin')->except(['index', 'show']);
    // }

    public function index( Formation $formation)
    {
    
       $formation=Formation::all();
         return response()->json(compact('formation'), 200);
       
    }

    /**
     * @OA\Post(
     *     path="/formations",
     *     summary="Ajouter une nouvelle formation",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nom", type="string"),
     *             @OA\Property(property="date_candidature", type="string", format="date"),
     *             @OA\Property(property="date_limite_candidature", type="string", format="date")
     *         )
     *     ),
     *     @OA\Response(response="201", description="Formation ajoutée avec succès"),
     *     @OA\Response(response="422", description="Erreur de validation")
     * )
     */
    public function store(Request $request)
{
   // Validation des données du formulaire
//    
// if (!Auth::check()) {
//     return response()->json(['errors' => 'Vous devez être connecté pour effectuer une candidature.'], 401);
// }
// $user = Auth::User();
//    if (!$user->hasRole('admin')) {
//     return response()->json([
//         "message" => "Accès non autorisé"
//     ], 403);
// }

    $validator = Validator::make($request->all(), [
        'nom' => 'required',
        'date_candidature' => 'required',
        'date_limite_candidature' => 'required',
    ]);

    // Vérification de la validation
    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

  $user = Auth::user();
        // $candidatRole = Role::where('nonRole', 'admin')->first(); // Assurez-vous que 'candidat' est le nom du rôle
    
        // if (!$user->hasRole($candidatRole)) {
        //     return response()->json(['errors' => 'Vous devez être un admin pour effectuer une candidature.'], 422);
        // }
        
    // Création d'une nouvelle instance de Formation
      Formation::create([
        'nom' =>$request->nom,
        'date_candidature' => $request->date_candidature,
        'date_limite_candidature' => $request->date_limite_candidature,
    ]);
    return response()->json(['message' => 'FORMATION ajouté avec succès'], 201);


 }


    /**
     * @OA\Get(
     *     path="/formations/{id}",
     *     summary="Afficher les détails d'une formation",
     *     @OA\Parameter(name="id", in="path", required=true, description="ID de la formation"),
     *     @OA\Response(response="200", description="Détails de la formation")
     * )
     */
    public function show(Formation $formation ,$id)
    {
        $formation = Formation::find($id);
        return response()->json(compact('formation'), 200);
    }
 /**
     * @OA\Patch(
     *     path="/formations/{id}",
     *     summary="Modifier une formation existante",
     *     @OA\Parameter(name="id", in="path", required=true, description="ID de la formation"),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nom", type="string"),
     *             @OA\Property(property="date_candidature", type="string", format="date"),
     *             @OA\Property(property="date_limite_candidature", type="string", format="date")
     *         )
     *     ),
     *     @OA\Response(response="201", description="Formation modifiée avec succès"),
     *     @OA\Response(response="404", description="Formation non trouvée")
     * )
     */
    public function update(Request $request,$id )
    {
       
        $formationpdate=Formation::findorFail($request->id);
        $formationpdate->nom =$request->nom;
        $formationpdate-> date_candidature = $request->date_candidature;
        $formationpdate->date_limite_candidature=$request->date_limite_candidature;
        $formationpdate->save();

    return response()->json(['message' => 'formation modifier avec succès'], 201);

    }

     /**
     * @OA\Delete(
     *     path="/formations/{id}",
     *     summary="Supprimer une formation",
     *     @OA\Parameter(name="id", in="path", required=true, description="ID de la formation"),
     *     @OA\Response(response="200", description="Formation supprimée avec succès"),
     *     @OA\Response(response="404", description="Formation non trouvée")
     * )
     */
    public function destroy($id)
    {
    Formation::find($id)->delete();
    return response()->json(['message' => 'Formation supprimé avec succès'], 200);
    }
}
