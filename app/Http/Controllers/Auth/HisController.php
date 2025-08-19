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

class Hiscontroller extends Controller
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
    $games = $this->database->getReference('games')->getValue();

    $historyQuery = $this->database->getReference('history')
        ->orderByChild('uid')
        ->equalTo($uid2);

    $perPage = 5; 
    $page = request()->get('page', 1); 
    $offset = ($page - 1) * $perPage;
    $history = $historyQuery->getValue();
    usort($history, [$this, 'compare_history_items']);
    $totalRecords = count($history);
    $historyPaginated = array_slice($history, $offset, $perPage);
    $user = app('firebase.auth')->getUser($uid2);
    $totalPages = ceil($totalRecords / $perPage);

    return view('auth.pHistory', compact('user', 'historyPaginated', 'games', 'totalPages', 'page'));
}
}
