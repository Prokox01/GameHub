<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Auth\SignInResult\SignInResult;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Contract\Database;
use Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class MultiGameController extends Controller
{
    private $database;
    private $tabname;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->tabname = "rooms_Chinese"; 
    }
    public function chinczyk_multi($gid, Request $request)
    {
        $roomId = $request->input('roomId');
        $roomData = $this->database->getReference($this->tabname)->getChild($roomId)->getValue();
        
        if (!$roomData) {
            return response()->json(['error' => 'Room not found'], 404);
        }
    
        $ActivePlayer = $roomData['ActivePlayer'];

        $players = $roomData['players'];
        array_shift($players);
        $playerCount = count(array_filter($players));

        return view('games.play.chinczyk.chinczyk_multi', compact('gid','playerCount','players','ActivePlayer'));
    }

    public function updateActivePlayer(Request $request)
    {
        $roomId = $request->input('roomId');
        $APlayer = $request->input('ActivePlayer');

        $roomRef = $this->database->getReference($this->tabname)->getChild($roomId);
        $roomData = $roomRef->getValue();

        if (!$roomData) {
            return response()->json(['error' => 'Room not found'], 404);
        }

        $roomRef->update([
            'ActivePlayer' => $APlayer
        ]);

        return response()->json(['message' => 'ActivePlayer updated successfully']);
    }

    public function getActivePlayer(Request $request)
    {
        $roomId = $request->input('roomId');

        $roomData = $this->database->getReference($this->tabname)->getChild($roomId)->getValue();
        
        if (!$roomData) {
            return response()->json(['error' => 'Room not found'], 404);
        }

        $activePlayer = $roomData['ActivePlayer'];
        return response()->json(['ActivePlayer' => $activePlayer]);
    }

    public function updateGameState(Request $request)
    {
        $roomId = $request->input('roomId');
        $gameState = $request->input('gameState');

        $roomRef = $this->database->getReference($this->tabname)->getChild($roomId);
        $roomData = $roomRef->getValue();

        if (!$roomData) {
            return response()->json(['error' => 'Room not found'], 404);
        }

        $roomRef->update([
            'state' => $gameState
        ]);

        return response()->json(['message' => 'Position updated successfully']);
    }

    public function getGameState(Request $request)
    {
        $roomId = $request->input('roomId');
        $roomData = $this->database->getReference($this->tabname)->getChild($roomId)->getValue();
        

    
        if (!$roomData) {
            return response()->json(['error' => 'Room not found'], 404);
        }
    
        if (!array_key_exists('state', $roomData)) {
            return response()->json(['error' => 'Game state not found'], 404);
        }
    
        $gameState = $roomData['state'];
        return response()->json(['state' => $gameState]);
    }

    public function sendDiceRoll(Request $request){
        $roomId = $request->input('roomId');
        $diceValue = $request->input('diceValue');

        $roomRef = $this->database->getReference($this->tabname)->getChild($roomId);
        $roomData = $roomRef->getValue();

        if (!$roomData) {
            return response()->json(['error' => 'Room not found'], 404);
        }
        $roomRef->update([
            'diceValue' => $diceValue
        ]);
        return response()->json(['message' => 'Dice roll sent successfully', 'diceValue' => $diceValue]);
    }

    public function getDiceRoll(Request $request){

        $roomId = $request->input('roomId');
        $roomData = $this->database->getReference($this->tabname)->getChild($roomId)->getValue();
        
        if (!$roomData) {
            return response()->json(['error' => 'Room not found'], 404);
        }
        $diceValue = $roomData['diceValue'];
        return response()->json(['diceValue' => $diceValue]);

    }

    public function sendWin(Request $request){
        $roomId = $request->input('roomId');
        $win = $request->input('win');
        $PW = $request->input('PW');
        $roomRef = $this->database->getReference($this->tabname)->getChild($roomId);
        $roomData = $roomRef->getValue();

        if (!$roomData) {
            return response()->json(['error' => 'Room not found'], 404);
        }
        $roomRef->update([
            'win' => $win,
            'PW' => $PW
            
        ]);

        return response()->json(['message' => 'Position updated successfully']);
    }

    public function getWin(Request $request){

        $roomId = $request->input('roomId');
        $roomData = $this->database->getReference($this->tabname)->getChild($roomId)->getValue();
        
        if (!$roomData) {
            return response()->json(['error' => 'Room not found'], 404);
        }
        $PW = $roomData['PW'];
        $win = $roomData['win'];
        return response()->json(['win' => $win, 'PW' => $PW]);

    }
    public function deleteRoom(Request $request)
{
    $roomId = $request->input('roomId');

    $deleteAction = $this->database->getReference($this->tabname)->getChild($roomId)->remove();

    if ($deleteAction) {
        return response()->json(['success' => 'Room deleted successfully'], 200);
    } else {
        return response()->json(['error' => 'Failed to delete room'], 500);
    }
}

    
}
