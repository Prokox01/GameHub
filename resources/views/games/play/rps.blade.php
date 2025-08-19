@extends('layouts.app')

@section('content')
<div class="container">
    <div style="display: flex; justify-content: center;">
        <div style="flex: 1; text-align: center;">
            <h2>Twój wybór:</h2>
            <img id="user-choice-img" src="" alt="">
        </div>
        <div style="flex: 1; text-align: center;">
            <h2>Wybór komputera:</h2>
            <img id="computer-choice-img" src="" alt="">
        </div>
    </div>
    <div style="text-align: center; margin-top: 50px; left:calc(50% - 150px);top:50% ;position:absolute;">
<div style="display: inline-block; text-align: center; width: 300px; ">
            <button class="btn btn-warning mb-2 " id="Kamień" style="width: 100px;">Kamień</button>
            <button class="btn btn-warning mb-2 " id="Papier" style="width: 100px;">Papier</button>
            <button class="btn btn-warning mb-2 " id="Nożyce" style="width: 100px;">Nożyce</button>
        </div>
    </div>
</div>

<div id="win-box" class="container bg-light" style="color: orange; position: absolute; text-align: center; top: 50%; left: 50%; transform: translate(-50%, -50%); box-shadow: 2px 2px 15px 5px lightgreen; max-width: 40%; width: 40%; display: none">
    <h1 style="color:lightgreen">Wygrana!!</h1>
    <br>
    <h1 style="color:lightgreen">+10</h1>
    <br>
    <a  href="/games/play/rps/{{$gid}}" class="btn btn-warning mb-2" >Zagraj jeszcze raz</a>
    <a  href="/games" class="btn btn-danger mb-2" >Wróć do wyboru gry</a>
</div>
<div id="lose-box" class="container bg-light" style="color: orange; position: absolute; text-align: center; top: 50%; left: 50%; transform: translate(-50%, -50%); box-shadow: 2px 2px 15px 5px red; max-width: 40%; width: 40%; display: none">
    <h1 style="color:red">Przegrana!!</h1>
    <br>
    <h1 style="color:red">-10</h1>
    <br>
    <a  href="/games/play/rps/{{$gid}}" class="btn btn-warning mb-2" >Zagraj jeszcze raz</a>
    <a  href="/games" class="btn btn-danger mb-2" >Wróć do wyboru gry</a>
</div>
<div id="draw-box" class="container bg-light" style="color: orange; position: absolute; text-align: center; top: 50%; left: 50%; transform: translate(-50%, -50%); box-shadow: 2px 2px 15px 5px grey; max-width: 40%; width: 40%; display: none">
    <h1 style="color:grey">Remis!!</h1>
    <br>
    <h1 style="color:grey">0</h1>
    <br>
    <a  href="/games/play/rps/{{$gid}}" class="btn btn-warning mb-2" >Zagraj jeszcze raz</a>
    <a  href="/games" class="btn btn-danger mb-2" >Wróć do wyboru gry</a>
</div>

<div style="display: none;">
    <form id="addForm"  method="POST" action="{{url('add-history')}}">
    @csrf
    <input id="id" type="text" value="{{$gid}}" name="id" readonly  hidden>
    <input name="wynik" id="wynik" type="text" value="" readonly style="border: none; background-color: transparent; color: black; font-size: 52px; text-align: center; width: 100px; ">
    <br>
    </form>
</div>

<script>
    const computerChoiceDisplay = document.getElementById('computer-choice-img');
    const userChoiceDisplay = document.getElementById('user-choice-img');
    const resultDisplay = document.getElementById('result');
    const possibleChoices = document.querySelectorAll('button');

    let userChoice;
    let computerChoice;
    let result;

    possibleChoices.forEach(possibleChoice => possibleChoice.addEventListener('click', (e) => {
        userChoice = e.target.id;
        if (userChoice === 'Kamień') {
            userChoiceDisplay.src = 'https://firebasestorage.googleapis.com/v0/b/centrumgier-a08cf.appspot.com/o/games%2Fkamien_w_prawo.png?alt=media&token=64f8aa92-14f4-4567-95c0-3704e62df777';
        } else if (userChoice === 'Papier') {
            userChoiceDisplay.src = 'https://firebasestorage.googleapis.com/v0/b/centrumgier-a08cf.appspot.com/o/games%2Fpapier_w_prawo.png?alt=media&token=a7d36045-41e7-4efe-98da-14eac85eb9d2';
        } else if (userChoice === 'Nożyce') {
            userChoiceDisplay.src = 'https://firebasestorage.googleapis.com/v0/b/centrumgier-a08cf.appspot.com/o/games%2Fnozyce_w_prawo.png?alt=media&token=5957b83c-c94a-4b77-9776-99bbdb9e41b2';
        }
        generateComputerChoice();
        getResult();
    }));

    function generateComputerChoice() {
        const randomNumber = Math.floor(Math.random() * 3) + 1;

        if (randomNumber === 1) {
            computerChoice = 'Kamień';
            computerChoiceDisplay.src = 'https://firebasestorage.googleapis.com/v0/b/centrumgier-a08cf.appspot.com/o/games%2Fkamien_w_lewo.png?alt=media&token=ed60e00c-e88c-463e-9f89-6a804393ee1f';
        } else if (randomNumber === 2) {
            computerChoice = 'Nożyce';
            computerChoiceDisplay.src = 'https://firebasestorage.googleapis.com/v0/b/centrumgier-a08cf.appspot.com/o/games%2Fnozyce_w_lewo.png?alt=media&token=0a01906a-8664-41f6-a208-fb3f0c466f4f';
        } else if (randomNumber === 3) {
            computerChoice = 'Papier';
            computerChoiceDisplay.src = 'https://firebasestorage.googleapis.com/v0/b/centrumgier-a08cf.appspot.com/o/games%2Fpapier_w_lewo.png?alt=media&token=03763c4b-44dd-410b-a2ec-3265c2d4d2b4';
        }
    }

    function getResult() {
        document.getElementById("Papier").setAttribute("disabled", true);
        document.getElementById("Kamień").setAttribute("disabled", true);
        document.getElementById("Nożyce").setAttribute("disabled", true);

        if (computerChoice === userChoice) {
            var element = document.getElementById("wynik");
            element.value="0"
            addRecord();
            setTimeout(() => {box(2);}, 1000);
        } else if (
            (computerChoice === 'Kamień' && userChoice === 'Papier') ||
            (computerChoice === 'Papier' && userChoice === 'Nożyce') ||
            (computerChoice === 'Nożyce' && userChoice === 'Kamień')
        ) {
            var element = document.getElementById("wynik");
            element.value="+10"
            addRecord();
            setTimeout(() => {box(0);}, 1000);
        } else {
            var element = document.getElementById("wynik");
            element.value="-10"
            addRecord();
            setTimeout(() => {box(1);}, 1000);
        }
    }

    function box(b) {
if(b==0){
    var element = document.getElementById("win-box").style.display = "block";
}
else if(b==2){
    var element = document.getElementById("draw-box").style.display = "block";
}else{
    var element = document.getElementById("lose-box").style.display = "block";
}
}

function addRecord() {
        // Pobierz dane z formularza
        var formData = $('#addForm').serialize();

        // Wykonaj asynchroniczne zapytanie Ajax
        $.ajax({
            url: "{{url('add-history')}}",
            type: "POST",
            data: formData,
            success: function(response) {
                // Obsłuż odpowiedź, jeśli to konieczne
                console.log(response);

            },
            error: function(error) {
                // Obsłuż błąd, jeśli to konieczne
                console.log(error);
            }
        });
    }
</script>
@endsection
