<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Contract\Auth;
use Kreait\Auth\Request\UpdateUser;
use Kreait\Firebase\Exception\FirebaseException;
use App\Http\Controllers\Controller;
use Kreait\Firebase\Contract\Database;
use Session;

class Profcontroller extends Controller
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

  public function compare_history_items($a, $b) {
    $result = strtotime($b['date']) - strtotime($a['date']);
    if ($result == 0) {
        $result = strtotime($b['time']) - strtotime($a['time']);
    }
    return $result;
}

  public function index($uid)
  {
    $games =  $this->database->getReference('games')->getValue();

    $his =  $this->database->getReference('history')
    ->orderByChild('uid')
    ->equalTo($uid)
    ->getValue();

    usort($his, [$this, 'compare_history_items']);
    $his = array_slice($his, 0, 5);

    $achivments = $this->database->getReference('achivments')->getValue();
    $historyQuery = $this->database->getReference('AchivUser')
        ->orderByChild('uid')
        ->equalTo($uid);
    $userachiv = $historyQuery->getValue();

    usort($userachiv, [$this, 'compare_history_items']);
    $userachiv = array_slice($userachiv, 0, 3);
    

    $user = app('firebase.auth')->getUser($uid);
    return view('auth.prof',compact('user','his','games','userachiv','achivments'));
  }

}
