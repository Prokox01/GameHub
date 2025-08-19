@extends('layouts.app')

@section('content')
<div class="container bg-light md-12 ml-12" style="margin-top: 50px; box-shadow: 2px 2px 15px 5px white; border-radius:25px;">

    <h4 class="text-center">Zarządzaj osiągnięciami</h4><br>

    <h5>Dodaj nowe osiągnięcie</h5>
    <div class="card card-default">
        <div class="card-body p-5">
            <form id="addachivment" method="POST" action="{{ url('add-achivment') }}" enctype="multipart/form-data">
                @csrf
                <input id="name" type="text" class="form-control" name="name" placeholder="Nazwa" required autofocus><br>
                <select name="game" class="form-control">
                    @foreach($games as $key2 => $g)
                        <option value="{{ $key2 }}">
                            {{$g['name']}}
                        </option>
                    @endforeach
                </select><br>
                {!! Form::file('image', ['class' => 'form-control', 'required' => 'required', 'accept' => 'image/*']) !!}<br>
                <textarea id="desc" class="form-control" rows="3" name="desc" placeholder="Opis osiągnięcia" required></textarea><br>
                <button id="submitCustomer" type="submit" class="btn btn-warning mb-2">Dodaj</button>
            </form>
        </div>
    </div>

    <br>

    <h5>Zarządzaj obecnymi osiągnięciami</h5>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Gra</th>
                    <th>Nazwa</th>
                    <th>Zdjęcie</th>
                    <th>Opis</th>
                    <th class="text-center">Operacje</th>
                </tr>
            </thead>
            <tbody>
                 @if($hisPaginated)
                    @foreach($hisPaginated as $key => $a)
                        <tr>
                            <td>{{$key}}</td>
                            <td>
                                @foreach($games as $key2 => $g)
                                    @if($key2==$a['gid'])
                                        <img style="width: 50px; height: 50px; border-radius:180px;  border: 5px solid white" src="{{$g['image']}}" alt="">
                                        {{$g['name']}}
                                    @endif
                                @endforeach
                            </td>
                            <td>{{$a['name']}}</td>
                            <td>
                                <img style="width: 50px; height: 50px; border-radius:180px; border: 5px solid white" src="{{$a['image']}}" alt="">
                            </td>
                            <td style="word-wrap: break-word; max-height: 100px; max-width: 200px;">
                            <div class="overflow-auto" style="word-wrap: break-word; max-height: 100px;">
                                {{$a['desc']}}
                            </div>
                            <td>
                                <a class="btn btn-warning" href="edit/achivments/{{$key}}">Edytuj</a>
                                <a class="btn btn-danger" onclick="return confirmDelete()" href="delete/achivments/{{$key}}">Usuń</a>
                            </td>
                        </tr>
                    @endforeach
                   
                @else
                    <tr>
                        <td colspan="6">
                            <h1 class="text-center">Brak osiągnięć!!</h1>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
         <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center flex-wrap d-flex">
            @if ($page > 1)
            <li class="page-item">
                <a class="page-link" href="?page={{ $page - 1 }}">Poprzednia</a>
            </li>
            @endif

            @for ($i = 1; $i <= $totalPages; $i++)
            <li class="page-item {{ $i == $page ? 'active' : '' }}">
                <a class="page-link" href="?page={{ $i }}">{{ $i }}</a>
            </li>
            @endfor

            @if ($page < $totalPages)
            <li class="page-item">
                <a class="page-link" href="?page={{ $page + 1 }}">Następna</a>
            </li>
            @endif
        </ul>
    </nav>
    </div>
</div>
@endsection