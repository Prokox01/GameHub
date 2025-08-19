@extends('layouts.app')
@section('content')

<div class="container bg-light" style="margin-top: 50px; box-shadow: 2px 2px 15px 5px white; border-radius:25px;">

<h4 class="text-center ">Zarządaj użytkownikami</h4><br>
<h5>Dodaj nowego użytkownika</h5>
<div class="card card-default">
    <div class="card-body p-5">
    @if(Session::has('error'))              
              <div class="alert alert-danger alert-dismissible fade show">
                {{ Session::get('error') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
              </div>
    @endif
        <form method="POST" action="{{url('adduser')}}" enctype="multipart/form-data">
        @csrf
            <br>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
            <input id="name"  type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Nazwa użytkownika" required ><br>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
            <input id="email"  type="text" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Adres e-mail" required ><br>

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
            <input id="pass"  type="password" class="form-control @error('password') is-invalid @enderror" name="pass" placeholder="Hasło" required ><br>

            {!! Form::file('image', ['class' => 'form-control', 'required' => 'required', 'accept' => 'image/*']) !!} <br>
            <button  type="submit"  class="btn btn-warning mb-2">Dodaj</button>
        </form>
    </div>
</div>

<br>

<h5>Zarządaj obecnymi użytkownikami</h5>
<div class="table-responsive">
<table class="table table-bordered" style=" border-collapse: separate; padding: 30px;">
    <tr>
        <th>UID</th>
        <th>Zdjęcie</th>
        <th>E-mail</th>
        <th>Nazwa</th>
        <th>Operacje</th>
    </tr>
    
    @if($usersPaginated)
        @foreach($usersPaginated as $key3 => $u)
            <tr>
                <td>{{ $u->uid }}</td>
                <td>
                    @if($u->photoUrl != NULL)
                        <img style="width: 50px; height: 50px; border-radius:180px; " src="{{ $u->photoUrl }}" alt="">
                    @else
                        <img style="width: 50px; height: 50px; border-radius:180px; " src="https://firebasestorage.googleapis.com/v0/b/centrumgier-a08cf.appspot.com/o/pfp%2Fdefault.jpg?alt=media" alt="">
                    @endif
                </td> 
                <td>{{ $u->email }}</td> 
                <td>{{ $u->displayName }}</td>
                <td>
                    <a class="btn btn-warning" href="edit/user/{{ $u->uid }}">Edytuj</a>
                    <a class="btn btn-danger" onclick="return confirmDelete()" href="delete/user/{{ $u->uid }}">Usuń</a>
                </td>  
            </tr>
        @endforeach
    </table>
    </div>
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center flex-wrap d-flex">
            @if ($page > 1)
                <li class="page-item">
                    <a class="page-link" href="?page={{ $page - 1 }}">Poprzednia</a>
                </li>
            @endif

            @for ($i = 1; $i <= $totalPages; $i++)
                <li class="page-item {{ $i == $page ? 'active' : '' }}">
                    <a class="page-link" href="?page={{ $i }}" >{{ $i }}</a>
                </li>
            @endfor

            @if ($page < $totalPages)
                <li class="page-item">
                    <a class="page-link" href="?page={{ $page + 1 }}"  >Następna</a>
                </li>
            @endif
        </ul>
    </nav>
@else
    </table>
    <h1>Brak Użytkowników!!</h1>
@endif

</div>


@endsection