@extends('layouts.app')
@section('content')
<div class="container bg-light" style="margin-top: 50px; box-shadow: 2px 2px 15px 5px white; border-radius:25px;">

    <h4 class="text-center">Zarządaj Historią</h4><br>

<h5>Edytuj historię gry</h5>
<div class="card card-default">
    <div class="card-body p-5">
        <form method="POST" action="{{url('update-history/'.$key)}}">
        @csrf
        @method('PUT')
            <select name="game" class="form-control">
                @foreach($games as $key2 => $g)
                @if($key2 == $editdata['gid'])
                    <option value="{{ $key2 }}" selected>
                        {{ $g['name'] }}
                    </option>
                    @else
                    <option value="{{ $key2 }}">
                        {{ $g['name'] }}
                    </option>
                    @endif
                    @endforeach
                </select>
                <br>
                <select name="user" class="form-control">
                    @foreach($users as $key3 => $u)
                    @if($u->uid == $editdata['uid'])
                    <option value="{{ $u->uid }}" selected>
                        {{ $u->displayName }}
                    </option>
                    @else
                    <option value="{{ $u->uid }}">
                        {{ $u->displayName }}
                    </option>
                    @endif
                    @endforeach
                </select>
                <br>
                <input id="score" type="text" class="form-control" name="score" placeholder="Wynik gry" required value="{{ $editdata['score'] }}"><br>
                <button type="submit" class="btn btn-warning mb-2">Zmień</button>
                <a href="/admin/history" class="btn btn-danger mb-2">Porzuć</a>
            </form>
        </div>
    </div>

    <br>

    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            @if ($page > 1)
            <li class="page-item">
                <a class="page-link" href="?page={{ $page - 1 }}#bottom">Poprzednia</a>
            </li>
            @endif

            @for ($i = 1; $i <= $totalPages; $i++)
            <li class="page-item {{ $i == $page ? 'active' : '' }}">
                <a class="page-link" href="?page={{ $i }}#bottom">{{ $i }}</a>
            </li>
            @endfor

            @if ($page < $totalPages)
            <li class="page-item">
                <a class="page-link" href="?page={{ $page + 1 }}#bottom">Następna</a>
            </li>
            @endif
        </ul>
    </nav>

</div>

@endsection
