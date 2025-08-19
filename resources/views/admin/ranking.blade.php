@extends('layouts.app')
@section('content')
<div class="container bg-light" style="margin-top: 50px; box-shadow: 2px 2px 15px 5px white; border-radius:25px;">

<h4 class="text-center ">Zarządaj Wynikami rankingu</h4><br>
<h5>Dodaj nowy Ranking</h5>
<div class="card card-default">
    <div class="card-body p-5">
        <form method="POST" action="{{url('addranking')}}">
        @csrf
            <select name="game" class="form-control">
                @foreach($games as $key2 => $g)
                    <option value="{{ $key2 }}">
                    {{$g['name']}}
                    </option>
                @endforeach
            </select>
            <br>
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
            <input id="score"  type="text" class="form-control" name="score" placeholder="Wynik gry" required ><br>
            <button  type="submit"  class="btn btn-warning mb-2">Dodaj</button>
        </form>
    </div>
</div>

<br>

<h5>Zarządaj obecnymi rankingami</h5>
<div class="table-responsive">
<table class="table table-bordered" style="border-collapse: separate; padding: 30px;">
                    <tr>
                            <th>ID</th>
                            <th>Gra</th>
                            <th>Gracz</th>
                            <th>Data</th>
                            <th>Godzina</th>
                            <th>Wynik</th>
                            <th>Operacje</th>
                    </tr>
                    @if($hisPaginated)
                    @foreach($hisPaginated as $key => $h)
                    <tr>
                        <td>
                            {{$key}}
                        </td> 
                        <td>
                            @foreach($games as $key2 => $g)
                              @if($key2==$h['gid'])
                                <img style="width: 50px; height: 50px; border-radius:180px; border: 5px solid white" src="{{$g['image']}}" alt="">
                                {{$g['name']}}
                              @endif
                            @endforeach
                        </td>
                        <td>
                        @foreach($gracz as $key2 => $u)
                              @if($u['uid']==$h['uid'])
                                {{$u['displayName']}}
                              @endif
                            @endforeach
                        </td> 
                        <td>
                            {{$h['date']}}
                        </td>  
                        <td>{{$h['time']}}</td>  
                        <td>{{$h['points']}}</td>
                        <td>
                            <a class="btn btn-warning" href="edit/ranking/{{$key}}">Edytuj</a>
                            <a class="btn btn-danger" onclick="return confirmDelete()" href="delete/ranking/{{$key}}">Usuń</a>
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
                @else
                </table>
                <h1>Brak rankingów!!</h1>
                @endif

</div>


@endsection