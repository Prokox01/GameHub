@extends('layouts.app')
<style>
    * {
    box-sizing: border-box;
}
.ludo-container {
    width: 450px;
    margin: 20px auto;
    zoom: 1.75;
    position: relative;
}

.ludo-container .ludo {

    height: 450px;
    width: 100%;
    background-image: url('../../../../ludo/ludo-bg.jpg');
    background-size: contain;
    position: relative;
}

.player-pieces {
    height: 100%;
    width: 100%;
}

.player-piece {
    width: 3%;
    height: 3%;
    border: 2px solid;
    border-radius: 10px;
    position: absolute;
    transform: translate(50%, 50%);
    transition: all .2s;
    z-index: 1;
}
.player-piece.highlight {
    cursor: pointer;
    border: 2px dashed;
    animation: spin 1s infinite linear;
}
@keyframes spin {
    0% {
        transform: translate(50%, 50%) rotate(0deg);
    }
    50% {
        transform: translate(50%, 50%) rotate(180deg) scale(1.4);
    }
    100% {
        transform: translate(50%, 50%) rotate(360deg);
    }
}
.player-base {
    width: 40%;
    height: 40%;
    border: 30px solid;
    position: absolute;
}

@foreach($players as $index => $playerId)
        [player-id="{{$playerId}}"].player-piece {
            background-color: {{$index === 0 ? '#2eafff' : ($index === 1 ? '#eddb16' : ($index === 2 ? '#00b550' : 'red'))}};
        }
 @endforeach

 @foreach($players as $index => $playerId)
        .player-bases [player-id="{{ $playerId }}"].player-base {
            @if($index === 0)
                left: 0;
                bottom: 0;
                border-color: #2eafff;
            @elseif($index === 1)
                top: 0;
                left: 0;
                border-color: #eddb16;
            @elseif($index === 2)
                top: 0;
                right: 0;
                border-color: #00b550;
            @else
                bottom: 0;
                right: 0;
                border-color: red; 
            @endif
        }
@endforeach


.player-base.highlight {
    animation: border-blink .7s infinite ease-in-out;
}

@keyframes border-blink {
    50% {
        border-color: rgba(255, 255, 255, 0.8);
    }
}

.btn {
    padding: 8px 20px;
    border: none;
    cursor: pointer;
    font-size: 16px;
}

.btn:disabled {
    opacity: 0.5;
}

.btn-dice {
    background-color: transparent;
    color: white;
    z-index: 2;
    width: 100px;
    height: 100px;
}

.row {
    display: flex;
    justify-content: space-between;
    margin-top: 15px;
    margin-left: 35%;
}

.dice-value {
    color: transparent;
}
.kostka{
    margin-left: -48%;
}
.kostka img {
    width: 100px;
    height: 100px;
}
.shake {
    animation: rotateAndPulse 3s linear infinite;
}

@keyframes rotateAndPulse {
    0%, 100% {
        transform: rotate(0deg) scale(1);
    }
    50% {
        transform: rotate(360deg) scale(1.1);
    }
}
.active-player{
    color: white;
}
#reset-btn{
    color: white;
}
.reset-button {
    opacity: 0;
    pointer-events: none;
}
.dice-value{
    opacity: 0;
}
</style>
@section('content')
    <div class="ludo-container">
        <div class="ludo">
        <div class="player-pieces">
        @foreach($players as $playerId)
            <div class="player-piece" player-id="{{ $playerId }}" piece="0"></div>
            <div class="player-piece" player-id="{{ $playerId }}" piece="1"></div>
            <div class="player-piece" player-id="{{ $playerId }}" piece="2"></div>
            <div class="player-piece" player-id="{{ $playerId }}" piece="3"></div>
        @endforeach
        </div>
            </div>

            <div class="player-bases">
            @foreach($players as $playerId)
                    <div class="player-base" player-id="{{ $playerId }}"></div>
                @endforeach
            </div>
        </div>
        <div class="footer">
            <div class="row">
                <button id="dice-btn" class="btn btn-dice"></button>
                <div class="kostka">
                    <img src="{{ asset('ludo/jeden.png') }}">
                </div>
                <div class="dice-value"></div>
                <button id="reset-btn" class="reset-button">Reset</button>
            </div>
            <h2 class="active-player">Active Player: <span></span> </h2>
        </div>
    </div>
    <script src="../../../../ludo/main2.js" type="module"></script>

    <div id="win-box" class="container bg-light" style="display: none; position: absolute; text-align: center; top: 50%; left: 50%; transform: translate(-50%, -50%); box-shadow: 2px 2px 15px 5px lightgreen; max-width: 40%; width: 40%; color: lightgreen; z-index: 9999">
    <h1>Wygrana!!</h1>
    <h1>+10</h1>
    <a href="/games/play/chinczyk/rooms/{{$gid}}" class="btn btn-warning mb-2">Zagraj jeszcze raz</a>
    <a href="/games" class="btn btn-danger mb-2">Wróć do wyboru gry</a>
    </div>
    <div id="lose-box" class="container bg-light" style="display: none; position: absolute; text-align: center; top: 50%; left: 50%; transform: translate(-50%, -50%); box-shadow: 2px 2px 15px 5px red; max-width: 40%; width: 40%; color: red; z-index: 9999">
        <h1>Przegrana!!</h1>
        <h1>-10</h1>
        <a href="/games/play/chinczyk/rooms/{{$gid}}" class="btn btn-warning mb-2">Zagraj jeszcze raz</a>
        <a href="/games" class="btn btn-danger mb-2">Wróć do wyboru gry</a>
    </div>
    <div id="draw-box" class="container bg-light" style="display: none; position: absolute; text-align: center; top: 50%; left: 50%; transform: translate(-50%, -50%); box-shadow: 2px 2px 15px 5px grey; max-width: 40%; width: 40%; color: grey; z-index: 9999">
        <h1>Remis!!</h1>
        <h1>0</h1>
        <a href="/games/play/chinczyk/rooms/{{$gid}}" class="btn btn-warning mb-2">Zagraj jeszcze raz</a>
        <a href="/games" class="btn btn-danger mb-2">Wróć do wyboru gry</a>
    </div>

    <div style="display: none;">
        <form id="addForm" method="POST" action="{{url('add-history')}}">
            @csrf
            <input id="id" type="text" value="{{$gid}}" name="id" readonly hidden>
            <input name="wynik" id="wynik" type="text" value="" readonly style="border: none; background-color: transparent; color: black; font-size: 52px; text-align: center; width: 100px;">
        </form>
    </div>
@endsection
<script>
    var roomId = new URLSearchParams(window.location.search).get('roomId');
    console.log('Room ID:', roomId);
    var players = @json($players);
    var ActivePlayer = @json($ActivePlayer);
    const currentPlayerUid = "{{Session::get('uid') }}";

    function box(b) {
            if (b == 0) {
                document.getElementById("win-box").style.display = "block";
            } else if (b == 2) {
                document.getElementById("draw-box").style.display = "block";
            } else {
                document.getElementById("lose-box").style.display = "block";
            }
        }

        function addRecord() {
            var formData = $('#addForm').serialize();
            $.ajax({
                url: "{{url('add-history')}}",
                type: "POST",
                data: formData,
                success: function(response) {
                    console.log(response);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
        
    
</script>
