@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
    @foreach($games as $key => $game)
    <div class="col-md-6 mb-8 d-flex justify-content-center" >
        <div class="card" style="margin-top: 50px; box-shadow: 2px 2px 15px 5px white; border-radius:25px; max-width: 450px;">
            <h5 class="card-title text-center">{{$game['name']}}</h5>
            <img src="{{$game['image']}}" class="img-fluid rounded" alt="{{$game['name']}}" style="width: 450px;  height: 300px;">
            @if(!empty($game['topThreeRankings']))
            <table class="table ">
                <tr>
                    <th>Miejsce</th>
                    <th>Gracz</th>
                    <th>Wynik</th>
                </tr>
                @foreach($game['topThreeRankings'] as $index => $ranking)
                <tr>
                    <td>
                        <img src="https://firebasestorage.googleapis.com/v0/b/centrumgier-a08cf.appspot.com/o/Images%2F{{$index+1}}.png?alt=media" class="img-fluid" alt="Medal" style="width: 50px;">
                    </td>
                    <td>
                        @if($ranking['user']->photoUrl != NULL)
                        <img style="width: 50px; height: 50px; border-radius:180px; padding-bottom: 5px;" src="{{$ranking['user']->photoUrl}}" alt="">
                        @else
                        <img style="width: 50px; height: 50px; border-radius:180px; padding-bottom: 5px; border: 5px solid white" src="https://firebasestorage.googleapis.com/v0/b/centrumgier-a08cf.appspot.com/o/pfp%2Fdefault.jpg?alt=media" alt="">
                        @endif
                        {{$ranking['user']->displayName}}
                    </td> 
                    <td style="padding-top: 5.1%; padding-left: 4%;"> 
                        {{$ranking['points']}} 
                    </td> 
                </tr>          
                @endforeach
            </table>
            @else
            <div class="card-body">
                <p class="text-center">Brak danych w rankingu dla tej gry.</p>
            </div>
            @endif
            @if(!empty($game['topThreeRankings']))
                <!-- Przycisk przenoszący do /rankings/{{$key}} -->
                     <div class="card-footer d-flex justify-content-center">
                <a href="/rankings/{{$key}}"  class="btn btn-warning">Zobacz pełny ranking</a>
            </div>
            @else
            @endif
        </div>
    </div>
    @endforeach
</div>
</div>
@endsection
