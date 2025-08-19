@extends('layouts.app')

@section('content')
<br>
  <div class="container " style="box-shadow: 2px 2px 15px 5px orange; border-radius:25px;">
  <br>
    <div class="row justify-content-center ">
    <div class="col-lg-4 text-light">
      <h4>Zdjęcie profilowe</code></h5>
        @if($user->photoUrl != NULL)
          <img style="width: 150px; height: 150px; border-radius:180px; padding-bottom: 5px; border: 5px solid white" src="{{$user->photoUrl}}" alt="">
        @else
          <img style="width: 150px; height: 150px; border-radius:180px; padding-bottom: 5px; border: 5px solid white" src="https://firebasestorage.googleapis.com/v0/b/centrumgier-a08cf.appspot.com/o/pfp%2Fdefault.jpg?alt=media" alt="">
        @endif
        </div>
      

        <div class="col-lg-8 text-center pt-0" style="color: white;">
        <h4>Nazwa użytkownika</code></h5>
          <div class="card py-4 mb-5 mt-md-3 bg-white rounded " style="box-shadow: 2px 2px 15px 5px white; color: black;">

            <div class="form-group px-3">

             <h1 class=" col-12 text-center pl-0">{{$user->displayName}}</h1>
            </div>

          </div>
        @if($user->uid == Session::get('uid'))
          <div class="text-center">
                <a class="btn btn-warning" href="/profile">Zarządzaj kontem</a>
          </div>
          </BR>
        @endif
        </div>
      </div>
      <div class="border-bottom border-grey"></div>
      <BR></BR>
    <div class="row justify-content-center ">
      <div class="col-lg-4 text-light">
        <h4>Historia gier</code></h5>
          <span class="text-justify mb-3 " style="padding-top:-3px;">Sprawdzisz tutaj ostatnio rozegrane gry. Jaki i ich wyniki</span>
        </div>

        <div class="col-lg-8 text-center pt-0 ">
          <div class="card py-4 mb-5 mt-md-3 bg-white rounded " style="box-shadow: 2px 2px 15px 5px white;">
            <div class="form-group px-3">
                <table class="table" style="margin: 15px; border-collapse: separate; padding: 30px;">
                        <tr>
                            <th>Gra</th>
                            <th>Data</th>
                            <th>Godzina</th>
                            <th>Wynik</th>
                        </tr>
                    @if($his)
                    @foreach($his as $key => $h)
                    <tr>
                        <td>
                            @foreach($games as $key2 => $g)
                              @if($key2==$h['gid'])
                                <img style="width: 50px; height: 50px; border-radius:180px; padding-bottom: 5px; border: 5px solid white" src="{{$g['image']}}" alt="">
                                {{$g['name']}}
                              @endif
                            @endforeach
                        </td>
                        <td>
                            {{$h['date']}}
                        </td>  
                        <td>{{$h['time']}}</td>  
                        <td>{{$h['score']}}</td> 
                    </tr>
                    @endforeach
                </table>
                @else
                </table>
                <h1>Brak gier!!</h1>
                @endif
            </div>
            <div class="form-group row mb-0 mr-4">
              <div class="col-md-8 offset-md-4 text-right">
                <a href="/history/{{$user->uid}}" class="btn btn-warning">Zobacz całą</a>
              </div>
            </div>

          </div>
        </div>

      </div>
      <div class="border-bottom border-grey"></div>

      <div class="row justify-content-center pt-5 " >
        <div class="col-lg-4 text-light">
          <h4>Osiągnięcia</code></h5>
            <span class="text-justify" style="padding-top:-3px;">W tym miejscu możesz zobaczyć zdobyte osiągnięcia.</span>
          </div>

          <div class="col-lg-8 text-center pt-0">
            <div class="card py-4 mb-5 mt-md-3 bg-white rounded" style="box-shadow: 2px 2px 15px 5px white;">
            <div class="form-group px-3 ">
                <table class="table" style="margin: 15px; border-collapse: separate; padding: 30px;">
                        <tr>
                            <th>Osiągnięcie</th>
                            <th>Nazwa</th>
                            <th>Data</th>
                            <th>Godzina</th>
                        </tr>
                    @if($userachiv)
                    @foreach($userachiv as $key => $h)
                    <tr>
                          @foreach($achivments as $key2 => $g)
                              @if($key2==$h['aid'])
                              <td>
                                <img style="width: 100px; height: 100px; border-radius:180px; padding-bottom: 5px; border: 5px solid white" src="{{$g['image']}}" alt="">
                              </td>
                              <td>
                                {{$g['name']}}
                              </td>
                              @endif
                            @endforeach
                       
                        <td>
                            {{$h['date']}}
                        </td>  
                        <td>{{$h['time']}}</td>  
                    </tr>
                    @endforeach
                </table>
                @else
                </table>
                <h1>Brak osiągnięć!!</h1>
                @endif
            </div>
            <div class="col-md-8 offset-md-4 text-right">
            <a href="/achivments/{{$user->uid}}" class="btn btn-warning">Pokaż wszystkie</a>
            </div>
            </div>
          </div>

        </div>


      @endsection
