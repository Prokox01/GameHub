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

class Achivcontroller extends Controller
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

public function index($uid2)
{
  $perPage = 6; 
  $page = request()->get('page', 1); 
  $offset = ($page - 1) * $perPage;
    $achivments = $this->database->getReference('achivments')->getValue();
    $historyQuery = $this->database->getReference('AchivUser')
        ->orderByChild('uid')
        ->equalTo($uid2);

    $userachiv = $historyQuery->getValue();
    $user = app('firebase.auth')->getUser($uid2);

    $totalRecords = count( $userachiv);
    $userachivPaginated = array_slice( $userachiv, $offset, $perPage);
    $user = app('firebase.auth')->getUser($uid2);
    $totalPages = ceil($totalRecords / $perPage);

    return view('auth.pAchivments', compact('user', 'userachivPaginated', 'achivments' , 'totalPages', 'page'));
}
}
