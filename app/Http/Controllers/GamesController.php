<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Auth\SignInResult\SignInResult;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Contract\Database;
use Session;

class GamesController extends Controller
{    private $database;
     private $tabname;
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
        return view('games.index', compact('games'));
    }
    
    public function welcome()
    {
        $games =  $this->database->getReference($this->tabname)->getValue();  
        return view('welcome', compact('games'));
    }

    public function show($gid)
    {
        $game =  $this->database->getReference($this->tabname)->getChild($gid)->getValue();  
        return view('games.show', compact('game','gid'));
    }

    public function snake($gid)
    {  
        // Pobranie identyfikatora użytkownika sesji
        $sessionId = Session::get('uid');
    
        // Pobranie rekordów z 'achivuser', gdzie pole 'uid' jest równe identyfikatorowi użytkownika sesji
        $achivuserData = [];
        $achivuser = $this->database->getReference('achivuser')->getValue();
        if ($achivuser) {
            foreach ($achivuser as $key => $record) {
                if ($record['uid'] == $sessionId) {
                    $achivuserData[$key] = $record;
                }
            }
        }
    
        // Przekazanie danych do widoku
        return view('games.play.snake', compact('gid', 'achivuserData'));
    }

    public function rps($gid)
    {  
        return view('games.play.rps', compact('gid'));
    }
    public function kubki($gid)
    {  
        return view('games.play.kubki', compact('gid'));
    }

    public function chinczyk($gid)
    {
        return view('games.play.chinczyk', compact('gid'));
    }
    public function chinczyk_solo($gid)
    {
        return view('games.play.chinczyk.chinczyk_solo', compact('gid'));
    }

    public function add(Request $request)
    {  
        $gid = $request->id;
        $uid = Session::get('uid');
        $w = $request->wynik;
        
        $data = [
            'gid' => $gid,
            'uid' => $uid,
            'score' => $w,
            'date' => date('Y-m-d'),
            'time' => date('H:i'),
        ];
    
        $post_ref = $this->database->getReference('history')->push($data);
    
        $game = $this->database->getReference('games/'.$gid)->getValue();
        $his = $this->database->getReference('ranking')->getValue();

        $achivuser = $this->database->getReference('AchivUser')->getValue();
        $achivementAids = [];
        if(!empty($achivuser)){
            foreach ($achivuser as $record) {
                if ($record['uid'] == $uid) {
                    $achivementAids[] = $record['aid'];
                }
        }
        }

        $matchingRecord = null;

        foreach ($his as $key => $record) {
            if ($record['gid'] == $gid && $record['uid'] == $uid) {
                $matchingRecord = $record;
                break;
            }
        }
        
        if($game['name']=="Snake"){
 ////////////////////////////////////////////////////Achivmenty//////////////////////////////////////////////////////////
            $historyRecords = $this->database->getReference('history')
            ->orderByChild('uid')
            ->equalTo($uid)
            ->getValue();
        
        // Tablica, która będzie przechowywać pasujące rekordy
        $matchedRecords = [];
        
        foreach ($historyRecords as $recordKey => $record) {
            if (isset($record['gid']) && $record['gid'] == $gid) {
                // Jeśli pole gid pasuje do $gid, dodaj ten rekord do tablicy $matchedRecords
                $matchedRecords[$recordKey] = $record;
            }
        }
            
        $matchedRecordsCount = count($matchedRecords);
        
        if ($matchedRecordsCount==1) {
                $oo = [
                    'aid' => "-NsylVEP3gco6Rn7Mk4G",
                    'uid' => $uid,
                    'date' => date('Y-m-d'),
                    'time' => date('H:i'),
                ];
                $post_ref = $this->database->getReference('AchivUser')->push($oo);
            }

            if($w>=5){
                $achivementAid = '-NsylbiYVvX866JkIeCQ';
                 if (!in_array($achivementAid, $achivementAids)) {
                    $oo = [
                        'aid' => "-NsylbiYVvX866JkIeCQ",
                        'uid' => $uid,
                        'date' => date('Y-m-d'),
                        'time' => date('H:i'),
                    ];
                    $post_ref = $this->database->getReference('AchivUser')->push($oo);
                 }
            }

            if($w>=10){
                $achivementAid = '-NsylgkAAAvpANLI9GvM';
                if (!in_array($achivementAid, $achivementAids)) {
                    $oo = [
                        'aid' => "-NsylgkAAAvpANLI9GvM",
                        'uid' => $uid,
                        'date' => date('Y-m-d'),
                        'time' => date('H:i'),
                    ];
                    $post_ref = $this->database->getReference('AchivUser')->push($oo);
                }
            }

            if($w>=15){
                $achivementAid = '-NsylsPSeJgyf5vIXunD';
                if (!in_array($achivementAid, $achivementAids)) {
                    $oo = [
                        'aid' => "-NsylsPSeJgyf5vIXunD",
                        'uid' => $uid,
                        'date' => date('Y-m-d'),
                        'time' => date('H:i'),
                    ];
                    $post_ref = $this->database->getReference('AchivUser')->push($oo);
                }
            }

 ////////////////////////////////////////////////////ranking//////////////////////////////////////////////////////////

            $data2 = [
                'gid' => $gid,
                'uid' => $uid,
                'points' =>  $w,
                'date' => date('Y-m-d'),
                'time' => date('H:i'),
            ]; 

            if ($matchingRecord) {
                $points = $matchingRecord['points'];
                if ($points < $w) {
                    $this->database->getReference('ranking/' . $key . '/points')->set($w);
                }
            } else {
                $post_ref = $this->database->getReference('ranking')->push($data2);
            }
      }else if($game['name']=="Connect 4"){
 ////////////////////////////////////////////////////Achivmenty//////////////////////////////////////////////////////////
        $historyRecords = $this->database->getReference('history')
        ->orderByChild('gid')
        ->equalTo($gid)
        ->getValue();
    
    // Tablica, która będzie przechowywać pasujące rekordy
    $matchedRecords = [];
    
    foreach ($historyRecords as $recordKey => $record) {
        if (isset($record['uid']) && $record['uid'] == $uid) {
            // Jeśli pole gid pasuje do $gid, dodaj ten rekord do tablicy $matchedRecords
            $matchedRecords[$recordKey] = $record;
        }
    }

    $matchedRecordsCount = count($matchedRecords);
    
    if ($matchedRecordsCount==1) {
        $oo = [
            'aid' => "-NtWl2f8u4W55PjwxO2I",
            'uid' => $uid,
            'date' => date('Y-m-d'),
            'time' => date('H:i'),
        ];
        $post_ref = $this->database->getReference('AchivUser')->push($oo);
    }
    if($matchedRecordsCount>=5){
        $achivementAid = '-NtWlPihQf_9-N6jASL8';
         if (!in_array($achivementAid, $achivementAids)) {
            $oo = [
                'aid' => "-NtWlPihQf_9-N6jASL8",
                'uid' => $uid,
                'date' => date('Y-m-d'),
                'time' => date('H:i'),
            ];
            $post_ref = $this->database->getReference('AchivUser')->push($oo);
         }
    }

    if($matchedRecordsCount>=10){
        $achivementAid = '-NtWllnD98MINWZWxsOJ';
         if (!in_array($achivementAid, $achivementAids)) {
            $oo = [
                'aid' => "-NtWllnD98MINWZWxsOJ",
                'uid' => $uid,
                'date' => date('Y-m-d'),
                'time' => date('H:i'),
            ];
            $post_ref = $this->database->getReference('AchivUser')->push($oo);
         }
    }

  ////////////////////////////////////////////////////ranking//////////////////////////////////////////////////////////
            $data2 = [
                'gid' => $gid,
                'uid' => $uid,
                'points' =>  100,
                'date' => date('Y-m-d'),
                'time' => date('H:i'),
            ]; 

            if ($matchingRecord) {
                $points = $matchingRecord['points'];
                if ($w =="+10") {
                    $this->database->getReference('ranking/' . $key . '/points')->set( $points+10);
                }else{
                    $this->database->getReference('ranking/' . $key . '/points')->set( $points-10);
                }
            } else {
                $post_ref = $this->database->getReference('ranking')->push($data2);
            }

      }else if($game['name']=="Papier, Kamień, Nożyce"){
 ////////////////////////////////////////////////////Achivmenty//////////////////////////////////////////////////////////
            $historyRecords = $this->database->getReference('history')
            ->orderByChild('gid')
            ->equalTo($gid)
            ->getValue();
        
        // Tablica, która będzie przechowywać pasujące rekordy
        $matchedRecords = [];
        
        foreach ($historyRecords as $recordKey => $record) {
            if (isset($record['uid']) && $record['uid'] == $uid) {
                // Jeśli pole gid pasuje do $gid, dodaj ten rekord do tablicy $matchedRecords
                $matchedRecords[$recordKey] = $record;
            }
        }

        $matchedRecordsCount = count($matchedRecords);
        
        if ($matchedRecordsCount==1) {
            $oo = [
                'aid' => "-NtWjfB-bQwwH4PnPsVA",
                'uid' => $uid,
                'date' => date('Y-m-d'),
                'time' => date('H:i'),
            ];
            $post_ref = $this->database->getReference('AchivUser')->push($oo);
        }

        $rankingRecords = $this->database->getReference('ranking')
        ->orderByChild('gid')
        ->equalTo($gid)
        ->getValue();
    
    // Tablica, która będzie przechowywać pasujące rekordy
    $rekord = null;
    
    foreach ($rankingRecords as $recordKey => $record) {
        if (isset($record['uid']) && $record['uid'] == $uid) {
            // Jeśli pole gid pasuje do $gid, dodaj ten rekord do tablicy $matchedRecords
            $rekord = $record["points"];
            break;
        }
    }

    if($rekord>=100){
        $achivementAid = '-NtWk-TptuyY5SmxoLdr';
         if (!in_array($achivementAid, $achivementAids)) {
            $oo = [
                'aid' => "-NtWk-TptuyY5SmxoLdr",
                'uid' => $uid,
                'date' => date('Y-m-d'),
                'time' => date('H:i'),
            ];
            $post_ref = $this->database->getReference('AchivUser')->push($oo);
         }
    }


    if($rekord>=200){
        $achivementAid = '-NtWkC8aj1r-wSULnYDI';
         if (!in_array($achivementAid, $achivementAids)) {
            $oo = [
                'aid' => "-NtWkC8aj1r-wSULnYDI",
                'uid' => $uid,
                'date' => date('Y-m-d'),
                'time' => date('H:i'),
            ];
            $post_ref = $this->database->getReference('AchivUser')->push($oo);
         }
    }


    if($rekord>=300){
        $achivementAid = '-NtWkOTLCEjNciEE1Lnj';
         if (!in_array($achivementAid, $achivementAids)) {
            $oo = [
                'aid' => "-NtWkOTLCEjNciEE1Lnj",
                'uid' => $uid,
                'date' => date('Y-m-d'),
                'time' => date('H:i'),
            ];
            $post_ref = $this->database->getReference('AchivUser')->push($oo);
         }
    }

////////////////////////////////////////////////////Ranking//////////////////////////////////////////////////////////
        $data2 = [
            'gid' => $gid,
            'uid' => $uid,
            'points' =>  100,
            'date' => date('Y-m-d'),
            'time' => date('H:i'),
        ]; 

        if ($matchingRecord) {
            $points = $matchingRecord['points'];
            if ($w =="+10") {
                $this->database->getReference('ranking/' . $key . '/points')->set( $points+10);
            }else if($w =="-10"){
                $this->database->getReference('ranking/' . $key . '/points')->set( $points-10);
            }
        } else {
            $post_ref = $this->database->getReference('ranking')->push($data2);
        }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      }else if($game['name']=="Chińczyk"){
 ////////////////////////////////////////////////////Achivmenty//////////////////////////////////////////////////////////
            $historyRecords = $this->database->getReference('history')
            ->orderByChild('gid')
            ->equalTo($gid)
            ->getValue();

            // Tablica, która będzie przechowywać pasujące rekordy
            $matchedRecords = [];

            foreach ($historyRecords as $recordKey => $record) {
            if (isset($record['uid']) && $record['uid'] == $uid) {
                // Jeśli pole gid pasuje do $gid, dodaj ten rekord do tablicy $matchedRecords
                $matchedRecords[$recordKey] = $record;
            }
            }

            $matchedRecordsCount = count($matchedRecords);

            if ($matchedRecordsCount==1) {
            $oo = [
                'aid' => "-Ntog4zMGtabD8Li6bj4",
                'uid' => $uid,
                'date' => date('Y-m-d'),
                'time' => date('H:i'),
            ];
            $post_ref = $this->database->getReference('AchivUser')->push($oo);
            }

            $rankingRecords = $this->database->getReference('ranking')
            ->orderByChild('gid')
            ->equalTo($gid)
            ->getValue();

            // Tablica, która będzie przechowywać pasujące rekordy
            $rekord = null;

            foreach ($rankingRecords as $recordKey => $record) {
            if (isset($record['uid']) && $record['uid'] == $uid) {
            // Jeśli pole gid pasuje do $gid, dodaj ten rekord do tablicy $matchedRecords
            $rekord = $record["points"];
            break;
            }
            }

            if($rekord>=100){
            $achivementAid = '-NtogvJcBirUrHaLPAMG';
            if (!in_array($achivementAid, $achivementAids)) {
            $oo = [
                'aid' => "-NtogvJcBirUrHaLPAMG",
                'uid' => $uid,
                'date' => date('Y-m-d'),
                'time' => date('H:i'),
            ];
            $post_ref = $this->database->getReference('AchivUser')->push($oo);
            }
            }


            if($rekord>=200){
            $achivementAid = '-NtohA2vPqc0-1Tj-q6f';
            if (!in_array($achivementAid, $achivementAids)) {
            $oo = [
                'aid' => "-NtohA2vPqc0-1Tj-q6f",
                'uid' => $uid,
                'date' => date('Y-m-d'),
                'time' => date('H:i'),
            ];
            $post_ref = $this->database->getReference('AchivUser')->push($oo);
            }
            }


            if($rekord>=300){
            $achivementAid = '-NtohIBZMxwrx-jp9AE8';
            if (!in_array($achivementAid, $achivementAids)) {
            $oo = [
                'aid' => "-NtohIBZMxwrx-jp9AE8",
                'uid' => $uid,
                'date' => date('Y-m-d'),
                'time' => date('H:i'),
            ];
            $post_ref = $this->database->getReference('AchivUser')->push($oo);
            }
            }

            ////////////////////////////////////////////////////Ranking//////////////////////////////////////////////////////////
            $data2 = [
            'gid' => $gid,
            'uid' => $uid,
            'points' =>  100,
            'date' => date('Y-m-d'),
            'time' => date('H:i'),
            ]; 

            if ($matchingRecord) {
            $points = $matchingRecord['points'];
            if ($w =="+10") {
                $this->database->getReference('ranking/' . $key . '/points')->set( $points+10);
            }else if($w =="-10"){
                $this->database->getReference('ranking/' . $key . '/points')->set( $points-10);
            }
            } else {
            $post_ref = $this->database->getReference('ranking')->push($data2);
            }




      }else if($game['name']=="Trzy kubki"){
 ////////////////////////////////////////////////////Achivmenty//////////////////////////////////////////////////////////
        $historyRecords = $this->database->getReference('history')
        ->orderByChild('gid')
        ->equalTo($gid)
        ->getValue();
    
    // Tablica, która będzie przechowywać pasujące rekordy
    $matchedRecords = [];
    
    foreach ($historyRecords as $recordKey => $record) {
        if (isset($record['uid']) && $record['uid'] == $uid) {
            // Jeśli pole gid pasuje do $gid, dodaj ten rekord do tablicy $matchedRecords
            $matchedRecords[$recordKey] = $record;
        }
    }

    $matchedRecordsCount = count($matchedRecords);
    
    if ($matchedRecordsCount==1) {
        $oo = [
            'aid' => "-NtXeYQnyj19fiV_uLUH",
            'uid' => $uid,
            'date' => date('Y-m-d'),
            'time' => date('H:i'),
        ];
        $post_ref = $this->database->getReference('AchivUser')->push($oo);
    }
    if($matchedRecordsCount>=3){
        $achivementAid = '-NtXeh4WwwRu1ALlcrKN';
         if (!in_array($achivementAid, $achivementAids)) {
            $oo = [
                'aid' => "-NtXeh4WwwRu1ALlcrKN",
                'uid' => $uid,
                'date' => date('Y-m-d'),
                'time' => date('H:i'),
            ];
            $post_ref = $this->database->getReference('AchivUser')->push($oo);
         }
    }

    if($matchedRecordsCount>=5){
        $achivementAid = '-NtXf3tBPtN7p520e8zs';
         if (!in_array($achivementAid, $achivementAids)) {
            $oo = [
                'aid' => "-NtXf3tBPtN7p520e8zs",
                'uid' => $uid,
                'date' => date('Y-m-d'),
                'time' => date('H:i'),
            ];
            $post_ref = $this->database->getReference('AchivUser')->push($oo);
         }
    }

 ////////////////////////////////////////////////////ranking//////////////////////////////////////////////////////////
        $data2 = [
            'gid' => $gid,
            'uid' => $uid,
            'points' =>  100,
            'date' => date('Y-m-d'),
            'time' => date('H:i'),
        ]; 

        if ($matchingRecord) {
            $points = $matchingRecord['points'];
            if ($w =="+10") {
                $this->database->getReference('ranking/' . $key . '/points')->set( $points+10);
            }else{
                $this->database->getReference('ranking/' . $key . '/points')->set( $points-10);
            }
        } else {
            $post_ref = $this->database->getReference('ranking')->push($data2);
        }



      }   
        return response()->json(['message' => 'Rekord został dodany pomyślnie', 'key' => $post_ref->getKey()]);
    }


    public function gameCon4()
    {  
        

    }

    public function con4($gid)
    {  
        $rooms = $this->database->getReference('rooms')->getValue();
    
        // Filter rooms with less than 2 players
        $availableRooms = [];
        if ($rooms) {
        foreach ($rooms as $roomId => $room) {
            // Check if the room has players and roomNumber keys
            if (!isset($room['players']['2']) && isset($room['roomNumber'])) {
           
                    $availableRooms[$roomId] = $room['roomNumber'];
                
            }
        }
    }
    
        return view('games.play.con4', compact('gid', 'availableRooms')); 
    }

    public function createRoomCon4(Request $request)
    {
        $roomsCount = count($this->database->getReference('rooms')->getValue());
        $roomNumber = $roomsCount;
        $currentPlayerUid = Session::get('uid');
        $currentPlayerNumber = 1;
    
        // Check if the player already has a room
        $existingRoom = $this->getRoomByPlayerUid($currentPlayerUid);
        if ($existingRoom !== null) {
            // Player already has a room, return an error response
            return response()->json(['error' => 'Player already has a room'], 400);
        }
    
        // Create the new room
        $roomData = [
            'roomNumber' => $roomNumber,
            'players' => [
                $currentPlayerNumber => $currentPlayerUid,
                '2' => null,
            ],
            'moves' => [], // Initialize moves array
        ];
    
        // Store the room data in the database and get the key
        $newRoomRef = $this->database->getReference('rooms')->push($roomData);
        $newRoomKey = $newRoomRef->getKey();
    
        // Return the key of the newly created room
        return response()->json(['message' => 'Room created successfully', 'roomId' => $newRoomKey]);
    }
    
    public function joinRoomCon4(Request $request)
    {
        $roomId = $request->input('roomId');
        $currentPlayerUid = $request->input('currentPlayerUid');


            // Check if the player is already in the room
            $room = $this->database->getReference('rooms')->getChild($roomId)->getValue();
            if (isset($room['players']) && in_array($currentPlayerUid, $room['players'])) {
                // Player is already in the room, return an error response
                return response()->json(['error' => 'Player is already in the room'], 400);
            }
        // Retrieve the room data
        $room = $this->database->getReference('rooms')->getChild($roomId)->getValue();
    
        // Check if player '2' already exists in the room
        if (!isset($room['players']['2'])) {
            // Add player '2' to the room
            $this->database
                ->getReference('rooms')
                ->getChild($roomId)
                ->getChild('players')
                ->getChild('2')
                ->set($currentPlayerUid);
    
            // Return a success response
            return response()->json(['message' => 'Player 2 joined the room']);
        } else {
            // Player '2' already exists, return an error response
            return response()->json(['error' => 'Player 2 slot is already occupied'], 400);
        }
    }

    private function getRoomByPlayerUid($uid)
    {
        $rooms = $this->database->getReference('rooms')->getValue();

        foreach ($rooms as $roomId => $room) {
            if (isset($room['players']) && in_array($uid, $room['players'])) {
                return $roomId;
            }
        }

        return null;
    }

    public function addMoveCon4(Request $request)
    {
        $roomId = $request->input('roomId');
        $currentPlayerUid = $request->input('currentPlayerUid');
        $index = $request->input('Index'); // Pobranie tablicy indeksów zaznaczonych pól
    
        // Dodanie ruchu do pokoju na serwerze
        $this->database
            ->getReference('rooms')
            ->getChild($roomId)
            ->getChild('moves')
            ->push(['uid' => $currentPlayerUid, 'Index' => $index]);
        
        // Pobranie wszystkich ruchów z danego pokoju
        $movesSnapshot = $this->database
            ->getReference('rooms')
            ->getChild($roomId)
            ->getChild('moves')
            ->orderByKey() 
            ->getSnapshot();
        
        $moves = [];
        foreach ($movesSnapshot->getValue() as $move) {
            $moves[] = $move;
        }
    
        // Zwrot odpowiedzi do klienta
        return response()->json(['message' => 'Ruch dodany pomyślnie', 'moves' => $moves]);
    }

    public function getMoveCon4(Request $request)
    {
        $roomId = $request->input('roomId');

        $roomRef = $this->database->getReference('rooms')->getChild($roomId);
        $roomSnapshot = $roomRef->getSnapshot();

        if (!$roomSnapshot->exists()) {
            $moves = null;
            return response()->json(['message' => 'Ruch dodany pomyślnie', 'moves' => $moves]);
        }

        // Pobranie wszystkich ruchów z danego pokoju
        $movesSnapshot = $this->database
            ->getReference('rooms')
            ->getChild($roomId)
            ->getChild('moves')
            ->orderByKey() 
            ->getSnapshot();
        
        // Sprawdzenie, czy są jakiekolwiek ruchy w pokoju
        if ($movesSnapshot->exists()) {
            $moves = [];
            foreach ($movesSnapshot->getValue() as $move) {
                $moves[] = $move;
            }
        } else {
            $moves = 'brak'; // Brak ruchów w pokoju
        }
    
        // Zwrot odpowiedzi do klienta
        return response()->json(['message' => 'Ruch dodany pomyślnie', 'moves' => $moves]);
    }
    
    public function delroomCon4(Request $request){
        $roomId = $request->input('roomId');
    
        // Sprawdź, czy pokój istnieje
        $roomRef = $this->database->getReference('rooms')->getChild($roomId);
        $roomSnapshot = $roomRef->getSnapshot();
    
        if ($roomSnapshot->exists()) {
            // Pokój istnieje, usuń go
            $roomRef->remove();
    
            return response()->json(['message' => 'Pokój usunięty pomyślnie']);
        } else {
            // Pokój nie istnieje, zwróć odpowiednią wiadomość
            return response()->json(['message' => 'Pokój nie istnieje lub został już usunięty']);
        }
    }
    public function getrooms() {
        $roomsSnapshot = $this->database->getReference('rooms')->getSnapshot();
        $rooms = $roomsSnapshot->getValue();
        $roomsArray = [];
        
        foreach ($rooms as $roomId => $room) {
            // Pomijamy pokój o kluczu równym "pokoje"
            if (!isset($room['players']['2']) && isset($room['roomNumber'])) {
                // Dodajemy klucz pokoju do tablicy jako część wartości
                $room['roomId'] = $roomId;
                $roomsArray[] = $room;
            }
        }
        
        return response()->json(['rooms' => $roomsArray]);
    }

}