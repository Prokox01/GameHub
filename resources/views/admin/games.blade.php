@extends('layouts.app')
@section('content')
<div class="container bg-light" style="margin-top: 50px; box-shadow: 2px 2px 15px 5px white; border-radius:25px;">

<h4 class="text-center ">Zarządaj grami</h4><br>

<h5>Dodaj nową gre</h5>
<div class="card card-default">
    <div class="card-body p-5">
        <form id="addgame"  method="POST" action="{{url('add-game')}}" enctype="multipart/form-data">
            @csrf

            <input id="name" type="text" class="form-control" name="name" placeholder="Nazwa" required autofocus><br>
            {!! Form::file('image', ['class' => 'form-control', 'required' => 'required', 'accept' => 'image/*']) !!}<br>
            <textarea id="desc" class="form-control" rows="3" name="desc" placeholder="Opis gry" required></textarea><br>
            <input id="link"  type="text" class="form-control" name="link" placeholder="Link do gry"  ><br>
            <button  id="submitCustomer" type="submit"  class="btn btn-warning mb-2">Dodaj</button>
        </form>
    </div>
</div>

<br>

<h5>Zarządaj obecnymi grami</h5>
<div class="table-responsive">
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>Nazwa</th>
        <th>Zdjęcie</th>
        <th>Opis</th>
        <th>Link do gry</th>
        <th width="180" class="text-center">Operacje</th>
    </tr>
    @if($games)
        @foreach($games as $key => $game)
        <tr>
            <td>{{$key}}</td>
            <td>{{$game['name']}}</td>
            <td><img style="width: 50px; height: 50px; border-radius:180px;" src="{{$game['image']}}" alt=""></td>
            <td>
                <div class="overflow-auto" style="word-wrap: break-word; max-height: 100px; max-width: 200px;">
                {{$game['desc']}}
                </div>
            </td>  
            <td>{{$game['link']}}</td>  
            <td>
                <a class="btn btn-warning" href="edit/game/{{$key}}">Edytuj</a>
                <a class="btn btn-danger" onclick="return confirmDelete()" href="delete/game/{{$key}}">Usuń</a>
            </td>
        </tr>
        @endforeach

    @else
    <h1>Brak gier!!</h1>
     @endif

</table>
<</div>
</div>



@endsection