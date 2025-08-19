<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Auth\Request\UpdateUser;
use Kreait\Firebase\Exception\FirebaseException;
use App\Http\Controllers\Controller;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Auth\SignInResult\SignInResult;
use Session;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class PlayersController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function __construct(Database $database)
  {
    $this->middleware('auth');
    $this->database = $database;
   
  }

  public function index()
  {
    $auth = app('firebase.auth');
    $players =  $auth->listUsers($defaultMaxResults = 1000, $defaultBatchSize = 1000);

    return view('players',compact('players'));
  }

  public function search(Request $request)
  {
      $query = $request->input('query');
      $auth = app('firebase.auth');
      $allPlayers = $auth->listUsers($defaultMaxResults = 1000, $defaultBatchSize = 1000);
      
      // Filter players based on the search query
      $players = [];
      foreach ($allPlayers as $player) {
          if (strpos(strtolower($player->displayName), strtolower($query)) !== false) {
            $players[] = $player;
          }
      }
  
      return view('players', compact('players'));
  }
}
