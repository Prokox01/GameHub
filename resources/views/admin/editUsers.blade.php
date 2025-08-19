@extends('layouts.app')
@section('content')
<div class="container bg-light" style="margin-top: 50px; box-shadow: 2px 2px 15px 5px white; border-radius:25px;">

<h4 class="text-center ">Zarządaj użytkownikami</h4><br>
<h5>Edytuj użytkownika</h5>
<div class="card card-default">
    <div class="card-body p-5">
        <form method="POST" action="{{url('update-user/'.$user->uid)}}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
            <br>
            <input value="{{$user->displayName}}"id="name"  type="text" class="form-control" name="name" placeholder="Nazwa użytkownika" required ><br>
            <input value="{{$user->email}}" id="email"  type="text" class="form-control" name="email" placeholder="Adres e-mail" required ><br>
            <input  id="pass"  type="password" class="form-control" name="pass" placeholder="Hasło"><br>
            {!! Form::file('image', null,['class'=>'form-control']) !!}<br><br>
            <button  type="submit"  class="btn btn-warning mb-2">Zmień</button>
        </form>
        <a  href="/admin/users" class="btn btn-danger mb-2">Porzuć</a>
    </div>
</div>

<br>



@endsection