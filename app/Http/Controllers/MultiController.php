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

class MultiController extends Controller
{
    private $database;
    private $tabname;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->tabname = "rooms_Chinese"; 
    }
    public function chinczyk($gid)
    {
        $rooms = $this->database->getReference('rooms_Chinese')->getValue();
        $availableRooms = [];
        $currentRoom = null;
        $currentRoomId = null;
        $play = null;

        $currentPlayerUid = Session::get('uid');
        $currentRoomData = $this->getRoomByPlayerUid($currentPlayerUid);
        if ($currentRoomData) {
            $currentRoom = $currentRoomData['room'];
            $currentRoomId = $currentRoomData['id'];
            $play = $currentRoom['play'];
        }
    
        if ($rooms) {
            foreach ($rooms as $roomId => $room) {
                if (!isset($room['players']['4']) && isset($room['roomNumber']) && !$room['play']) {
                    $availableRooms[$roomId] = $room['roomNumber'];
                }
            }
        }
    
        return view('games.play.chinczyk.chinczyk_rooms', compact('gid', 'availableRooms', 'currentRoom', 'currentRoomId','play'));
    }

    public function createRoomChinczyk(Request $request)
    {
        $currentPlayerUid = Session::get('uid');
        $existingRoomId = $this->getRoomByPlayerUid($currentPlayerUid);
        if ($existingRoomId !== null) {
            return response()->json(['error' => 'Player already has a room'], 400);
        }

        $roomsCount = count($this->database->getReference('rooms_Chinese')->getValue());
        $roomNumber = $roomsCount;
        $currentPlayerNumber = 1;
        $timestamp = now()->timestamp;


        $roomData = [
            'roomNumber' => $roomNumber,
            'players' => [
                $currentPlayerNumber => $currentPlayerUid,
                '2' => null,
                '3' => null,
                '4' => null,
            ],
            'moves' => [],
            'play' => false,
            'updated_at' => $timestamp,
            'ActivePlayer' => $currentPlayerUid,
        ];
        $this->database->getReference('rooms_Chinese')->push($roomData);
        
        return response()->json(['message' => 'Room created successfully']);
    }

    public function joinRoomChinczyk(Request $request)
    {
        Log::info('Join room request: ' . json_encode($request->all()));
        $roomId = $request->input('roomId');
        $currentPlayerUid = $request->input('currentPlayerUid');
        
        $roomByPlayerUid = $this->getRoomByPlayerUid($currentPlayerUid);
        if ($roomByPlayerUid !== null) {
            return response()->json(['error' => 'Player is already in a room'], 400);
        }
        $room = $this->database->getReference($this->tabname)->getChild($roomId)->getValue();
        Log::info('Room: ' . json_encode($room));
        if (!isset($room['players'])) {
            return response()->json(['error' => 'Room not found'], 400);
        }
        
        $playerSlots = ['2', '3', '4'];
        foreach ($playerSlots as $slot) {
            if (!isset($room['players'][$slot])) {
                $this->database->getReference($this->tabname)
                    ->getChild($roomId)
                    ->getChild('players')
                    ->getChild($slot)
                    ->set($currentPlayerUid);
                return response()->json(['message' => "Player $slot joined the room"]);
            }
        }
        
        return response()->json(['error' => 'All player slots are already occupied'], 400);
    }

    private function getRoomByPlayerUid($uid)
    {
        $rooms = $this->database->getReference('rooms_Chinese')->getValue();
    
        foreach ($rooms as $roomId => $room) {
            if (isset($room['players']) && in_array($uid, $room['players'])) {
                return ['id' => $roomId, 'room' => $room];
            }
        }
        return null;
    }

    public function exitRoomChinczyk(Request $request)
    {
        $roomId = $request->input('roomId');
        $currentPlayerUid = $request->input('currentPlayerUid');
    
        if ($roomId === 'room') {
            return response()->json(['error' => 'Cannot exit the room with name "room"'], 400);
        }
    
        $roomRef = $this->database->getReference('rooms_Chinese')->getChild($roomId);
        $room = $roomRef->getValue();
        if ($room === null || !isset($room['players']) || !is_array($room['players'])) {
            return response()->json(['error' => 'Invalid room data'], 400);
        }
    
        $slot = array_search($currentPlayerUid, $room['players']);
        if ($slot === false) {
            return response()->json(['error' => 'Player not found in the room'], 400);
        }
    
        $roomRef->update([
            'players/'.$slot => null,
        ]);
        $this->removeEmptyRooms();
    
        return response()->json(['message' => 'Player successfully exited the room']);
    }

    public function removeEmptyRooms()
    {
        $rooms = $this->database->getReference('rooms_Chinese')->getValue();
        
        foreach ($rooms as $roomId => $room) {
            if ($roomId === 'room') {
                continue;
            }
            
            $isEmpty = true;
            if (isset($room['players'])) {
                foreach ($room['players'] as $player) {
                    if ($player !== null) {
                        $isEmpty = false;
                        break;
                    }
                }
            }
            if ($isEmpty) {
                $this->database->getReference('rooms_Chinese')->getChild($roomId)->remove();
                Log::info("Room $roomId removed because it was empty.");
                break;
            }
        }
    }
    
    public function fetchRoomInfo()
    {
        $previousRoomCount = Session::get('roomCount');
        $currentRoomCount = count($this->database->getReference('rooms_Chinese')->getValue());

        $rooms = $this->database->getReference('rooms_Chinese')->getValue();
        
        if ($previousRoomCount !== $currentRoomCount) {
            Session::put('roomCount', $currentRoomCount);
            return response()->json(['hasChanges' => true, 'rooms' => $rooms]);
        }
        
        return response()->json(['hasChanges' => false, 'rooms' => $rooms]);
    }

    public function fetchPlayerCount()
        {
            $rooms = $this->database->getReference('rooms_Chinese')->getValue();
            
            $currentPlayerCount = 0;
            $play = null;
            foreach ($rooms as $room) {
                $players = $room['players'] ?? [];
                $currentPlayerCount += count($players);
            }
            $currentPlayerCount = $currentPlayerCount-1;
            
            $previousPlayerCount = Session::get('playerCount');
            Session::put('playerCount', $currentPlayerCount);

            if ($previousPlayerCount !== $currentPlayerCount) {
                return response()->json(['hasChanges' => true, 'playerCount' => $currentPlayerCount]);
            } else {
                return response()->json(['hasChanges' => false, 'playerCount' => $currentPlayerCount]);
            }
        }
        
        public function startGameChinczyk(Request $request)
        {
            $roomId = $request->input('roomId');
        
            $roomRef = $this->database->getReference('rooms_Chinese')->getChild($roomId);
        
            $roomData = $roomRef->getValue();
        
            if (!$roomData) {
                return response()->json(['error' => 'Room not found'], 404);
            }
        
            $players = $roomData['players'];
        
            $roomRef->update([
                'play' => true,
            ]);
        
            return response()->json(['message' => 'Game started successfully', 'players' => $players]);
        }

        public function getPlayValue(Request $request)
        {
            $roomId = $request->input('roomId');
    
            $roomData = $this->database->getReference('rooms_Chinese')->getChild($roomId)->getValue();
            
            if (!$roomData) {
                return response()->json(['error' => 'Room not found'], 404);
            }
    
            $play = $roomData['play'] ?? false;
    
            return response()->json(['play' => $play]);
        }
}
