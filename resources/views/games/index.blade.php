@extends('layouts.app')
@section('content')
<br>
@if($games)
<div class="container">
<div class="row">
    @foreach($games as $key => $game)
    <div class="col-md-6 mb-8 d-flex justify-content-center" >
                <div class="card" style="margin-top: 50px; box-shadow: 2px 2px 15px 5px white; border-radius:25px; max-width: 350px;">
                    <img src="{{$game['image']}}" class="card-img-top" style="max-width: 350px; height: 350px; border-radius:25px;">

                    <div class="card-body text-center">
                        <h5 class="card-title">{{$game['name']}}</h5>
                    </div>

                    <div class="card-footer d-flex justify-content-center">
                        <a href="games/{{$key}}" class="btn btn-warning">Zobacz grę</a>
                    </div>
                </div>
    </div>
    @endforeach
</div>
</div>
    @else
    <h1>Brak gier!!</h1>
     @endif
@endsection