@extends('layouts.app')

@section('content')
<div class="container">
    
    <!-- Slider z grami -->
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
  <ol class="carousel-indicators">
    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="4"></li>
  </ol>
  <div class="carousel-inner">
  @php
    $z=0;
  @endphp
  @foreach($games as $key => $game)

                <div class="carousel-item {{ $z ==0  ? 'active' : '' }}">
                <img src="{{ $game['image'] }}" class="d-block w-100" alt="{{ $game['name'] }}" style="height: 500px;  object-fit: contain;">

                    <div class="carousel-caption d-none d-md-block">
                        <h5>{{ $game['name'] }}</h5>
                    </div>
                </div>
             @php
                $z++;
             @endphp 
            @endforeach
  </div>
  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
    
    <!-- Panel z informacjami -->
    <div class="row mt-5">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">O nas</h5>
                    <p class="card-text">GameHub to platforma, która umożliwia dostęp do szerokiego wyboru gier online. Znajdziesz tutaj zarówno klasyczne tytuły, jak i najnowsze przeboje. Dołącz do naszej społeczności i ciesz się grami razem z nami!</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Jak zacząć?</h5>
                    <p class="card-text">Aby zacząć korzystać z GameHub, wystarczy założyć darmowe konto. Po zalogowaniu będziesz mógł przeglądać dostępne gry, zbierać osiągnięcia i rywalizować z innymi graczami.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Kontakt</h5>
                    <p class="card-text">Masz pytania lub sugestie? Skontaktuj się z nami poprzez formularz kontaktowy na naszej stronie lub wysyłając e-mail na adres: kontakt@gamehub.com.</p>
                    <a href="/contact" class="btn btn-warning">Skontaktuj się</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
