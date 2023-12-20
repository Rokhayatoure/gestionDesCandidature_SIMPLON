<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use OpenAi\Annotations as OA;




/**
 * 
 * 
 */

class UserController extends Controller
{
    /**
    * @OA\Post(
    *     path="/users/ajouterRole",
    *     summary="Ajouter un nouveau rôle",
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\JsonContent(
    *             @OA\Property(property="nonRole", type="string")
    *         )
    *     ),
    *     @OA\Response(response="201", description="Rôle ajouté avec succès"),
    *     @OA\Response(response="422", description="Erreur de validation")
    * )
    */
  public function ajouterRole(Request $request)
  {
      $request->validate([
          'nonRole' => 'required',
      ]);

      $role = Role::create([
          'nonRole' => $request->nonRole,
      ]);

      return response()->json(['message' => 'Rôle ajouté avec succès', 'role' => $role], 201);
  }
  /**
     * @OA\Post(
     *     path="/users/inscrirCandidat",
     *     summary="Inscrire un candidat",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nom", type="string"),
     *             @OA\Property(property="prenom", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string"),
     *             @OA\Property(property="adresse", type="string"),
     *             @OA\Property(property="niveau_etude", type="string"),
     *             @OA\Property(property="date_naissance", type="string", format="date")
     *         )
     *     ),
     *     @OA\Response(response="201", description="Utilisateur ajouté avec succès"),
     *     @OA\Response(response="422", description="Erreur de validation")
     * )
     */
  public function inscrirCandidat(Request $request)
  {
      $validator = Validator::make($request->all(), [
          'nom' => ['required', 'string', 'min:4', 'regex:/^[a-zA-Z]+$/'],
          'prenom' => ['required', 'string', 'min:4', 'regex:/^[a-zA-Z]+$/'],
          'email' => ['required', 'email', 'unique:users,email'],
  
          'adresse' => ['required', 'string', 'regex:/^[a-zA-Z0-9 ]+$/'],
         'niveauEtude'=>['required','string'],
         'date_de_naissance'=>['required']
      ]);
      if ($validator->fails()) {
          return response()->json(['errors' => $validator->errors()], 422);
      }

     
      $roleCandidate = Role::where('nonRole', 'candidat')->first();
      $user = User::create([
          'nom' => $request->nom,
          'prenom' => $request->prenom,
          'email' => $request->email,
          'password' => Hash::make($request->password),
          'adresse' => $request->adresse,
          'niveauEtude' => $request->niveau_etude,
          'date_de_naissance' => $request->date_naissance,
          'role_id' => $roleCandidate->id,
       
      ]);

      $user->roles()->attach($roleCandidate);

      return response()->json(['message' => 'Utilisateur ajouté avec succès'], 201);
  }
  /**
     * @OA\Post(
     *     path="/users/inscrirAdmin",
     *     summary="Inscrire un administrateur",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nom", type="string"),
     *             @OA\Property(property="prenom", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string"),
     *             @OA\Property(property="adresse", type="string")
     *         )
     *     ),
     *     @OA\Response(response="201", description="Utilisateur ajouté avec succès"),
     *     @OA\Response(response="422", description="Erreur de validation")
     * )
     */
  public function inscrirAdmin(Request $request)
  {
      $validator = Validator::make($request->all(), [
          'nom' => ['required', 'string', 'min:4', 'regex:/^[a-zA-Z]+$/'],
          'prenom' => ['required', 'string', 'min:4', 'regex:/^[a-zA-Z]+$/'],
          'email' => ['required', 'email', 'unique:users,email'],
  
          'adresse' => ['required', 'string', 'regex:/^[a-zA-Z0-9 ]+$/'],
         
      ]);
      if ($validator->fails()) {
          return response()->json(['errors' => $validator->errors()], 422);
      }

     
      $roleCandidate = Role::where('nonRole', 'Admin')->first();
      $user = User::create([
          'nom' => $request->nom,
          'prenom' => $request->prenom,
          'email' => $request->email,
          'password' => Hash::make($request->password),
          'adresse' => $request->adresse,
           'role_id' => $roleCandidate->id,
       
      ]);

      $user->roles()->attach($roleCandidate);

      return response()->json(['message' => 'Utilisateur ajouté avec succès'], 201);
  }
   /**
     * @OA\Post(
     *     path="/users/login",
     *     summary="Connecter un utilisateur",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Utilisateur connecté avec succès"),
     *     @OA\Response(response="422", description="Détails invalides")
     * )
     */
  public function login(Request $request)
    {

        // data validation
        $validator = Validator::make($request->all(), [
            "email" => "required|email",

        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // JWTAuth
        $token = JWTAuth::attempt([
            "email" => $request->email,
            "password" => $request->password
        ]);

        if (!empty($token)) {
       $user = Auth::user();
            return response()->json([
                "status" => true,
                "message" => "utilisateur connecter avec succe",
                "token" => $token,
                'user'=>$user
            ]);
        }

        return response()->json([
            "status" => false,
            "message" => "details invalide"
        ]);
    }

  
}
