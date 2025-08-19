@extends('layouts.app')
@section('content')
<br>
@if($achivments)
<div class="container">
<div class="row">
    @foreach($achivments as $key => $achivment)
    <div class="col-md-4 mb-3 d-flex justify-content-center" >
                <div class="card" style="background-color: white; margin-top: 50px;  box-shadow: 2px 2px 15px 5px white;  border-radius:25px; max-width: 250px;">
                    <img src="{{$achivment['image']}}" class="card-img-top" style="max-width: 250px; height: 250px; border-radius:25px;">

                    <div class="card-body text-center" style="color: black">
                        <h5 class="card-title">{{$achivment['name']}}</h5>
                        <div class="border-bottom border-grey"></div>
                        <br>
                        <div class="overflow-auto" style="word-wrap: break-word; max-height: 100px; max-width: 250px;">
                        {{$achivment['desc']}}
                    </div>
                    </div>
            </div>       
    </div>
    @endforeach
</div>
</div>
    @else
    <h1>Brak osiągnięć!!</h1>
     @endif
@endsection