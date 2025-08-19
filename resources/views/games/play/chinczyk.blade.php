@extends('layouts.app')
  <style>
    h1 {
      color: #fff;
      margin-top: 20px;
      font-size: 10vw;
      text-align: center;
      padding: 100px;
    }
    .flex_container {
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: row;
    }
    a {
      text-decoration: none;
      color: #fff;
    }
    .rainbow-button {
      margin: 0 80px;
      padding: 20px 40px;
      width: calc(20vw + 10px);
      height: calc(8vw + 6px);
      background-image: linear-gradient(
        90deg,
        #00c0ff 0%,
        #ffcf00 49%,
        #fc4f4f 80%,
        #00c0ff 100%
      );
      border-radius: 5px;
      display: flex;
      align-items: center;
      justify-content: center;
      text-transform: uppercase;
      font-size: 2.5vw;
      font-weight: bold;
      position: relative;
      border: 2px solid transparent;
      background-clip: padding-box;
      box-sizing: border-box;
      transition: background-color 0.3s, transform 0.3s; /* Dodane przekształcenie */
    }
    .rainbow-button:before {
      content: '';
      position: absolute;
      top: -2px;
      left: -2px;
      right: -2px;
      bottom: -2px;
      background: linear-gradient(
        90deg,
        #00c0ff 0%,
        #ffcf00 49%,
        #fc4f4f 80%,
        #00c0ff 100%
      );
      border-radius: 5px;
      z-index: -1;
      animation: slidebg 2s linear infinite;
    }
    .rainbow-button:hover:before {
      animation: slidebg 2s linear infinite;
    }
    .rainbow-button:after {
      content: attr(alt);
      width: 100%;
      height: 100%;
      background-color: #191919;
      display: flex;
      align-items: center;
      justify-content: center;
      position: absolute;
      border-radius: 5px;
    }
    .rainbow-button:hover {
      background-image: linear-gradient(
        90deg,
        #ff6f00 0%,
        #ffcf00 17%,
        #00e676 33%,
        #00b0ff 49%,
        #2962ff 66%,
        #d500f9 80%,
        #ff6f00 100%
      );
      transform: scale(1.1); 
    }

    @keyframes slidebg {
      to {
        background-position: 10vw;
      }
    }
    
  </style>
@section('content')
<h1>Wybierz tryb gry</h1>
<div class="flex_container">
  <a href="/games/play/chinczyk/chinczyk_solo/{{ $gid }}" class="rainbow-button" alt="Single Player">Single <span>Player</span></a>
  <a href="/games/play/chinczyk/rooms/{{ $gid }}" class="rainbow-button" alt="Multi Player">Multi <span>Player</sapn></a>
</div>
@endsection