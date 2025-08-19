@extends('layouts.app')
<style>
    * {
    box-sizing: border-box;
}
.ludo-container {
    width: 450px;
    margin: 20px auto;
    zoom: 1.75;
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

[player-id="P1"].player-piece {
    background-color: #2eafff;
}

[player-id="P2"].player-piece {
    background-color: #eddb16;
}
[player-id="P3"].player-piece{
    background-color: #00b550;
}
[player-id="P4"].player-piece{
    background-color: red;
}

.player-base {
    width: 40%;
    height: 40%;
    border: 30px solid;
    position: absolute;
}

.player-bases [player-id="P1"].player-base {
    bottom: 0;
    left: 0;
    border-color: #1295e7;
}

.player-bases [player-id="P2"].player-base {
    top: 0;
    left: 0;
    border-color: RGB(255,222,23);
}

.player-bases [player-id="P3"].player-base{
    top: 0;
    right: 0;
    border-color: #049645;
}

.player-bases [player-id="P4"].player-base{
    bottom: 0;
    right: 0;
    border-color: RGB(232,22,33);
}


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
    width: 50px;
}

.row {
    display: flex;
    justify-content: space-between;
    margin-top: 15px;
}

.dice-value {
    color: transparent;
}
.kostka{
    margin-left: -48%;
}
.kostka img {
    height: 50px;
    width: 50px;
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
</style>
@section('content')
    <div class="ludo-container">
        <div class="ludo">
            <div class="player-pieces">
                <div class="player-piece" player-id="P1" piece="0"></div>
                <div class="player-piece" player-id="P1" piece="1"></div>
                <div class="player-piece" player-id="P1" piece="2"></div>
                <div class="player-piece" player-id="P1" piece="3"></div>
                
                <div class="player-piece" player-id="P2" piece="0"></div>
                <div class="player-piece" player-id="P2" piece="1"></div>
                <div class="player-piece" player-id="P2" piece="2"></div>
                <div class="player-piece" player-id="P2" piece="3"></div>

                <div class="player-piece" player-id="P3" piece="0"></div>
                <div class="player-piece" player-id="P3" piece="1"></div>
                <div class="player-piece" player-id="P3" piece="2"></div>
                <div class="player-piece" player-id="P3" piece="3"></div>

                <div class="player-piece" player-id="P4" piece="0"></div>
                <div class="player-piece" player-id="P4" piece="1"></div>
                <div class="player-piece" player-id="P4" piece="2"></div>
                <div class="player-piece" player-id="P4" piece="3"></div>
            </div>

            <div class="player-bases">
                <div class="player-base" player-id="P1"></div>
                <div class="player-base" player-id="P2"></div>
                <div class="player-base" player-id="P3"></div>
                <div class="player-base" player-id="P4"></div>
            </div>
        </div>
        <div class="footer">
            <div class="row">
                <button id="dice-btn" class="btn btn-dice"></button>
                <div class="kostka">
                    <img src="{{ asset('ludo/jeden.png') }}">
                </div>
                <div class="dice-value"></div>
                <button id="reset-btn" class="btn btn-reset">Reset</button>
            </div>
            <h2 class="active-player">Active Player: <span></span> </h2>
        </div>
    </div>

    <script src="../../../../ludo/main.js" type="module"></script>
@endsection