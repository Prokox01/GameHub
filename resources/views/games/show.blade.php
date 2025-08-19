@extends('layouts.app')
@section('content')

<div style="width: 80%" class="container">
<h1 class="text-center" style="color: white; text-size:5em; box-shadow: 2px 2px 15px 5px white; border-radius:25px; margin: 5px; padding: 5px;"> {{$game['name']}}</h1>
<br>
<div style="color: white" class="row" >
    <div class="col-md-6 mb-6 d-flex justify-content-center" >
        <img src="{{$game['image']}}" class="card-img-top m-2" style="max-width: 500px; height: 500px;">
    </div>
    <div class="col-md-6 mb-6 d-flex  p-5" style="box-shadow: 2px 2px 15px 5px orange; border-radius:25px;" >
        <p>
            {{$game['desc']}}
        </p>
    </div>
</div>
@guest
@else
<br>
<br>
<div class="border-bottom border-grey"></div>
<br>
<div class="col-md-12 mb-12 d-flex justify-content-center" >
    @if($game['link'] != "brak!")
    <div style="color: white" class="row" >
        <a href="/games" class="btn btn-danger" style="font-size: 4em; border-radius:25px; box-shadow: 2px 2px 15px 5px red; margin-right: 25px">Wróć</a>
        <a href="{{$game['link'].'/'.$gid}}"class="btn btn-warning" style="font-size: 4em; border-radius:25px; box-shadow: 2px 2px 15px 5px orange;">Zagraj</a>
    </div>
    @endif
</div>
@endguest
</div>
@endsection