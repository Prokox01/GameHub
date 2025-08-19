<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Auth\SignInResult\SignInResult;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Contract\Database;
use Session;


class AdminAchivUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(Database $database){
        $this->database = $database;
        $this->tabname = $tabname="AchivUser";
    }

    public function index()
    {   

        $auth = app('firebase.auth');
        $achivments =  $this->database->getReference('achivments')->getValue();
        $achivusers=  $this->database->getReference($this->tabname)->getValue();  
        $users = $auth->listUsers($defaultMaxResults = 1000, $defaultBatchSize = 1000);

        $perPage = 8;
        $page = request()->get('page', 1);
        $offset = ($page - 1) * $perPage;
        if($achivusers != NULL){
        $hisPaginated = array_slice($achivusers, $offset, $perPage);
        $totalRecords = count($achivusers);
        }else{
          $hisPaginated=  $achivusers;
          $totalRecords=0;
        }
       
        $totalPages = ceil($totalRecords / $perPage);
  
        return view('admin.achivuser', compact('hisPaginated', 'achivments', 'users','page','totalPages'));
    }


    public function storeAV(Request $request)
    {
      $data = [
        'uid' => $request->user,
        'aid' => $request->achivment,
        'date' =>date('Y-m-d'),
        'time' =>date('H:i'),
      ];
      $post_ref= $this->database->getReference($this->tabname)->push($data);
      return back()->withInput();
      }


      public function deleteAV($gid)
      {
        $key = $gid;
        $deldata =  $this->database->getReference($this->tabname.'/'.$key)->remove(); 

        if($deldata){
            return redirect('admin/achivuser')->with('status','usunięto pomyślnie');
        }
      }

      public function editAV($gid)
      {
        $auth = app('firebase.auth');
        $achivments =  $this->database->getReference('achivments')->getValue();
        $users = $auth->listUsers($defaultMaxResults = 1000, $defaultBatchSize = 1000);
        $key = $gid;
        $editdata =  $this->database->getReference($this->tabname)->getChild($key)->getValue(); 

        if($editdata){
            return view('admin.editAchivuser',compact('editdata','key','users','achivments'));
        }

      }

    public function updateAV($gid, Request $request){

      $updates = [
        'aid' => $request->achivment,
        'uid' => $request->user,
        'date' =>date('Y-m-d'),
        'time' =>date('H:i'),
      ];

        $update =  $this->database->getReference($this->tabname.'/'.$gid)->update($updates); 
        if($update){
            return redirect('admin/achivuser')->with('status','Zaktualizowano pomyślnie');
        }
    }
}
