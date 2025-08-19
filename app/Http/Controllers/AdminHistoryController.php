<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Auth\SignInResult\SignInResult;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Contract\Database;
use Session;


class AdminHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(Database $database){
        $this->database = $database;
        $this->tabname = $tabname="history";
    }

    public function index()
    {   
$auth = app('firebase.auth');
    $games = $this->database->getReference('games')->getValue();
    $his = $this->database->getReference($this->tabname)->getValue();  
    $users = $auth->listUsers($defaultMaxResults = 1000, $defaultBatchSize = 1000);

    $perPage = 8;
    $page = request()->get('page', 1);
    $offset = ($page - 1) * $perPage;
    $hisPaginated = array_slice($his, $offset, $perPage);
    $totalRecords = count($his);
    $totalPages = ceil($totalRecords / $perPage);

    return view('admin.history', compact('hisPaginated', 'games', 'users','page','totalPages'));
    }


    public function storeH(Request $request)
    {
      $data = [
        'gid' => $request->game,
        'uid' => $request->user,
        'score' => $request->score,
        'date' =>date('Y-m-d'),
        'time' =>date('H:i'),
      ];
      $post_ref= $this->database->getReference($this->tabname)->push($data);
      return back()->withInput();
      }


      public function deleteH($gid)
      {
        $key = $gid;
        $deldata =  $this->database->getReference($this->tabname.'/'.$key)->remove(); 

        if($deldata){
            return redirect('admin/history')->with('status','usunięto pomyślnie');
        }
      }

      public function editH($gid)
      {
        $auth = app('firebase.auth');
        $games =  $this->database->getReference('games')->getValue();
        $users = $auth->listUsers($defaultMaxResults = 1000, $defaultBatchSize = 1000);
        $key = $gid;
        $editdata =  $this->database->getReference($this->tabname)->getChild($key)->getValue(); 

        if($editdata){
            return view('admin.editHistory',compact('editdata','key','users','games'));
        }

      }

    public function updateH($gid, Request $request){

      $updates = [
        'gid' => $request->game,
        'uid' => $request->user,
        'score' => $request->score,
        'date' =>date('Y-m-d'),
        'time' =>date('H:i'),
      ];


        $update =  $this->database->getReference($this->tabname.'/'.$gid)->update($updates); 
        if($update){
            return redirect('admin/history')->with('status','Zaktualizowano pomyślnie');
        }
    }
}
