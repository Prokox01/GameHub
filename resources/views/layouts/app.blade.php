<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<style>
    /* Dla paska przewijania pionowego */
::-webkit-scrollbar {
  width: 10px; /* Szerokość paska przewijania */
}

::-webkit-scrollbar-thumb {
  background-color: #888; /* Kolor wypełnienia paska przewijania */
  border-radius: 5px; /* Zaokrąglenie narożników */
}

/* Dla paska przewijania poziomego */
::-webkit-scrollbar-track {
  background-color: #f1f1f1; /* Kolor tła ścieżki przewijania */
}

::-webkit-scrollbar-thumb {
  background-color: #555; /* Kolor wypełnienia paska przewijania */
}

/* Dla obszaru przewijania, gdy przycisk nie jest wciśnięty */
::-webkit-scrollbar-thumb:hover {
  background-color: #777; /* Kolor wypełnienia paska przewijania po najechaniu myszką */
}
.contrast-mode, .contrast-mode * {
    color: inherit !important; /* Użycie dziedziczenia koloru dla wszystkich elementów */
    border-color: inherit !important;
}
.contrast-mode {
    background-color: #fff !important; /* Kolor tła w trybie kontrastowym */
    color: #000 !important; /* Kolor tekstu w trybie kontrastowym */
    border-color: #000 !important;
}
.contrast-mode canvas {
    outline-color: invert(100%); /* Inwersja koloru krawędzi canvas */
    outline-style: solid; /* Styl linii outline */
    outline-width: 1px; /* Grubość linii outline */
}
</style>
<script>
    function confirmDelete() {
        return confirm("Czy na pewno chcesz usunąć?");
    }
    </script>
<script>
    // Funkcja obsługująca zdarzenia klawiatury
    function preventArrowKeyScrolling(event) {
        // Sprawdzanie, czy naciśnięty klawisz to strzałka
        if (event.keyCode >= 37 && event.keyCode <= 40) {
            // Jeśli tak, zapobiegaj domyślnemu zachowaniu (przewijanie strony)
            event.preventDefault();
        }
    }

    // Dodawanie nasłuchiwacza zdarzeń klawiatury do całego dokumentu
    document.addEventListener("keydown", preventArrowKeyScrolling);
</script>
<script>
    $(document).ready(function () {
    const body = $('body');
    const contrastButton = $('#contrastButton');
    const fontSizeButton = $('#fontSizeButton');
    let z = parseInt(localStorage.getItem('fontSizeLevel')) || 3;

    function toggleContrast() {
        const isContrastMode = body.hasClass('contrast-mode');

        if (isContrastMode) {
            body.removeClass('contrast-mode');
            localStorage.setItem('contrastMode', 'off');
        } else {
            body.addClass('contrast-mode');
            localStorage.setItem('contrastMode', 'on');
        }
    }

    function increaseFontSize() {
        if (z < 9) {
            z++;
            const currentFontSize = parseFloat(body.css('font-size'));
            const newFontSize = currentFontSize + 3;
            body.css('font-size', newFontSize + 'px');
            localStorage.setItem('fontSizeLevel', z);
        }
    }

    function restoreFontSize() {
        if (z > 6) {
            z--;
            const currentFontSize = parseFloat(body.css('font-size'));
            const newFontSize = currentFontSize - 3;
            body.css('font-size', newFontSize + 'px');
            localStorage.setItem('fontSizeLevel', z);
        }
    }    

    // Inicjalizacja rozmiaru czcionki
    body.css('font-size', (z * 3) + 'px');

            contrastButton.on('click', toggleContrast);
            fontSizeButton.on('click', increaseFontSize);


// Restore font size on button click
$('#restoreFontSizeButton').on('click', restoreFontSize);
        const contrastModeFromStorage = localStorage.getItem('contrastMode');
        if (contrastModeFromStorage === 'on') {
            body.addClass('contrast-mode');
        } else {
            body.removeClass('contrast-mode');
        }

        const fontSizeFromSession = sessionStorage.getItem('fontSize');
        if (!isNaN(fontSizeFromSession)) {
            body.css('font-size', fontSizeFromSession + 'px');
        }
    });
</script>

</head>
<body style="background-color: black;" style="min-height:90vh;">
    <div  id="app">

        <nav  style="box-shadow: 10px 5px 5px orange; " class="navbar navbar-light navbar-expand-lg ">
                <a style="color: orange" class="navbar-brand" href="{{ url('/') }}">
                   <img src="https://firebasestorage.googleapis.com/v0/b/centrumgier-a08cf.appspot.com/o/Images%2Flogo.png?alt=media&token=02000448-97d1-4bfc-bbbc-6e58b85a0937" alt="Centrum gier">
                </a>
                <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        @if (Session::get('uid') == "pGI2FmUWdAakrR0oZL2G1H1TV9p2" || Session::get('uid') == "vfi5axXhfod7WNOd6Ia4WK7hgbq1")
                            <li  class="nav-item dropdown"> 
                                    <a style="color:red" id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                     Panel Administratora 
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <div class="mb-3 d-flex flex-column align-items-center">                  
                                        <a style="color:red" class="nav-link" href="/admin/games">Zarządzaj grami</a>
                                        <a style="color:red" class="nav-link text-center " href="/admin/users">Zarządzaj użytkownikami</a>
                                        <a style="color:red" class="nav-link text-center " href="/admin/history">Zarządzaj historią</a>
                                        <a style="color:red" class="nav-link text-center " href="/admin/achivments">Zarządzaj osiągnięciami</a>
                                        <a style="color:red" class="nav-link text-center " href="/admin/rankings">Zarządzaj rankingiem</a>
                                        <a style="color:red" class="nav-link text-center " href="/admin/achivuser">Zarządzaj osiągnięciami graczy</a>
                                        </div>
                                    </div>
                             </li>
                            @endif
                            <li class="nav-item">
                                    <a style="color: orange" class="nav-link" href="/games">Gry</a>
                            </li>
                            <li class="nav-item">
                                <a style="color: orange" class="nav-link" href="/rankings">Rankingi</a>
                            </li>
                            <li class="nav-item">
                                <a style="color: orange" class="nav-link" href="/achivments">Spis osiągnięć</a>
                            </li>
                            @if (Route::has('login'))
                            <li class="nav-item">
                                <a style="color: orange" class="nav-link" href="/players">Gracze</a>
                            </li>
                            @endif
                                
                    </ul>
                    <div class="border-bottom border-grey"></div>
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <button  type="button" class="navbar-btn btn"  id="fontSizeButton" onclick="increaseFontSize()">A+ 
                        </button>
                            <button type="button" class="navbar-btn btn" id="restoreFontSizeButton" onclick="restoreFontSize()">A-</button>
                            <button type="button" class="navbar-btn btn" id="contrastButton" onclick="toggleContrast()">
                            <img src="https://firebasestorage.googleapis.com/v0/b/centrumgier-a08cf.appspot.com/o/Images%2Fcontrast.png?alt=media" style="width: 20px; height: 20px; border-radius:180px;">
                        </button>
                        </li>
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a style="color: white" class="nav-link" href="{{ route('login') }}">{{ __('Logowanie') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a style="color: white" class="nav-link" href="{{ route('register') }}">{{ __('Rejestracja') }}</a>
                                </li>
                            @endif
                        @else

                          <li class="nav-item">
                              <a style="color: white" class="nav-link" href="/prof/{{Session::get('uid')}}">Profil</a>
                          </li>

                          <li class="nav-item">
                            <a style="color: white" class="nav-link" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                {{ __('Wyloguj') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                              </li>
                            </div>
                          </li>
                        @endguest
                    </ul>
                </div>
        </nav>

        <main class="py-4" style="margin-bottom: 400px">
            @yield('content')
        </main>

        <br>
        <footer  style="color: white;  border-top: 1px solid #ccc; padding: 20px; display: flex; flex-wrap: wrap; justify-content: space-between; ">

    <div>
        <p style="margin-bottom: 0;">Autorzy strony:</p>
        <div class="border-bottom border-grey"></div>
        <ul style="list-style-type: none; padding-left: 0; ">
            <li style="margin-right: 25px;"><strong>Kacper Kordalski 20192</strong> – Kierownik</li>
            <li style="margin-right: 25px;">Błażej Dudziński 20175</li>
            <li style="margin-right: 25px;">Patryk Gładki 20225</li>
            <li>Kacper Kuczawski 20195</li>
        </ul>
    </div>
    <a href="/contact" style="color: orange;">Skontaktuj się</a>
    </footer>


    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>
