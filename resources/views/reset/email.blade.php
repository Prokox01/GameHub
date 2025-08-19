@extends('layouts.app')

@section('content')
<div class="container" >
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="box-shadow: 2px 2px 15px 5px orange;">
                <div class="card-header">Zweryfikuj swój adres e-mail</div>

                <div class="card-body">
                  @if (session('resent'))
                      <div class="alert alert-success" role="alert">
                          {{ __('Link został wysłany na twój adres e-mail') }}
                      </div>
                  @endif
                  @if(Session::has('error'))
                    <p class=" pb-3 alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('error') }}</p>
                  @endif

                   Przed przejściem dalej sprawdź czy otrzymałeś email.
                   Jeżeli nie otrzymałeś

                        <form class="d-inline" method="POST" action="App\Http\Controllers\Auth\ResetController@verify">
                            @csrf
                            <button type="submit" class="btn btn-link p-0 m-0 align-baseline" style="text-decoration:none;">{{ __('Kliknij tutaj żeby otrzymać nowy') }}</button>.
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
