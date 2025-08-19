<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GamesController;
use App\Http\Controllers\AdminGamesController;
use App\Http\Controllers\AdminHistoryController;
use App\Http\Controllers\AdminUsersController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\AdminRankingController;
use App\Http\Controllers\AdminAchivUserController;
use App\Http\Controllers\AdminAchivmentsController;
use App\Http\Controllers\AchivmentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\MultiController;
use App\Http\Controllers\MultiGameController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [GamesController::class,'welcome']);

/////////Admin///////////////
Route::get('/admin/games', [AdminGamesController::class,'index'])->middleware('checkUser');
Route::post('add-game', [AdminGamesController::class, 'store'])->middleware('checkUser');
Route::get('/admin/edit/game/{gid}', [AdminGamesController::class, 'edit'])->middleware('checkUser');
Route::put('update-game/{gid}', [AdminGamesController::class, 'update'])->middleware('checkUser');
Route::get('/admin/delete/game/{gid}', [AdminGamesController::class, 'delete'])->middleware('checkUser');

Route::get('/admin/history', [AdminHistoryController::class,'index'])->middleware('checkUser');
Route::post('addhistory', [AdminHistoryController::class,'storeH'])->middleware('checkUser');
Route::get('/admin/edit/history/{gid}', [AdminHistoryController::class, 'editH'])->middleware('checkUser');
Route::put('update-history/{gid}', [AdminHistoryController::class, 'updateH'])->middleware('checkUser');
Route::get('/admin/delete/history/{gid}', [AdminHistoryController::class, 'deleteH'])->middleware('checkUser');

Route::get('/admin/users', [AdminUsersController::class,'index'])->middleware('checkUser');
Route::post('adduser', [AdminUsersController::class,'storeU'])->middleware('checkUser');
Route::get('/admin/edit/user/{gid}', [AdminUsersController::class, 'editU'])->middleware('checkUser');
Route::put('update-user/{gid}', [AdminUsersController::class, 'updateU'])->middleware('checkUser');
Route::get('/admin/delete/user/{gid}', [AdminUsersController::class, 'deleteU'])->middleware('checkUser');

Route::get('/admin/rankings', [AdminRankingController::class,'index'])->middleware('checkUser');
Route::post('addranking', [AdminRankingController::class,'storeR'])->middleware('checkUser');
Route::get('/admin/edit/ranking/{gid}', [AdminRankingController::class, 'editR'])->middleware('checkUser');
Route::put('update-ranking/{gid}', [AdminRankingController::class, 'updateR'])->middleware('checkUser');
Route::get('/admin/delete/ranking/{gid}', [AdminRankingController::class, 'deleteR'])->middleware('checkUser');

Route::get('/admin/achivments', [AdminAchivmentsController::class,'index'])->middleware('checkUser');
Route::post('add-achivment', [AdminAchivmentsController::class, 'storeA'])->middleware('checkUser');
Route::get('/admin/edit/achivments/{gid}', [AdminAchivmentsController::class, 'editA'])->middleware('checkUser');
Route::put('update-achivments/{gid}', [AdminAchivmentsController::class, 'updateA'])->middleware('checkUser');
Route::get('/admin/delete/achivments/{gid}', [AdminAchivmentsController::class, 'deleteA'])->middleware('checkUser');

Route::get('/admin/achivuser', [AdminAchivUserController::class,'index'])->middleware('checkUser');
Route::post('add-achivuser', [AdminAchivUserController::class,'storeAV'])->middleware('checkUser');
Route::get('/admin/edit/achivuser/{gid}', [AdminAchivUserController::class, 'editAV'])->middleware('checkUser');
Route::put('update-achivuser/{gid}', [AdminAchivUserController::class, 'updateAV'])->middleware('checkUser');
Route::get('/admin/delete/achivuser/{gid}', [AdminAchivUserController::class, 'deleteAV'])->middleware('checkUser');

/////////////////////////////////////////


/////////////////////Strony i gry/////////////////

Route::get('/contact', function () {
    return view('contact');
});

///////gry////////


Route::get('/games/play/snake/{gid}', [GamesController::class, 'snake']);
Route::get('/games/play/con4/{gid}', [GamesController::class, 'con4']);
Route::get('/games/play/rps/{gid}', [GamesController::class, 'rps']);
Route::get('/games/play/kubki/{gid}', [GamesController::class, 'kubki']);
Route::get('/games/play/chinczyk/{gid}', [GamesController::class, 'chinczyk']);
Route::get('/games/play/chinczyk/chinczyk_solo/{gid}', [GamesController::class, 'chinczyk_solo']);
Route::get('/games/play/chinczyk/chinczyk_multi/{gid}', [MultiGameController::class, 'chinczyk_multi']);
Route::get('/games/play/chinczyk/rooms/{gid}', [MultiController::class, 'chinczyk']);

Route::post('/join-roomChinczyk', [MultiController::class, 'joinRoomChinczyk']);
Route::post('/create-roomChinczyk', [MultiController::class, 'createRoomChinczyk']);
Route::post('/exit-roomChinczyk', [MultiController::class, 'exitRoomChinczyk']);
Route::get('/fetch-room-info', [MultiController::class, 'fetchRoomInfo']);
Route::get('/fetch-player-count', [MultiController::class, 'fetchPlayerCount']);
Route::post('/start-game', [MultiController::class, 'startGameChinczyk']);
Route::post('/get-play-value', [MultiController::class, 'getPlayValue']);
Route::post('/fetch-player-count-game', [MultiController::class, 'fetchPlayerCountInRoom']);
Route::post('/updateActivePlayer', [MultiGameController::class, 'updateActivePlayer']);
Route::post('/getActivePlayer', [MultiGameController::class, 'getActivePlayer']);
Route::post('/updateGameState', [MultiGameController::class, 'updateGameState']);
Route::post('/getGameState', [MultiGameController::class, 'getGameState']);
Route::post('/sendDiceRoll', [MultiGameController::class, 'sendDiceRoll']);
Route::post('/getDiceRoll', [MultiGameController::class, 'getDiceRoll']);
Route::post('/sendWin', [MultiGameController::class, 'sendWin']);
Route::post('/getWin', [MultiGameController::class, 'getWin']);
Route::post('/deleteRoom', [MultiGameController::class, 'deleteRoom']);

Route::post('/addMoveCon4', [GamesController::class, 'addMoveCon4']);
Route::post('/getMoveCon4', [GamesController::class, 'getMoveCon4']);
Route::post('/delroomCon4', [GamesController::class, 'delroomCon4']);
Route::post('/getrooms', [GamesController::class, 'getrooms']);
Route::post('/join-room', [GamesController::class, 'joinRoomCon4']);
Route::post('/create-room', [GamesController::class, 'createRoomCon4']);
Route::resource('/games', GamesController::class);
Route::get('/games/{gid}', [GamesController::class, 'show']);
Route::post('add-history', [GamesController::class, 'add']);
Route::get('/rankings', [RankingController::class, 'index']);
Route::get('/rankings/{gid}', [RankingController::class, 'gamerank']);
Route::get('/achivments', [AchivmentController::class, 'index']);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('user');
/////////////////////////////////////////

/////////////////////Autoryzacja i profile/////////////////
Auth::routes();
Route::get('/players/search', 'App\Http\Controllers\Auth\PlayersController@search')->name('players.search');
Route::get('/players', [App\Http\Controllers\Auth\PlayersController::class, 'index']);
Route::get('/email/verify', [App\Http\Controllers\Auth\ResetController::class, 'verify_email'])->name('verify')->middleware('fireauth');
Route::post('login/{provider}/callback', 'Auth\LoginController@handleCallback');
Route::resource('/profile', App\Http\Controllers\Auth\ProfileController::class)->middleware('user','fireauth');
Route::get('/prof/{uid}', [App\Http\Controllers\Auth\ProfController::class, 'index']);
Route::get('/history/{uid}', [App\Http\Controllers\Auth\HisController::class, 'index']);
Route::get('/achivments/{uid}', [App\Http\Controllers\Auth\AchivController::class, 'index']);
Route::resource('/password/reset', App\Http\Controllers\Auth\ResetController::class);
Route::resource('/img', App\Http\Controllers\ImageController::class);
/////////////////////////////////////////
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
