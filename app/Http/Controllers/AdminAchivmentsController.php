<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Auth\SignInResult\SignInResult;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Contract\Database;
use Session;


class AdminAchivmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(Database $database){
        $this->database = $database;
        $this->tabname = $tabname="achivments";
    }

    public function index()
    {
        $games =  $this->database->getReference('games')->getValue(); 
        $achivments =  $this->database->getReference($this->tabname)->getValue();  
        $perPage = 8;
        $page = request()->get('page', 1);
        $offset = ($page - 1) * $perPage;
       
        
        if($achivments!= NULL){
          $hisPaginated = array_slice($achivments, $offset, $perPage);
          $totalRecords = count($achivments);
          }else{
            $hisPaginated=  $achivments;
            $totalRecords=0;
          }
          $totalPages = ceil($totalRecords / $perPage);

        return view('admin.achivments', compact('hisPaginated','achivments','games','page','totalPages'));
    }

    public function storeA(Request $request)
    {
        $request->validate([
            'image' => 'required',
          ]);
          $input = $request->all();
          $image = $request->file('image'); //image file from frontend
  
          $firebase_storage_path = 'achivments/';
          $localfolder = public_path('firebase-temp-uploads') .'/';
          $file      =  ($request->name).'.'. 'png';
  
          $link = 'https://firebasestorage.googleapis.com/v0/b/centrumgier-a08cf.appspot.com/o/achivments%2F'.$request->name.'.png?alt=media' ;

  
          if ($image->move($localfolder, $file)) {
            $uploadedfile = fopen($localfolder.$file, 'r');
            app('firebase.storage')->getBucket()->upload($uploadedfile, ['name' => $firebase_storage_path . $file]);
            //will remove from local laravel folder
            unlink($localfolder . $file);
            Session::flash('message', 'Pomyślnie dodano');
          }


          $data = [
            'name' => $request->name,
            'gid' => $request->game,
            'image' => $link,
            'desc' =>  $request->desc,
          ];
          $post_ref= $this->database->getReference($this->tabname)->push($data);

          return back()->withInput();
      }

      public function deleteA($gid)
      {
        $data =  $this->database->getReference($this->tabname)->getChild($gid)->getValue(); 
        $imageDeleted = app('firebase.storage')->getBucket()->object("achivments/".$data['name'].".png")->delete();
        $key = $gid;
        $deldata =  $this->database->getReference($this->tabname.'/'.$key)->remove(); 

        if($deldata){
            return redirect('admin/achivments')->with('status','usunięto pomyślnie');
        }
      }

      public function editA($gid)
      {
        $key = $gid;
        $games =  $this->database->getReference('games')->getValue();
        $editdata =  $this->database->getReference($this->tabname)->getChild($key)->getValue(); 

        if($editdata){
            return view('admin.editAchivments',compact('editdata','key','games'));
        }

      }

    public function updateA($gid, Request $request){
        $image = $request->file('image');

        if($image){
            $firebase_storage_path = 'achivments/';
            $localfolder = public_path('firebase-temp-uploads') .'/';
            $file      =  ($request->name).'.'. 'png';
    
            $link = 'https://firebasestorage.googleapis.com/v0/b/centrumgier-a08cf.appspot.com/o/achivments%2F'.$request->name.'.png?alt=media' ;
    
            if ($image->move($localfolder, $file)) {
              $uploadedfile = fopen($localfolder.$file, 'r');
              app('firebase.storage')->getBucket()->upload($uploadedfile, ['name' => $firebase_storage_path . $file]);
              //will remove from local laravel folder
              unlink($localfolder . $file);
            }

            $updates = [
                'name' => $request->name,
                'gid' => $request->game,
                'image' => $link,
                'desc' =>  $request->desc,
              ];

        }else{

            $updates = [
                'name' => $request->name,
                'gid' => $request->game,
                'desc' =>  $request->desc,
              ];
        }

        $update =  $this->database->getReference($this->tabname.'/'.$gid)->update($updates); 
        if($update){
            return redirect('admin/achivments')->with('status','Zaktualizowano pomyślnie');
        }
    }
}
