<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Auth\SignInResult\SignInResult;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Contract\Database;
use Session;


class AdminGamesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(Database $database){
        $this->database = $database;
        $this->tabname = $tabname="games";
    }

    public function index()
    {
        $games =  $this->database->getReference($this->tabname)->getValue();  
        return view('admin.games', compact('games'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required',
          ]);
          $input = $request->all();
          $image = $request->file('image'); //image file from frontend
  
          $firebase_storage_path = 'games/';
          $localfolder = public_path('firebase-temp-uploads') .'/';
          $file      =  ($request->name).'.'. 'png';
  
          $link = 'https://firebasestorage.googleapis.com/v0/b/centrumgier-a08cf.appspot.com/o/games%2F'.$request->name.'.png?alt=media' ;

  
          if ($image->move($localfolder, $file)) {
            $uploadedfile = fopen($localfolder.$file, 'r');
            app('firebase.storage')->getBucket()->upload($uploadedfile, ['name' => $firebase_storage_path . $file]);
            //will remove from local laravel folder
            unlink($localfolder . $file);
            Session::flash('message', 'Pomyślnie dodano');
          }

          if($request->link)
          {
            $l=$request->link;
          }else{
            $l="brak!";
          }

          $data = [
            'name' => $request->name,
            'image' => $link,
            'desc' =>  $request->desc,
            'link' =>  $l,
          ];
          $post_ref= $this->database->getReference($this->tabname)->push($data);

          return back()->withInput();
      }

      public function delete($gid)
      {
          $data = $this->database->getReference($this->tabname)->getChild($gid)->getValue(); 
          $imageDeleted = app('firebase.storage')->getBucket()->object("games/".$data['name'].".png")->delete();
          $key = $gid;
          $deldata = $this->database->getReference($this->tabname.'/'.$key)->remove(); 
      
          // Pobranie referencji do baz danych Firebase
          $database = $this->database;
      
          // Pobranie danych z tabel referencyjnych
          $historyRef = $database->getReference('history');
          $rankingRef = $database->getReference('ranking');
          $achivmentRef = $database->getReference('achivments');
      
          // Pobranie danych z tabel referencyjnych
          $history = $historyRef->getValue();
          $ranking = $rankingRef->getValue();
          $achivments = $achivmentRef->getValue();
      
          // Usunięcie odpowiednich rekordów z tabel referencyjnych
          if (!empty($history)) {
              foreach ($history as $key => $record) {
                  if ($record['gid'] == $gid) {
                      $historyRef->getChild($key)->remove();
                  }
              }
          }
      
          if (!empty($ranking)) {
              foreach ($ranking as $key => $record) {
                  if ($record['gid'] == $gid) {
                      $rankingRef->getChild($key)->remove();
                  }
              }
          }
      
          if (!empty($achivments)) {
              foreach ($achivments as $key => $record) {
                  if ($record['gid'] == $gid) {
                      $achivmentRef->getChild($key)->remove();
                  }
              }
          }
      
          if($deldata){
              return redirect('admin/games')->with('status','Usunięto pomyślnie');
          }
      }

      public function edit($gid)
      {
        $key = $gid;
        $editdata =  $this->database->getReference($this->tabname)->getChild($key)->getValue(); 

        if($editdata){
            return view('admin.editGames',compact('editdata','key'));
        }

      }

    public function update($gid, Request $request){
        $image = $request->file('image');

        if($image){
            $firebase_storage_path = 'games/';
            $localfolder = public_path('firebase-temp-uploads') .'/';
            $file      =  ($request->name).'.'. 'png';
    
            $link = 'https://firebasestorage.googleapis.com/v0/b/centrumgier-a08cf.appspot.com/o/games%2F'.$request->name.'.png?alt=media' ;
    
            if ($image->move($localfolder, $file)) {
              $uploadedfile = fopen($localfolder.$file, 'r');
              app('firebase.storage')->getBucket()->upload($uploadedfile, ['name' => $firebase_storage_path . $file]);
              //will remove from local laravel folder
              unlink($localfolder . $file);
            }

            
            if($request->link)
            {
              $l=$request->link;
            }else{
              $l="brak!";
            }
          
            $updates = [
                'name' => $request->name,
                'image' => $link,
                'desc' =>  $request->desc,
                'link' =>  $l,
              ];

        }else{
          if($request->link)
          {
            $l=$request->link;
          }else{
            $l="brak!";
          }
            $updates = [
                'name' => $request->name,
                'desc' =>  $request->desc,
                'link' =>  $l,
              ];
        }

        $update =  $this->database->getReference($this->tabname.'/'.$gid)->update($updates); 
        if($update){
            return redirect('admin/games')->with('status','Zaktualizowano pomyślnie');
        }
    }
}
