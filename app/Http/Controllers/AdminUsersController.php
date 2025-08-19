<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Auth\SignInResult\SignInResult;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Contract\Database;
use Session;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(Database $database){
        $this->database = $database;
    
    }

public function index()
{   
    $auth = app('firebase.auth');
    $users = $auth->listUsers($defaultMaxResults = 1000, $defaultBatchSize = 1000);

    $usersArray = [];
    foreach ($users as $user) {
        $usersArray[] = $user;
    }

    $perPage = 8;
    $page = request()->get('page', 1);
    $offset = ($page - 1) * $perPage;

    $usersPaginated = array_slice($usersArray, $offset, $perPage);

    $totalPages = ceil(count($usersArray) / $perPage);

    return view('admin.users', compact('usersPaginated', 'page', 'totalPages'));
}

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);
    }

    public function storeU(Request $request)
    {
      try {
      $auth = app('firebase.auth');

      $this->validator($request->all())->validate();

      $userProperties = [
        'email' => $request->email,
        'emailVerified' => true,
        'password' => $request->pass,
        'displayName' => $request->name,
        'disabled' => false,
    ];
      $createdUser = $auth->createUser($userProperties);

    } catch (FirebaseException $e) {
      Session::flash('error', $e->getMessage());
      return back()->withInput();
    }
    
      $id= $createdUser->uid;

      $request->validate([
        'image' => 'required',
      ]);
      $input = $request->all();
      $image = $request->file('image'); //image file from frontend

      $firebase_storage_path = 'pfp/';
      $localfolder = public_path('firebase-temp-uploads') .'/';
      $file      =  ($id).'.'. 'png';

      $link = 'https://firebasestorage.googleapis.com/v0/b/centrumgier-a08cf.appspot.com/o/pfp%2F'.$id.'.png?alt=media' ;


      if ($image->move($localfolder, $file)) {
        $uploadedfile = fopen($localfolder.$file, 'r');
        app('firebase.storage')->getBucket()->upload($uploadedfile, ['name' => $firebase_storage_path . $file]);
        //will remove from local laravel folder
        unlink($localfolder . $file);
        Session::flash('message', 'Pomyślnie dodano');
      }

      $properties = [
          'photoUrl' => $link,
      ];
      $updatedUser = $auth->updateUser($id, $properties);
      return back()->withInput();
      }


      public function deleteU($gid)
      {
          $auth = app('firebase.auth');
      
          // Pobranie referencji do baz danych Firebase
          $database = $this->database;
      
          // Pobranie danych z tabel
          $historyRef = $database->getReference('history');
          $rankingRef = $database->getReference('ranking');
          $achivhisRef = $database->getReference('AchivUser');
      
          // Pobranie danych z tabel
          $history = $historyRef->getValue();
          $ranking = $rankingRef->getValue();
          $achivhis = $achivhisRef->getValue();
      
          // Usunięcie użytkownika z Firebase Authentication

          $user = app('firebase.auth')->getUser($gid);
          if($user->photoUrl != NULL){
          // Usunięcie obrazka z Firebase Storage
          $imageDeleted = app('firebase.storage')->getBucket()->object("pfp/".$gid.".png")->delete();
          }
          

          $result = $auth->deleteUser($gid, true);
      
          // Sprawdzenie, czy tabele nie są puste
          if (!empty($history)) {
              // Przejście przez historię i usunięcie rekordów, gdzie uid == gid
              foreach ($history as $key => $record) {
                  if ($record['uid'] == $gid) {
                      $historyRef->getChild($key)->remove();
                  }
              }
          }
      
          if (!empty($ranking)) {
              // Przejście przez ranking i usunięcie rekordów, gdzie uid == gid
              foreach ($ranking as $key => $record) {
                  if ($record['uid'] == $gid) {
                      $rankingRef->getChild($key)->remove();
                  }
              }
          }
      
          if (!empty($achivhis)) {
              // Przejście przez achivhis i usunięcie rekordów, gdzie uid == gid
              foreach ($achivhis as $key => $record) {
                  if ($record['uid'] == $gid) {
                      $achivhisRef->getChild($key)->remove();
                  }
              }
          }
      
          // Przekierowanie użytkownika
          return redirect('admin/users')->with('status','Usunięto pomyślnie');
      }

      public function editU($gid)
      {
        $user = app('firebase.auth')->getUser($gid);

        if($user){
            return view('admin.editUsers',compact('user'));
        }

      }

    public function updateU($gid, Request $request){
      $this->validator($request->all())->validate();
      $auth = app('firebase.auth');
      $image = $request->file('image');
      $properties = [
        'email' => $request->email,
        'displayName' => $request->name,
        ];
        $updatedUser = $auth->updateUser($gid, $properties);

      if($image){
        $firebase_storage_path = 'pfp/';
        $localfolder = public_path('firebase-temp-uploads') .'/';
        $file      =  ($gid).'.'. 'png';
  
        $link = 'https://firebasestorage.googleapis.com/v0/b/centrumgier-a08cf.appspot.com/o/pfp%2F'.$gid.'.png?alt=media' ;
  
  
        if ($image->move($localfolder, $file)) {
          $uploadedfile = fopen($localfolder.$file, 'r');
          app('firebase.storage')->getBucket()->upload($uploadedfile, ['name' => $firebase_storage_path . $file]);
          //will remove from local laravel folder
          unlink($localfolder . $file);
          Session::flash('message', 'Pomyślnie dodano');
        }
  
    $properties = [
        'photoUrl' => $link,
      ];
    $updatedUser = $auth->updateUser($gid, $properties);
    }

   if($request->pass!=NULL){
      $properties = [
        'password' => $request->pass,
      ];
      $updatedUser = $auth->updateUser($gid, $properties);
   }

   return redirect('admin/users')->withInput();
    }
}
