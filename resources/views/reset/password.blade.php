@extends('layouts.app')

@section('content')

  <div class="container" >
    <div class="row justify-content-center"  >
      <div class="col-md-8" >
        <div class="card" style="box-shadow: 2px 2px 15px 5px orange;">
          <div class="card-header">Zresetuj swoje hasło</div>
          <div class="card-body" >

            @if(Session::has('message'))
              <div class="alert alert-info alert-dismissible fade show">
                {{ Session::get('message') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
              </div>
            @endif

            @if(Session::has('error'))
              <div class="alert alert-danger alert-dismissible fade show">
                {{ Session::get('error') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
              </div>
            @endif

            @if ($errors->any())
              @foreach ($errors->all() as $error)
                <div class="alert alert-danger alert-dismissible fade show">
                  {{ $error }}
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
              @endforeach
            @endif

            {!! Form::open(['method'=>'POST', 'action'=> 'App\Http\Controllers\Auth\ResetController@store']) !!}

            <div class="form-group">
              {!! Form::label('email', 'Adres e-mail:') !!}
              {!! Form::email('email', null, ['class'=>'form-control'])!!}
            </div>


            <div class="form-group">
              {!! Form::submit('Wyślij e-mail', ['class'=>'btn btn-warning']) !!}
            </div>

            {!! Form::close() !!}

          </div>

        </div>
      </div>
    </div>
  </div>
@endsection
