@extends('layouts.app')

@section('content')

<br> 

<div class="class=col-lg-3 col-md-4 col-sm-6 col-xs-12 container d-flex justify-content-center" >
    <canvas class="d-flex justify-content-center"  style="box-shadow: 2px 2px 15px 5px white;" id="gameCanvas" width="400" height="400"></canvas>
</div>

<div  id="start-box" class="container" style="color: orange; position: absolute; text-align: center; top: 50%; left: 50%; transform: translate(-50%, -50%); box-shadow: 2px 2px 15px 5px orange; max-width: 40%; width: 40%;">
    <h1>Naciśnij dowolną strzałkę aby zacząć</h1>
</div>

<div id="finish-box" class="container bg-light" style="color: orange; position: absolute; text-align: center; top: 50%; left: 50%; transform: translate(-50%, -50%); box-shadow: 2px 2px 15px 5px orange; max-width: 40%; width: 40%; display: none">
    <h1>Koniec gry!!</h1>
    <h1>Twój wynik</h1>
    <form id="addForm"  method="POST" action="{{url('add-history')}}">
    @csrf
    <input id="id" type="text" value="{{$gid}}" name="id" readonly  hidden>
    <input name="wynik" id="wynik" type="number" value="0" readonly style="border: none; background-color: transparent; color: black; font-size: 52px; text-align: center; width: 100px; ">
    <br>
    </form>
    <a  href="/games/play/snake/{{$gid}}" class="btn btn-warning mb-2" >Zagraj jeszcze raz</a>
    <a  href="/games" class="btn btn-danger mb-2" >Wróć do wyboru gry</a>
</div>

<script>
    const canvas = document.getElementById("gameCanvas");
    const ctx = canvas.getContext("2d");

    const gridSize = 20;
    let snake = [{ x: 10, y: 10 }];
    let food = { x: 15, y: 15 };
    let dx = 0;
    let dy = 0;

    function drawSnake() {
        ctx.fillStyle = "green";
        snake.forEach(segment => ctx.fillRect(segment.x * gridSize, segment.y * gridSize, gridSize, gridSize));
    }

    function drawFood() {
        ctx.fillStyle = "red";
        ctx.fillRect(food.x * gridSize, food.y * gridSize, gridSize, gridSize);
    }

    function moveSnake() {
        const head = { x: snake[0].x + dx, y: snake[0].y + dy };
        snake.unshift(head);
        if (head.x === food.x && head.y === food.y) {
            generateFood();
            addscore();
        } else {
            snake.pop();
        }
    }
    function addscore() {
    // Pobierz element o id "wynik"
    var element = document.getElementById("wynik");

    // Pobierz aktualną wartość i przekształć na liczbę
    var currentValue = parseInt(element.value);

    // Zwiększ wartość o 1
    var newValue = currentValue + 1;

    // Ustaw nową wartość na elemencie
    element.value = newValue;
}
    function box(b) {
            if(b==0){
                var element = document.getElementById("start-box").style.display = "none";
            }else{
                var element = document.getElementById("finish-box").style.display = "block";
            }
    }
    function generateFood() {
        food.x = Math.floor(Math.random() * (canvas.width / gridSize));
        food.y = Math.floor(Math.random() * (canvas.height / gridSize));
    }

    function clearCanvas() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    }

    function checkCollision() {
        const head = snake[0];
        return (
            head.x < 0 ||
            head.x >= canvas.width / gridSize ||
            head.y < 0 ||
            head.y >= canvas.height / gridSize ||
            snake.slice(1).some(segment => segment.x === head.x && segment.y === head.y)
        );
    }

    function main() {
        if (checkCollision()) {
            // Pobierz formularz o id "addgame"
            addRecord();
            box(1);
            return;
        }

        clearCanvas();
        drawSnake();
        drawFood();
        moveSnake();

        setTimeout(main, 100);
    }

    document.addEventListener("keydown", event => {
        const keyPressed = event.key;
        const goingUp = dy === -1;
        const goingDown = dy === 1;
        const goingLeft = dx === -1;
        const goingRight = dx === 1;
        box(0);
        if (keyPressed === "ArrowUp" && !goingDown) {
            dx = 0;
            dy = -1;
        }

        if (keyPressed === "ArrowDown" && !goingUp) {
            dx = 0;
            dy = 1;
        }

        if (keyPressed === "ArrowLeft" && !goingRight) {
            dx = -1;
            dy = 0;
        }

        if (keyPressed === "ArrowRight" && !goingLeft) {
            dx = 1;
            dy = 0;
        }
    });

    generateFood();
    main();
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
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
