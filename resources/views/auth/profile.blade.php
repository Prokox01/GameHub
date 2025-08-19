@extends('layouts.app')

@section('content')
<br>
  <div class="container " style="box-shadow: 2px 2px 15px 5px orange; border-radius:25px;">
    @if(Session::has('message'))
      <div class="alert alert-info alert-dismissible fade show" role="alert">
        {{ Session::get('message') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    @if ($errors->any())
        @foreach ($errors->all() as $error)
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $error }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        @endforeach
    @endif

    @if(Session::has('error'))
      <div class="alert alert-danger alert-dismissible fade show">
        {{ Session::get('error') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
    @endif
    <div class="row justify-content-center ">
    <div class="col-lg-4 text-light">
      <h4>Zdjęcie profilowe</code></h5>
        @if($user->photoUrl != NULL)
          <img style="width: 150px; height: 150px; border-radius:180px; padding-bottom: 5px; border: 5px solid white" src="{{$user->photoUrl}}" alt="">
        @else
          <img style="width: 150px; height: 150px; border-radius:180px; padding-bottom: 5px; border: 5px solid white" src="https://firebasestorage.googleapis.com/v0/b/centrumgier-a08cf.appspot.com/o/pfp%2Fdefault.jpg?alt=media" alt="">
        @endif
        </div>
      

        <div class="col-lg-8 text-center pt-0 ">
          <div class="card py-4 mb-5 mt-md-3 bg-white rounded " style="box-shadow: 2px 2px 15px 5px white;">

            <div class="form-group px-3">

             <label class="col-12 text-left pl-0">Nowe zdjęcie profilowe</label>
              
            </div>

            <div class="form-group row mb-0 mr-4">
              <div class="col-md-8 offset-md-4 text-right">
              {!! Form::open(['action' => 'App\Http\Controllers\ImageController@store', 'method' => 'POST' , 'files'=>true]) !!}

                  <br>
                  {!! Form::file('image', ['class' => 'form-control', 'required' => 'required', 'accept' => 'image/*']) !!}
         

    
                  {!! Form::submit('Zmień', ['class'=>'btn btn-warning']) !!}
            

                {!! Form::close() !!}
              </div>
            </div>

          </div>
        </div>

      </div>
      <div class="border-bottom border-grey"></div>
      
    <div class="row justify-content-center ">
      <div class="col-lg-4 text-light">
        <h4>Informacje o profilu</code></h5>
          <span class="text-justify mb-3 " style="padding-top:-3px;">Uaktualnij swoje informacje na profilu jak nazwa czy e-mail.<br><br> Kiedy zmienisz e-mail muszisz to potwierdzić na mailu, inaczej zablokujemy twoje konto</span>
        </div>

        <div class="col-lg-8 text-center pt-0 ">
          <div class="card py-4 mb-5 mt-md-3 bg-white rounded " style="box-shadow: 2px 2px 15px 5px white;">

            {!! Form::model($user, ['method'=>'PATCH', 'action'=> ['App\Http\Controllers\Auth\ProfileController@update',$user->uid]]) !!}
            {!! Form::open() !!}

            <div class="form-group px-3">
              {!! Form::label('displayName', 'Nazwa ',['class'=>'col-12 text-left pl-0']) !!}
              {!! Form::text('displayName', null, ['class'=>' col-md-8 form-control'])!!}
             

              {!! Form::label('email', 'E-mail ',['class'=>'pt-3 col-12 text-left pl-0']) !!}
              {!! Form::email('email', null, ['class'=>'col-md-8 form-control'])!!}

            </div>

            <div class="form-group row mb-0 mr-4">
              <div class="col-md-8 offset-md-4 text-right">
                {!! Form::submit('Zapisz', ['class'=>'btn btn-warning']) !!}
              </div>
            </div>

          </div>
        </div>

      </div>
      <div class="border-bottom border-grey"></div>

      <div class="row justify-content-center pt-5 " >
        <div class="col-lg-4 text-light">
          <h4>Zmiana hasła</code></h5>
            <span class="text-justify" style="padding-top:-3px;">Upewnij się że twoje hasło spełnia wymagnia bezpieczeństwa.</span>
          </div>

          <div class="col-lg-8 text-center pt-0">
            <div class="card py-4 mb-5 mt-md-3 bg-white rounded" style="box-shadow: 2px 2px 15px 5px white;">

              <div class="form-group px-3">
                {!! Form::label('new_password', 'Nowe hasło:',['class'=>'col-12 text-left pl-0']) !!}
                {!! Form::password('new_password', ['class'=>'col-md-8 form-control'])!!}
              </div>

              <div class="form-group px-3">
                {!! Form::label('new_confirm_password', 'Potwierdź hasło:',['class'=>'col-12 text-left pl-0']) !!}
                {!! Form::password('new_confirm_password', ['class'=>'col-md-8 form-control'])!!}
              </div>

              <div class="form-group row mb-0 mr-4">
                <div class="col-md-8 offset-md-4 text-right">
                  {!! Form::submit('Zapisz', ['class'=>'btn btn-warning']) !!}
                </div>
              </div>
              {!! Form::close() !!}
            </div>
          </div>

        </div>

        <div class="border-bottom border-grey"></div>

        <div class="row justify-content-center pt-5">
          <div class="col-lg-4 text-light">
            <h4>Delete Account</code></h5>
              <span class="text-justify" style="padding-top:-3px;">Usuwa twoje konto na stałe.</span>
            </div>

            <div class="col-lg-8 pt-0">
              <div class="card py-4 mb-5 mt-md-3 bg-white rounded" style="box-shadow: 2px 2px 15px 5px white;">
                <div class="text-left px-3">
                  Usunięcie twojego konta poskutkuje usunięciem wszystkich powiązanych danych. Upewnij się że chcesz to zrobić.
                </div>

                {!! Form::open(['method'=>'DELETE', 'action' =>['App\Http\Controllers\Auth\ProfileController@destroy',$user->uid],'onsubmit'=>'return confirmDelete()' ])!!}
                {!! Form::open() !!}
                <div class="form-group row mb-0 mr-4 pt-4 px-3">
                  <div class="col-md-8 offset-l-4 text-left">
                    {!! Form::submit('Usuń konto', ['class'=>'btn btn-danger pl-3']) !!}
                  </div>
                </div>
                {!! Form::close() !!}
              </div>
            </div>

          </div>

        </div>

      @endsection
