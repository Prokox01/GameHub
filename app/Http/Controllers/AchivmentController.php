<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Auth\SignInResult\SignInResult;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Contract\Database;
use Session;

class AchivmentController extends Controller
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
        $achivments =  $this->database->getReference($this->tabname)->getValue();  
        return view('achivments.index', compact('achivments'));
    }

}
