@extends('layouts.app')

@section('content')
    <div class="container bg-light" style="box-shadow: 2px 2px 15px 5px white; border-radius:25px">
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-center">Pełny ranking gry {{ $game['name'] }}</h1>

                @if(!empty($hisPaginated))
                    <table class="table">
                        <tr>
                            <th>Miejsce</th>
                            <th>Gracz</th>
                            <th>Wynik</th>
                            <th>Data</th>
                            <th>Czas</th>
                        </tr>
                        @foreach($hisPaginated as $index => $ranking)
                            <tr>
                    <td>
   @php
        $actualIndex = (($index-1)+$page*10)-8 ;
    @endphp
    @if ($actualIndex <= 3)
        <img src="https://firebasestorage.googleapis.com/v0/b/centrumgier-a08cf.appspot.com/o/Images%2F{{ $actualIndex }}.png?alt=media" class="img-fluid" style="width: 50px;">
    @else
    <p style=" padding-left: 12%; padding-top: 8%;">
        {{ $actualIndex }}
    </p>
    @endif
</td>
                    </td>
                                <td>
                                    @if($ranking['user']->photoUrl != NULL)
                                        <img style="width: 50px; height: 50px; border-radius:180px;" src="{{ $ranking['user']->photoUrl }}" alt="">
                                    @else
                                        <img style="width: 50px; height: 50px; border-radius:180px; border: 5px solid white" src="https://firebasestorage.googleapis.com/v0/b/centrumgier-a08cf.appspot.com/o/pfp%2Fdefault.jpg?alt=media" alt="">
                                    @endif
                                    {{ $ranking['user']->displayName }}
                                </td>
                                <td style=" padding-left: 2%;">{{ $ranking['points'] }}</td>
                                <td>{{ $ranking['date'] }}</td>
                                <td>{{ $ranking['time'] }}</td>
                            </tr>
                        @endforeach
                    </table>
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
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
                    <div class="card-body">
                        <p class="text-center">Brak danych w rankingu dla tej gry.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
