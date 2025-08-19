@extends('layouts.app')
@section('content')
<br>
@if($players)
<div class="container">

<div class="row">
    <div class="col-md-12 mb-12 d-flex justify-content-center">
        <form action="{{ route('players.search') }}" method="GET">
            <div class="input-group">
                <input type="text" class="form-control" name="query" placeholder="Znajdź graczy...">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">Szukaj</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    @foreach($players as $key => $u)
    <div class="col-md-3 mb-12 d-flex justify-content-center">
    <div class="card" style="background-color: white; margin-top: 50px; box-shadow: 2px 2px 15px 5px white; border-radius: 25px; width: 125px; height: 150px">
        <div class="card-body d-flex flex-column justify-content-center align-items-center" style="color: white">
            @if($u->photoUrl != NULL)
                <img style="width: 50px; height: 50px; border-radius: 50%;" src="{{ $u->photoUrl }}" alt="">
            @else
                <img style="width: 50px; height: 50px; border-radius: 50%;" src="https://firebasestorage.googleapis.com/v0/b/centrumgier-a08cf.appspot.com/o/pfp%2Fdefault.jpg?alt=media" alt="">
            @endif
            <br>
            <a class="card-title h-2" href="/prof/{{ $u->uid }}">{{ $u->displayName }}</a>
        </div>
    </div>
</div>      
    @endforeach
</div>
</div>
    @else
    <h1>Brak graczy!!</h1>
     @endif
@endsection