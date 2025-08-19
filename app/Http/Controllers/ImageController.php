<?php

namespace App\Http\Controllers;
use Kreait\Firebase\Contract\Auth;
use Kreait\Auth\Request\UpdateUser;
use Illuminate\Http\Request;
use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Auth\SignInResult\SignInResult;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Contract\Database;
use Session;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $auth = app('firebase.auth');
        //
        $request->validate([
          'image' => 'required',
        ]);
        $input = $request->all();
        $image = $request->file('image'); //image file from frontend

        $firebase_storage_path = 'pfp/';
        $localfolder = public_path('firebase-temp-uploads') .'/';
        $extension = $image->getClientOriginalExtension();
        $file      =  Session::get('uid').'.'. 'png';

        $link = 'https://firebasestorage.googleapis.com/v0/b/centrumgier-a08cf.appspot.com/o/pfp%2F'.Session::get('uid').'.png?alt=media' ;

    

        if ($image->move($localfolder, $file)) {
          $uploadedfile = fopen($localfolder.$file, 'r');
          app('firebase.storage')->getBucket()->upload($uploadedfile, ['name' => $firebase_storage_path . $file]);
          //will remove from local laravel folder
          unlink($localfolder . $file);
          Session::flash('message', 'Succesfully Uploaded');
        }

        $uid = Session::get('uid');
        $properties = [
            'photoUrl' => $link,
        ];

        $updatedUser = $auth->updateUser($uid, $properties);

        return back();
    }

}
