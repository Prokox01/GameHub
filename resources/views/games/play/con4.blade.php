@extends('layouts.app')
<style type="text/css">
    .containercf {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100vh; 
        color: orange !important;
    }
    .gridcf{
        width: 60vh; /* Szerokość siatki ustawiona na 60% wysokości widoku */
        height: 60vh; /* Wysokość siatki ustawiona na 60% wysokości widoku */
        display: grid; /* Ustawienie siatki */
        grid-template-columns: repeat(7, 1fr); /* 6 kolumn o równej szerokości */
        grid-template-rows: repeat(6, 1fr); /* 7 rzędów o równej wysokości */
        box-shadow: 2px 2px 15px 5px white;
        
    }
    .gridcf div{
        height: 99%;
        width: 100%;
        border:  1px solid white;
    }
    .takencf{
        border: none !important;
    }
    .player-one {
        background-color: red;
        border-radius: 50%;
    }
  
    .player-two {
        background-color: blue;
        border-radius: 50%;
    }
</style>

@section('content')
<div class="containercf" id="AskBox">
    <h2>Wybierz tryb gry</h2>
    <button id="vsPlayerBtn">Gra z innym graczem</button>
    <button id="vsComputerBtn">Gra z komputerem</button>
</div>

<div  class="containercf d-flex justify-content-center">
<div  class="containercf" id="gameContainer" style="display: none">
    
    <div class="gridcf">
        @for ($i = 0; $i < 42; $i++)
            <div data-index="{{ $i }}"></div>
        @endfor
    </div>
 </div>

<div class="containercf " id="roomSelection" style="display:none">
    <h2>Wybierz pokój</h2>
    <ul id="roomList" style="color: orange">
    @if($availableRooms)
        @foreach($availableRooms as $roomId => $room)
            <li>{{ $roomId }}</li>
        @endforeach
     @endif
    </ul>
    <button id="createRoomBtn">Stwórz nowy pokój</button>
</div>
</div>

<div id="win-box" class="container bg-light" style="color: orange; position: absolute; text-align: center; top: 50%; left: 50%; transform: translate(-50%, -50%); box-shadow: 2px 2px 15px 5px lightgreen; max-width: 40%; width: 40%; display: none">
    <h1 style="color:lightgreen">Wygrana!!</h1>
    <br>
    <h1 style="color:lightgreen">+10</h1>
    <br>
    <a  href="/games/play/con4/{{$gid}}" class="btn btn-warning mb-2" >Zagraj jeszcze raz</a>
    <a  href="/games" class="btn btn-danger mb-2" >Wróć do wyboru gry</a>
</div>

<div id="lose-box" class="container bg-light" style="color: orange; position: absolute; text-align: center; top: 50%; left: 50%; transform: translate(-50%, -50%); box-shadow: 2px 2px 15px 5px red; max-width: 40%; width: 40%; display: none">
    <h1 style="color:red">Przegrana!!</h1>
    <br>
    <h1 style="color:red">-10</h1>
    <br>
    <a  href="/games/play/con4/{{$gid}}" class="btn btn-warning mb-2" >Zagraj jeszcze raz</a>
    <a  href="/games" class="btn btn-danger mb-2" >Wróć do wyboru gry</a>
</div>
<div id="draw-box" class="container bg-light" style="color: orange; position: absolute; text-align: center; top: 50%; left: 50%; transform: translate(-50%, -50%); box-shadow: 2px 2px 15px 5px grey; max-width: 40%; width: 40%; display: none">
    <h1 style="color:grey">Remis!!</h1>
    <br>
    <h1 style="color:grey">0</h1>
    <br>
    <a  href="/games/play/con4/{{$gid}}" class="btn btn-warning mb-2" >Zagraj jeszcze raz</a>
    <a  href="/games" class="btn btn-danger mb-2" >Wróć do wyboru gry</a>
</div>
<div id="leave-box" class="container bg-light" style="color: orange; position: absolute; text-align: center; top: 50%; left: 50%; transform: translate(-50%, -50%); box-shadow: 2px 2px 15px 5px orange; max-width: 40%; width: 40%; display: none">
    <h1 style="color:orange">Drugi gracz opuścił pokój gra nie ważna!!!</h1>
    <br>
    <h1 style="color:orange">0</h1>
    <br>
    <a  href="/games/play/con4/{{$gid}}" class="btn btn-warning mb-2" >Zagraj jeszcze raz</a>
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
document.addEventListener('DOMContentLoaded', () => {

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
    // Funkcja do okresowego pobierania danych z serwera
    let intervalId; // Zmienna przechowująca identyfikator interwału
    let intervalIdroom;
function startFetchingDataFromServer() {
    intervalId = setInterval(fetchDataFromServer, 1500); // Uruchomienie interwału
}
function startFetchingrooms() {
    intervalIdroom = setInterval(fetchrooms, 1000); // Uruchomienie interwału
}

function stopFetchingDataFromServer() {
    clearInterval(intervalId); // Zatrzymanie interwału
}
function stopFetchingrooms() {
    clearInterval(intervalIdroom); // Zatrzymanie interwału
}
function fetchrooms() {
                fetch('/getrooms', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}', // Replace with your CSRF token
                    },
                    body: JSON.stringify({
                    }),
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to add move');
                    }
                    return response.json();
                })
                .then(data => {
                    const roomList = document.getElementById('roomList');
                    roomList.innerHTML = '';
                    if (data.rooms && data.rooms.length > 0) {
                        data.rooms.forEach(room => {
                            const listItem = document.createElement('li');
                            listItem.textContent = `${room.roomId}`;
                            roomList.appendChild(listItem);
                        });
                        addlinkroom();
                    } else {
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

    function fetchDataFromServer() {
            let iterator = 0;
                fetch('/getMoveCon4', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}', // Replace with your CSRF token
                    },
                    body: JSON.stringify({
                        roomId: roomID,
                    }),
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to add move');
                    }
                    return response.json();
                })
                .then(data => {
                    let iterator = 0;
                    // Iteracja po każdym ruchu
                    if(data.moves == null){
                        stopFetchingDataFromServer();
                        if(document.getElementById("lose-box").style.display != "block" && document.getElementById("win-box").style.display != "block" && document.getElementById("draw-box").style.display != "block"){;
                            setTimeout(() => {box(3);}, 1000);
                        }
                    }
                    else if(data.moves == 'brak'){
                    }else{
                        data.moves.forEach(move => {
                            const index = move.Index; // Pobranie indeksu z ruchu
                            squares[index].classList.add('taken');
                            if(player1){
                                if (iterator % 2 === 0) {
                                    squares[index].classList.add('player-one');
                                } else {
                                    squares[index].classList.add('player-two');
                                }
                                }else{
                                    if (iterator % 2 === 0) {
                                    squares[index].classList.add('player-two');
                                } else {
                                    squares[index].classList.add('player-one');
                                }
                            }
 
                            iterator++;
                            if (iterator === data.moves.length) {
                                lastplayed = move.uid;
                            } 
                            if(lastplayed == currentPlayer){
                                canmove = false;
                            }else{
                                canmove = true;
                            }

                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
                checkBoard();
        }


    let roomID=null; 
    let check =0;

    function addlinkroom(){
    const roomList = document.getElementById('roomList');
    if (roomList) {
        const rooms = roomList.querySelectorAll('li');

        rooms.forEach(room => {
        room.addEventListener('click', () => {
            const roomId = room.textContent;

            fetch('/join-room', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}', // Replace with your CSRF token
                },
                body: JSON.stringify({
                    roomId: roomId,
                    currentPlayerUid: '{{ Session::get('uid');}}', // Assuming you are using Laravel's built-in authentication
                }),
            })
            .then(response => {
                if (!response.ok) {
                throw new Error('Failed to join room');
   
                }
                return response.json();
            })
            .then(data => {
                
                // Handle the response, you can redirect to the game page or perform other actions
                document.getElementById('roomSelection').style.display = 'none';
                    // Hide mode selection div
                 stopFetchingrooms();
                 player1 = false;
                 roomID=roomId;
                 startFetchingDataFromServer();
                 document.getElementById('AskBox').style.display = 'none';
                 document.getElementById('gameContainer').style.display = 'flex';
                //alert('Joined room ' + roomId);
                gra();
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });
    } else {
        console.error('Element with ID "roomList" not found');
    }
    }
    
    addlinkroom();
    let lastplayed = "{{Session::get('uid') }}";
    let player1 = true;
    let canmove = true;
    
    // Add event listener for create room button
    const createRoomBtn = document.getElementById('createRoomBtn');

    createRoomBtn.addEventListener('click', () => {
        fetch('/create-room', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}', // Ensure to replace this with your CSRF token
        },
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Failed to create room');
        }
        return response.json();
    })
    .then(data => {
    // Handle the response, you can show a success message or perform any other actions
    const roomId = data.roomId; // Pobierz klucz nowo utworzonego pokoju
    //alert('Room created with ID: ' + roomId);
    document.getElementById('roomSelection').style.display = 'none';
    document.getElementById('AskBox').style.display = 'none';
    document.getElementById('gameContainer').style.display = 'block';
    // Przypisz klucz nowo utworzonego pokoju do zmiennej roomID
    lastplayed = null;
    roomID = roomId;
    stopFetchingrooms(); 
    startFetchingDataFromServer();
    gra();
})
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to create room');
    });

    });


  const squares = document.querySelectorAll('.gridcf div')
  const result = document.querySelector('#result')
  const displayCurrentPlayer = document.querySelector('#currentPlayer')
  let currentPlayer = 1
  const vsPlayerBtn = document.getElementById('vsPlayerBtn');
  const vsComputerBtn = document.getElementById('vsComputerBtn');
  vsComputer=0;

    vsPlayerBtn.addEventListener('click', () => {
        // Show room selection div
        document.getElementById('roomSelection').style.display = 'block';
        addlinkroom();
        startFetchingrooms();
        // Hide mode selection div
        document.getElementById('AskBox').style.display = 'none';
    });

    vsComputerBtn.addEventListener('click', () => {
        // Hide mode selection div
        vsComputer=1;
        gra();
        document.getElementById('AskBox').style.display = 'none';
        // Show game container div
        document.getElementById('gameContainer').style.display = 'block';
    }); 
    
  const winningArrays = [
    [0, 1, 2, 3],
    [41, 40, 39, 38],
    [7, 8, 9, 10],
    [34, 33, 32, 31],
    [14, 15, 16, 17],
    [27, 26, 25, 24],
    [21, 22, 23, 24],
    [20, 19, 18, 17],
    [28, 29, 30, 31],
    [13, 12, 11, 10],
    [35, 36, 37, 38],
    [6, 5, 4, 3],
    [0, 7, 14, 21],
    [41, 34, 27, 20],
    [1, 8, 15, 22],
    [40, 33, 26, 19],
    [2, 9, 16, 23],
    [39, 32, 25, 18],
    [3, 10, 17, 24],
    [38, 31, 24, 17],
    [4, 11, 18, 25],
    [37, 30, 23, 16],
    [5, 12, 19, 26],
    [36, 29, 22, 15],
    [6, 13, 20, 27],
    [35, 28, 21, 14],
    [0, 8, 16, 24],
    [41, 33, 25, 17],
    [7, 15, 23, 31],
    [34, 26, 18, 10],
    [14, 22, 30, 38],
    [27, 19, 11, 3],
    [35, 29, 23, 17],
    [6, 12, 18, 24],
    [28, 22, 16, 10],
    [13, 19, 25, 31],
    [21, 15, 9, 3],
    [20, 26, 32, 38],
    [36, 30, 24, 18],
    [5, 11, 17, 23],
    [37, 31, 25, 19],
    [4, 10, 16, 22],
    [2, 10, 18, 26],
    [39, 31, 23, 15],
    [1, 9, 17, 25],
    [40, 32, 24, 16],
    [9, 17, 25, 33],
    [8, 16, 24, 32],
    [11, 17, 23, 29],
    [12, 18, 24, 30],
    [1, 2, 3, 4],
    [5, 4, 3, 2],
    [8, 9, 10, 11],
    [12, 11, 10, 9],
    [15, 16, 17, 18],
    [19, 18, 17, 16],
    [22, 23, 24, 25],
    [26, 25, 24, 23],
    [29, 30, 31, 32],
    [33, 32, 31, 30],
    [36, 37, 38, 39],
    [40, 39, 38, 37],
    [7, 14, 21, 28],
    [8, 15, 22, 29],
    [9, 16, 23, 30],
    [10, 17, 24, 31],
    [11, 18, 25, 32],
    [12, 19, 26, 33],
    [13, 20, 27, 34],
  ]

  function delroomCon4() {
                fetch('/delroomCon4', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}', // Replace with your CSRF token
                    },
                    body: JSON.stringify({
                        roomId: roomID,
                    }),
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to remove room');
                    }
                    return response.json();
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

window.addEventListener('beforeunload', function(event) {   
                fetch('/delroomCon4', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}', // Replace with your CSRF token
                    },
                    body: JSON.stringify({
                        roomId: roomID,
                    }),
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to remove room');
                    }
                    return response.json();
                })
                .catch(error => {
                    console.error('Error:', error);
                });
});

  function checkBoard() {
    let allTaken = true;
    for (let i = 0; i < squares.length; i++) {
        if (!squares[i].classList.contains('taken')) {
            allTaken = false;
            break;
        }
    }

    if (allTaken) {
        if(parseInt(vsComputer)===0) {
                            var element = document.getElementById("wynik");
                            element.value="0"
                            addRecord();
                            setTimeout(() => {box(2);}, 1000);
                            stopFetchingDataFromServer();
                            setTimeout(() => {delroomCon4()}, 1200);
                        } else {
                            setTimeout(() => {box(2);}, 100);
                        }
    }else{

    for (let y = 0; y < winningArrays.length; y++) {
                    const square1 = squares[winningArrays[y][0]];
                    const square2 = squares[winningArrays[y][1]];
                    const square3 = squares[winningArrays[y][2]];
                    const square4 = squares[winningArrays[y][3]];
    
                    if (
                        square1.classList.contains('player-one') &&
                        square2.classList.contains('player-one') &&
                        square3.classList.contains('player-one') &&
                        square4.classList.contains('player-one')
                    ) {
                        if(parseInt(vsComputer)===0) {
                            var element = document.getElementById("wynik");
                            element.value="+10"
                            addRecord();
                            setTimeout(() => {box(0);}, 1000);
                            stopFetchingDataFromServer();
                            setTimeout(() => {delroomCon4()}, 1200);
                        }else{
                                setTimeout(() => {box(0);}, 100);
                            }
                    } else if (
                        square1.classList.contains('player-two') &&
                        square2.classList.contains('player-two') &&
                        square3.classList.contains('player-two') &&
                        square4.classList.contains('player-two')
                    ) {
                        if(parseInt(vsComputer)===0) {
                            var element = document.getElementById("wynik");
                            element.value="-10"
                            addRecord();
                            setTimeout(() => {box(1);}, 1000);
                            stopFetchingDataFromServer();
                            setTimeout(() => {delroomCon4()}, 1200);
                        } else {
                            setTimeout(() => {box(1);}, 100);
                        }
                    }
                }
            }
     }          

  function box(b) {
if(b==0){
    var element = document.getElementById("win-box").style.display = "block";
}
else if(b==2){
    var element = document.getElementById("draw-box").style.display = "block";
}else if(b==3){
    var element = document.getElementById("leave-box").style.display = "block";
}
else{
    var element = document.getElementById("lose-box").style.display = "block";
}

document.getElementById('gameContainer').style.display = 'none';
}

 function computerMove() {
    const availableColumns = getAvailableColumns();
console.log(currentPlayer+ "2");
                console.log(vsComputer+ "2");
    let bestMove;

    // Sprawdzanie czy komputer może wygrać w następnym ruchu
    for (const col of availableColumns) {
        const rowIndex = getLowestEmptyRow(col);

        if (checkWin(rowIndex, col, 'player-two')) {
            bestMove = col;
            break;
        }
    }

    // Sprawdzanie czy blokować przeciwnika przed wygraniem
    if (!bestMove) {
        for (const col of availableColumns) {
            const rowIndex = getLowestEmptyRow(col);

            if (checkWin(rowIndex, col, 'player-one')) {
                bestMove = col;
                break;
            }
        }
    }

    // Jeśli nie ma zagrożenia, wykonaj losowy ruch
    if (!bestMove) {
        const randomIndex = Math.floor(Math.random() * availableColumns.length);
        bestMove = availableColumns[randomIndex];
    }

    const rowIndex = getLowestEmptyRow(bestMove);
    const index = rowIndex * 7 + bestMove;
    squares[index].classList.add('taken');
    squares[index].classList.add('player-two');
    currentPlayer = 1;
    //displayCurrentPlayer.innerHTML = currentPlayer;
}



function gra(){
    if(parseInt(vsComputer)===0) {
    roomID = roomID;
    const currentPlayerUid = "{{Session::get('uid') }}";

    for (let i = 0; i < squares.length; i++) {
        squares[i].onclick = () => {
            const rowIndex = Math.floor(i / 7);

            if (currentPlayerUid !== lastplayed && canmove) {
                canmove = !canmove;
            if (
                (rowIndex === 5 || squares[i + 7].classList.contains('taken')) &&
                !squares[i].classList.contains('taken') 
            ) {
                fetch('/addMoveCon4', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}', // Replace with your CSRF token
                    },
                    body: JSON.stringify({
                        roomId: roomID,
                        currentPlayerUid: currentPlayerUid,
                        Index: i 
                    }),
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to add move');
                    }
                    return response.json();
                })
                .then(data => {
                    let iterator = 0;
                    // Iteracja po każdym ruchu
                    data.moves.forEach(move => {
                        const index = move.Index; // Pobranie indeksu z ruchu
                        squares[index].classList.add('taken');
                        if(player1){
                                if (iterator % 2 === 0) {
                                    squares[index].classList.add('player-one');
                                } else {
                                    squares[index].classList.add('player-two');
                                }
                                }else{
                                    if (iterator % 2 === 0) {
                                    squares[index].classList.add('player-two');
                                } else {
                                    squares[index].classList.add('player-one');
                                }
                            }
                        iterator++;
                        if (iterator === data.moves.length) {
                            lastplayed = move.uid;
                        }
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                });

        
            } else {
                alert('Zły ruch');
            }
        }else{
                alert('Nie twoja kolej!!');
            }
        };
    }
        
    }else if(parseInt(vsComputer)===1){
    for (let i = 0; i < squares.length; i++) {
        squares[i].onclick = () => {
            const rowIndex = Math.floor(i / 7);

            if (
                (rowIndex === 5 || squares[i + 7].classList.contains('taken')) &&
                !squares[i].classList.contains('taken')
            ) {
                if (currentPlayer === 1) {
                    squares[i].classList.add('taken');
                    squares[i].classList.add('player-one');
                    currentPlayer = 2;
                    //displayCurrentPlayer.innerHTML = currentPlayer;
                    console.log(currentPlayer);
                    console.log(vsComputer);
                    if (vsComputer) {
                        // Ruch komputera
                        computerMove();
                    }
                } else if (currentPlayer === 2) {
                    squares[i].classList.add('taken');
                    squares[i].classList.add('player-two');
                    currentPlayer = 1;
                    //displayCurrentPlayer.innerHTML = currentPlayer;
                }
            } else {
                alert('Zły ruch');
            }
            checkBoard();
        };
    }
    }
}

    function checkWin(row, col, playerClass) {

      const directions = [
          [0, 1],  // Poziomo
          [1, 0],  // Pionowo
          [1, 1],  // Skos w prawo
          [-1, 1], // Skos w lewo
      ];

      for (const direction of directions) {
          const [dRow, dCol] = direction;
          let count = 1;

          for (let i = 1; i < 4; i++) {
              const newRow = row + i * dRow;
              const newCol = col + i * dCol;

              if (
                  newRow >= 0 && newRow < 6 &&
                  newCol >= 0 && newCol < 7 &&
                  squares[newRow * 7 + newCol].classList.contains(playerClass)
              ) {
                  count++;
              } else {
                  break;
              }
          }

          if (count === 4) {
              return true;
          }
      }

      return false;
  }

  function getAvailableColumns() {
    const availableColumns = [];

    for (let col = 0; col < 7; col++) {
        if (!squares[col].classList.contains('taken')) {
            availableColumns.push(col);
        }
    }

    return availableColumns;
}

function getLowestEmptyRow(col) {
    for (let row = 5; row >= 0; row--) {
        const index = row * 7 + col;

        if (!squares[index].classList.contains('taken')) {
            return row;
        }
    }

   return -1; // Kolumna pełna
}

})
</script>
@endsection