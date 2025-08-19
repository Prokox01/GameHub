@extends('layouts.app')

@section('content')
<div class="container bg-light md-12 ml-12" style="margin-top: 50px; box-shadow: 2px 2px 15px 5px white; border-radius:25px;">

    <h4 class="text-center">Zarządzaj osiągnięciami graczy</h4><br>

    <h5>Edytuj wybrane osiągnięcie dla gracza</h5>
    <div class="card card-default">
        <div class="card-body p-5">
        <form id="editachivment"  action="{{url('update-achivuser/'.$key)}}" enctype="multipart/form-data" method="POST">
            @csrf
            @method('PUT')
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
                @if($u->uid == $editdata['uid'])
                <option value="{{$u->uid}}" selected>
                    {{$u->displayName}}
                </option>
                @else
                <option value="{{$u->uid}}">
                    {{$u->displayName}}
                </option>
                @endif
                @endforeach
            </select>
            <br>
            <select name="achivment" class="form-control">
                    @foreach($achivments as $key2 => $g)
                        @if($key2 == $editdata['aid'])
                        <option value="{{ $key2 }}" selected>
                            {{$g['name']}}
                        </option>
                        @else
                        <option value="{{ $key2 }}">
                            {{$g['name']}}
                        </option>
                        @endif
                    @endforeach
                </select><br>
                <button id="submitCustomer" type="submit" class="btn btn-warning mb-2">Zamień</button>
            </form>
        </div>
    </div>
    <br>
@endsection