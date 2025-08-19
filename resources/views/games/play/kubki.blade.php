@extends('layouts.app')

@section('content')

<br> 
<div id="polecenie"  class="container bg-light text-center" style="box-shadow: 2px 2px 15px 5px orange; display: none; " >
    <p>Wybierz kubek!</p>
</div>
<br>
<div class="container d-flex justify-content-center bg-white rounded align-items-center"  style="box-shadow: 2px 2px 15px 5px white; height: 400px" >
        <div class="cup" style="bottom: 100px">
            <div style="bottom: -100px" class="ball"></div>
        </div>
        <div class="cup" ></div>
        <div class="cup" ></div>
</div>
<br>
<div class="container d-flex justify-content-center"  >
        <button class="btn btn-warning mb-2 "id="shuffle" onclick="shuffle()">Zagraj</button>
</div>



<div id="win-box" class="container bg-light" style="color: orange; position: absolute; text-align: center; top: 50%; left: 50%; transform: translate(-50%, -50%); box-shadow: 2px 2px 15px 5px lightgreen; max-width: 40%; width: 40%; display: none">
    <h1 style="color:lightgreen">Wygrana!!</h1>
    <br>
    <h1 style="color:lightgreen">+10</h1>
    <br>
    <a  href="/games/play/kubki/{{$gid}}" class="btn btn-warning mb-2" >Zagraj jeszcze raz</a>
    <a  href="/games" class="btn btn-danger mb-2" >Wróć do wyboru gry</a>
</div>
<div id="lose-box" class="container bg-light" style="color: orange; position: absolute; text-align: center; top: 50%; left: 50%; transform: translate(-50%, -50%); box-shadow: 2px 2px 15px 5px red; max-width: 40%; width: 40%; display: none">
    <h1 style="color:red">Przegrana!!</h1>
    <br>
    <h1 style="color:red">-10</h1>
    <br>
    <a  href="/games/play/kubki/{{$gid}}" class="btn btn-warning mb-2" >Zagraj jeszcze raz</a>
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
        let cups = document.getElementsByClassName('cup');
        let ball = document.querySelector('.ball');
        let messageBox = document.getElementById('messageBox');
        let loseBox = document.getElementById('loseBox');
        let shuffleButton = document.getElementById('shuffle');

        function shuffle() {

            let numSwaps = Math.floor(Math.random() *25)  + 23; 
            let delay = 300; 
            cups[0].removeAttribute('style');
            ball.removeAttribute('style');
           ball.setAttribute("hidden", "hidden");

            for (let i = 0; i < numSwaps; i++) {
                setTimeout(() => {
                    // Randomly pick two cups to swap
                    let cupIndex1 = Math.floor(Math.random() * 3);
                    let cupIndex2 = Math.floor(Math.random() * 3);

                    while(cupIndex1 == cupIndex2){
                        cupIndex2 = Math.floor(Math.random() * 3);
                    }
                
                    // Swap the cups' innerHTML
                    let temp = cups[cupIndex1].innerHTML;
                    cups[cupIndex1].innerHTML = cups[cupIndex2].innerHTML;
                    cups[cupIndex2].innerHTML = temp;

                    // Keep the cups in their swapped positions
                    let offset = cups[cupIndex2].offsetLeft - cups[cupIndex1].offsetLeft;
                    cups[cupIndex2].style.transform = `translateX(-${offset}px)`;
                    cups[cupIndex1].style.transform = `translateX(${offset}px)`;

                    // Reset transformation after animation
                    setTimeout(() => {
                        cups[cupIndex2].style.transform = '';
                        cups[cupIndex1].style.transform = '';
                    }, 500);
                }, i * delay);
            }
            shuffleButton.disabled = true;
            ///niech to poczeka na skończenie tego fora 
            setTimeout(addClickEvents, numSwaps * delay);
            }

function addClickEvents() {
    for (let i = 0; i < cups.length; i++) {
        cups[i].setAttribute('onclick', 'reveal(this)');
    }
    var element = document.getElementById("polecenie").style.display = "block";
}             

function reveal(cup) {

    let ball = cup.querySelector('.ball');
  
    if (ball) {
        ball.removeAttribute('hidden');
 
        cup.style.transform = 'translateY(-100px)';
        ball.style.transform = 'translateY(100px)';

  
        for (let i = 0; i < cups.length; i++) {
            cups[i].removeAttribute('onclick');
        }
        var element = document.getElementById("wynik");
        element.value="+10"
        addRecord();
        setTimeout(() => {box(0);}, 1000);
    } else {
        ball = document.querySelector('.ball');
        ball.removeAttribute('hidden');
    
        for (let i = 0; i < cups.length; i++) {
            cups[i].style.transform = 'translateY(-100px)';
        }

        ball.style.transform = 'translateY(100px)';
  
        for (let i = 0; i < cups.length; i++) {
            cups[i].removeAttribute('onclick');
        }
        var element = document.getElementById("wynik");
        element.value="-10"
        addRecord();
        setTimeout(() => {box(1);}, 1000);
    }
}

</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    
function box(b) {
if(b==0){
    var element = document.getElementById("win-box").style.display = "block";
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
<style>
.cup {
    filter: drop-shadow(10px 5px 15px #000);
    background-size:cover;
    background-position: center;
    background-image: url(https://firebasestorage.googleapis.com/v0/b/centrumgier-a08cf.appspot.com/o/Images%2Fcup.png?alt=media);
    min-width: 140px;
    height: 160px;
    position: relative;
    margin: 0 20px;
    cursor: pointer;
    transition: transform 0.5s;
    transform-style: preserve-3d;
    transform: rotateX(5deg) rotateY(0deg) translateZ(10px);
}

.ball {
    filter: drop-shadow(10px 5px 15px #000);
    background-size:cover;
    background-position: center;
    min-width: 50px;
    height: 50px;
    background-image: url(https://firebasestorage.googleapis.com/v0/b/centrumgier-a08cf.appspot.com/o/Images%2Fball.png?alt=media);
    border-radius: 50%;
    position: absolute;
    bottom: 0;
    left: 40px;
    transition: transform 0.5s;
    transform-style: preserve-3d;
    transform: rotateX(0deg) rotateY(0deg) translateZ(25px);
}
    </style>
@endsection
