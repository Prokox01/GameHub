@extends('layouts.app')

@section('content')
<div id="roomSelection">
    @if($currentRoom && $play)
    <script>var roomId = "{{ $currentRoomId }}"; window.location.href = "/games/play/chinczyk/chinczyk_multi/{{ $gid }}?roomId=" + roomId;</script>
    @endif
    @if($currentRoom)
        <h2>Jesteś w pokoju: <span id="roomId">{{ $currentRoomId }}</span></h2>
        @php
            $playerCount = 0;
            foreach ($currentRoom['players'] as $player) {
                if ($player !== null) {
                    $playerCount++;
                }
            }
        @endphp
        <p id="playerCount">Liczba graczy: {{ $playerCount }}/4</p>
        <p id="timer"></p>
        <button id="exitRoomBtn" onclick="exitRoom('{{ $currentRoomId }}')">Wyjdź</button>
        <button id="playBtn" style="display: none;">Graj!</button>
    @else
        <h2>Wybierz pokój</h2>
        <ul id="roomList">
            @if($availableRooms)
                @foreach($availableRooms as $roomId => $room)
                    <li>
                        Pokój: {{ $roomId }}
                        <button class="joinRoomBtn" data-room-id="{{ $roomId }}">Dołącz</button>
                    </li>
                @endforeach
            @endif
        </ul>
        <button id="createRoomBtn">Stwórz nowy pokój</button>
    @endif
</div>  

<script>
@if($currentRoom)
    if (playBtn) {
    playBtn.addEventListener('click', () => {
        console.log('Play button clicked');
        fetch('/start-game', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({
                roomId: '{{ $currentRoomId }}',
            }),
        })
        .then(response => {
            console.log('Response:', response);
            if (!response.ok) {
                throw new Error('Failed to start the game');
            }
            return response.json();
        })
        .then(data => {
            console.log('Data:', data);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to start the game');
        });
    });
}
@endif
@if(!$currentRoom)
function fetchRoomInfo() {
    fetch('/fetch-room-info', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Failed to fetch room info');
        }
        return response.json();
    })
    .then(data => {
        console.log('Data:', data);
        if (data.hasChanges) {
            if (data.rooms) {
                const roomList = document.getElementById('roomList');
                if (roomList) {
                    let roomsHtml = '';
                    for (const roomId in data.rooms) {
                        if (Object.hasOwnProperty.call(data.rooms, roomId)) {
                            if (roomId !== 'room') {
                                roomsHtml += `<li>Pokój: ${roomId}<button class="joinRoomBtn" data-room-id="${roomId}">Dołącz</button></li>`;
                            }
                        }
                    }
                    roomList.innerHTML = roomsHtml;
                }
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
setInterval(fetchRoomInfo, 5000);
@endif

@if($currentRoom)
function checkPlayValue() {
    fetch('/get-play-value', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify({
            roomId: '{{ $currentRoomId }}',
        }),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Failed to get play value');
        }
        return response.json();
    })
    .then(data => {
        console.log('Play value:', data.play);
        if (data.play) {
            console.log('Ok');
            var roomId = "{{ $currentRoomId }}";
            console.log(roomId);
            window.location.href = "/games/play/chinczyk/chinczyk_multi/{{ $gid }}?roomId=" + roomId;
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

setInterval(checkPlayValue, 5000);

let countdownStarted = false;
    function fetchPlayerCount() {
        fetch('/fetch-player-count', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch player count');
            }
            return response.json();
        })
        
        .then(data => {
            console.log('Player count data:', data);
            if (data.hasChanges) {
                document.getElementById('playerCount').innerText = `Liczba graczy: ${data.playerCount}/4`;
            } else {
                console.log('Brak zmian w liczbie graczy.');
            }
            if (data.playerCount >= 2 && !countdownStarted) {
                    startCountdown(); 
                    countdownStarted = true;
                }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
    document.addEventListener('DOMContentLoaded', function() {
        fetchPlayerCount();
    });

    setInterval(fetchPlayerCount, 5000);
    function startCountdown() {
        let count = 5;
        const countdownInterval = setInterval(() => {
            count--;
            document.getElementById('timer').innerText = `Możesz rozpocząć gre za: ${count}`;
            if (count === 0) {
                clearInterval(countdownInterval);
                document.getElementById('playBtn').style.display = 'block';
                document.getElementById('timer').innerText = ``;
            }
        }, 1000);
    }
@endif


const roomSelection = document.getElementById('roomSelection');

if (roomSelection) {
    const exitRoomBtn = document.getElementById('exitRoomBtn');
    const playBtn = document.getElementById('playBtn');

    if (exitRoomBtn) {
        exitRoomBtn.addEventListener('click', () => {
            console.log('Exit room button clicked');
        });
    }
    if (playBtn) {
        playBtn.addEventListener('click', () => {
            console.log('Play button clicked');
        });
    }

    const roomList = document.getElementById('roomList');
    if (roomList) {
        roomList.addEventListener('click', event => {
            const joinRoomBtn = event.target.closest('.joinRoomBtn');
            if (joinRoomBtn) {
                const roomId = joinRoomBtn.getAttribute('data-room-id');
                console.log('Join room button clicked for room ID:', roomId);
                fetch('/join-roomChinczyk', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        roomId: roomId,
                        currentPlayerUid: '{{ Session::get('uid') }}',
                    }),
                })
                .then(response => {
                    console.log('Response:', response);
                    if (!response.ok) {
                        throw new Error('Failed to join room');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Data:', data);
                    document.getElementById('roomSelection').innerHTML = `
                        <h2>Jesteś w pokoju: ${roomId}</h2>
                         <p>Liczba graczy: ${data.playerCount}/4</p>
                        <button id="exitRoomBtn" data-room-id="${roomId}">Wyjdź</button>
                        <button id="playBtn">Graj!</button>
                    `;
                    location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to join room');
                });
            }
        });
    } else {
        console.error('Element with ID "roomList" not found');
    }

    const createRoomBtn = document.getElementById('createRoomBtn');

    createRoomBtn.addEventListener('click', () => {
        console.log('Create room button clicked');
        fetch('/create-roomChinczyk', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
        })
        .then(response => {
            console.log('Response:', response);
            if (!response.ok) {
                throw new Error('Failed to create room');
            }
            return response.json();
        })
        .then(data => {
            console.log('Data:', data);
            alert('Room created! Room number: ' + data.roomNumber);
            location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to create room');
        });
    });
} else {
    console.error('Element with ID "roomSelection" not found');
}

function exitRoom(roomId) {
    console.log('Room ID:', roomId);
    if (!roomId) {
        console.error('Room ID not found');
        return;
    }

    fetch('/exit-roomChinczyk', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify({
            roomId: roomId,
            currentPlayerUid: '{{ Session::get('uid') }}',
        }),
    })
    .then(response => {
        console.log('Response:', response);
        if (!response.ok) {
            throw new Error('Failed to exit room');
        }
        return response.json();
    })
    .then(data => {
        console.log('Data:', data);
        alert('Successfully exited the room');
        location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to exit room');
    });
}
</script>
@endsection
