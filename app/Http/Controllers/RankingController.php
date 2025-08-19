<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Auth\SignInResult\SignInResult;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Contract\Database;
use Session;

class RankingController extends Controller
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
        $auth = app('firebase.auth');
        $usersSnapshot = $auth->listUsers($defaultMaxResults = 1000, $defaultBatchSize = 1000);
        
        // Przekonwertuj snapshot na tablicę użytkowników
        $users = [];
        foreach ($usersSnapshot as $user) {
            $users[] = $user;
        }
    
        $games =  $this->database->getReference($this->tabname)->getValue();  
        $rankings = $this->database->getReference("ranking")->getValue();
    
        // Pogrupuj rekordy wyników po grze
        $groupedRankings = [];
        foreach ($rankings as $ranking) {
            $groupedRankings[$ranking['gid']][] = $ranking;
        }
    
        // Dla każdej gry znajdź trzy najlepsze wyniki
        foreach ($games as $gameId => &$game) {
            if (isset($groupedRankings[$gameId])) {
                $gameRankings = $groupedRankings[$gameId];
                // Sortuj rankingi dla tej gry malejąco według wyniku
                usort($gameRankings, function($a, $b) {
                    return $b['points'] - $a['points'];
                });
                // Pobierz trzy najlepsze wyniki dla tej gry
                $topThreeRankings = array_slice($gameRankings, 0, 3);
                // Dopasuj użytkowników na podstawie uid
                foreach ($topThreeRankings as &$ranking) {
                    $userId = $ranking['uid'];
                    foreach ($users as $user) {
                        if ($user->uid === $userId) {
                            $ranking['user'] = $user;
                            break;
                        }
                    }
                }
                // Przypisz topThreeRankings do gry
                $game['topThreeRankings'] = $topThreeRankings;
            }
        }
    
        return view('rankings.index', compact('games'));
    }

    public function gamerank($gid)
    {
    $key = $gid;
    $auth = app('firebase.auth');
    $usersSnapshot = $auth->listUsers($defaultMaxResults = 1000, $defaultBatchSize = 1000);

    // Przekonwertuj snapshot na tablicę użytkowników
    $users = [];
    foreach ($usersSnapshot as $user) {
        $users[] = $user;
    }

    // Pobierz dane konkretnej gry na podstawie $key
    $game = $this->database->getReference($this->tabname . '/' . $key)->getValue();

    // Jeśli gra nie istnieje, możemy zwrócić odpowiedni widok lub komunikat błędu
    if (!$game) {
        return view('errors.404'); // Możemy użyć dedykowanej strony błędu 404
    }

    // Pobierz dane rankingowe tylko dla konkretnej gry
    $rankings = $this->database->getReference("ranking")
        ->orderByChild('gid')
        ->equalTo($key)
        ->getValue();

    // Sortuj rankingi malejąco według wyniku
    usort($rankings, function($a, $b) {
        return $b['points'] - $a['points'];
    });

    // Oblicz liczbę wszystkich rekordów
    $rankings;
    $totalRecords = count($rankings);

    // Paginacja
    $perPage = 10; // liczba rekordów na stronie
    $page = request()->get('page', 1); // numer bieżącej strony
    $totalPages = ceil($totalRecords / $perPage); // całkowita liczba stron
    $offset = ($page - 1) * $perPage; // offset dla zapytania

    // Pobierz tylko rekordy dla danej strony
    $hisPaginated = array_slice($rankings, $offset, $perPage);

    // Dopasuj użytkowników na podstawie uid dla rekordów na danej stronie
    foreach ($hisPaginated as &$ranking) {
        $userId = $ranking['uid'];
        foreach ($users as $user) {
            if ($user->uid === $userId) {
                $ranking['user'] = $user;
                break;
            }
        }
    }

    // Przekazanie danych do widoku
    return view('rankings.showR', compact('game', 'hisPaginated', 'totalPages', 'page'));
}

   
}
