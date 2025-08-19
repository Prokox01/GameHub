@extends('layouts.app')

@section('content')
<div class="container bg-light md-12 ml-12" style="margin-top: 50px; box-shadow: 2px 2px 15px 5px white; border-radius:25px;">

    <h4 class="text-center">Zarządzaj osiągnięciami graczy</h4><br>

    <h5>Dodaj nowe osiągnięcie dla gracza</h5>
    <div class="card card-default">
        <div class="card-body p-5">
            <form id="addachivment" method="POST" action="{{ url('add-achivuser') }}" enctype="multipart/form-data">
            @csrf
            @php
            $gracz = [];
            @endphp
            <select name="user" class="form-control">
                @foreach($users as $key3 => $u)
                @php
                    $gracz[] = [
                    'uid' => $u->uid,
                    'displayName' => $u->displayName,
                    ];
                @endphp
                <option value="{{$u->uid}}">
                    {{$u->displayName}}
                </option>
                @endforeach
            </select>
            <br>
            <select name="achivment" class="form-control">
                    @foreach($achivments as $key2 => $g)
                        <option value="{{ $key2 }}">
                            {{$g['name']}}
                        </option>
                    @endforeach
                </select><br>
                <button id="submitCustomer" type="submit" class="btn btn-warning mb-2">Dodaj</button>
            </form>
        </div>
    </div>

    <br>

    <h5>Zarządzaj obecnymi osiągnięciami graczy</h5>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>osiągnięcie</th>
                    <th>Gracz</th>
                    <th>Data</th>
                    <th>Godzina</th>
                    <th class="text-center">Operacje</th>
                </tr>
            </thead>
            <tbody>
                 @if($hisPaginated)
                    @foreach($hisPaginated as $key => $a)
                        <tr>
                            <td>{{$key}}</td>
                            <td>
                                @foreach($achivments as $key2 => $g)
                                    @if($key2==$a['aid'])
                                        <img style="width: 50px; height: 50px; border-radius:180px; border: 5px solid white" src="{{$g['image']}}" alt="">
                                        {{$g['name']}}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                             @foreach($gracz as $key2 => $u)
                              @if($u['uid']==$a['uid'])
                                {{$u['displayName']}}
                              @endif
                            @endforeach
                            </td> 
                            <td>
                            {{$a['date']}}
                            </td>  
                            <td>{{$a['time']}}</td>  
                            <td>
                                <a class="btn btn-warning" href="edit/achivuser/{{$key}}">Edytuj</a>
                                <a class="btn btn-danger" onclick="return confirmDelete()" href="delete/achivuser/{{$key}}">Usuń</a>
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