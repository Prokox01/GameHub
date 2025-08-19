import { BASE_POSITIONS, HOME_ENTRANCE, HOME_POSITIONS, PLAYERS, SAFE_POSITIONS, START_POSITIONS, STATE, TURNING_POINTS } from './constants2.js';
import { UI } from './UI2.js';
const firstPlayer = players[0];
const secondPlayer = players[1];
const thirdPlayer = players[2];
const fourthPlayer = players[3];
let Activefunction = false;
let APlayer = ActivePlayer;
export class Ludo {
    
    currentPositions = {
        [firstPlayer]: [],
        [secondPlayer]: [],
        [thirdPlayer]: [],
        [fourthPlayer]: []
    }

    _diceValue;
    get diceValue() {
        return this._diceValue;
    }
    set diceValue(value) {
        this._diceValue = value;

        UI.setDiceValue(value);
    }

    _turn;
    get turn() {
        return this._turn;
    }
    set turn(value) {
        this._turn = value;
        UI.setTurn(value);
    }
    changeTurn(){
        if (APlayer == players[0]) {
            this.turn = 0;
        } else if (APlayer == players[1]) {
            this.turn = 1;
        } else if (APlayer == players[2]){
            this.turn = 2;
        }else if(APlayer == players[3]){
            this.turn = 3;
        }
        UI.setTurn(this.turn);
    }
    _state;
    get state() {
        return this._state;
    }
    set state(value) {
        this._state = value;

        if(value === STATE.DICE_NOT_ROLLED) {
            UI.enableDice();
            UI.unhighlightPieces();
        } else {
            UI.disableDice();
        }
    }

    constructor() {
        console.log('Hello World! Lets play Ludo!');
        this.currentPositions = {};
        this.update = true;
        this.isOperationInProgress = false;
        this.listenDiceClick();
        this.listenResetClick();
        this.listenPieceClick();
        this.playersWhoWon = null;
        this.end = false;

        this.resetGame();

        setInterval(() =>{
                this.getWin();
        }, 5000);

        setInterval(() =>{
            if(this.end === true){
                this.ending();
                this.end=false;
            }
        }, 2000);

        setInterval(() =>{
            if(this.end !== true){
                this.fetchActivePlayer();
            }
        }, 3000);
        setInterval(() => {
            this.changeTurn();
        }, 2000);

        setInterval(() => {
            if(this.update==true){
               this.fetchGameState();
               this.update=false;
            }

        }, 1000);

        setInterval(() => {
            if(this.end !== true){
            if(APlayer !== currentPlayerUid){
            this.fetchLatestDiceRoll();
            }
        }
        }, 3000);
    }

    listenDiceClick() {
        UI.listenDiceClick(this.onDiceClick.bind(this))
    }


    changeDiceImage(value) {
        const images = [
            "../../../../ludo/jeden.png",
            "../../../../ludo/dwa.png",
            "../../../../ludo/trzy.png",
            "../../../../ludo/cztery.png",
            "../../../../ludo/piec.png",
            "../../../../ludo/szesc.png"
        ];
    
        const loadedImages = images.map(src => {
            const img = new Image();
            img.src = src;
            return img;
        });
    
        let diceImage = document.querySelector(".kostka img");
        if (APlayer === currentPlayerUid) {
            diceImage.classList.add("shake");
    
            setTimeout(() => {
                diceImage.classList.remove("shake");
                diceImage.src = loadedImages[value - 1].src;
            }, 200);
        } else {
            diceImage.src = loadedImages[value - 1].src;
        }
    }
    
    onDiceClick() {
        Activefunction=true;
        if(APlayer !== currentPlayerUid || this.end === true){
            console.log('Nie twoja kolej');
            return;
        }
        console.log('dice clicked!');
        setTimeout(() => {
            this.diceValue = 1 + Math.floor(Math.random() * 6);
            this.changeDiceImage(this.diceValue);
            this.state = STATE.DICE_ROLLED;
            console.log(this.diceValue);
            this.sendDiceRoll(this.diceValue);
            this.checkForEligiblePieces();
        }, 200);
    }

    applyDiceRoll(diceValue) {
        this.diceValue = diceValue;
        this.changeDiceImage(diceValue);
    }

    sendDiceRoll(diceRoll) {
        fetch('/sendDiceRoll', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                roomId: roomId,
                diceValue: diceRoll,
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to send dice roll');
            }
            return response.json();
        })
        .then(data => {
            console.log('wysłane');
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    fetchLatestDiceRoll() {
        fetch('/getDiceRoll', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ roomId: roomId })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch latest dice roll');
            }
            return response.json();
        })
        .then(data => {
            
            this.applyDiceRoll(data.diceValue);
            console.log('pobieram '+data.diceValue);
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

 


    checkForEligiblePieces() {
        Activefunction=true;
        const player = PLAYERS[this.turn];
        const eligiblePieces = this.getEligiblePieces(player);
        if(eligiblePieces.length) {
            UI.highlightPieces(player, eligiblePieces);
            Activefunction=true;
        } else {
            this.incrementTurn();
        }
    }

    incrementTurn() {
        Activefunction=false;
        this.turn = (this.turn + 1) % players.length;
        UI.setTurn(this.turn);
        this.updateGameStateInDatabase()
        if(this.turn === 0){
            APlayer = players[0];
            this.updateActivePlayer(APlayer);
        }
        else if(this.turn === 1){
            APlayer = players[1];
            this.updateActivePlayer(APlayer);
        }
        else if(this.turn === 2){
            APlayer = players[2];
            this.updateActivePlayer(APlayer);
        }
        else if(this.turn === 3){
            APlayer = players[3];
            this.updateActivePlayer(APlayer);
        }
        this.state = STATE.DICE_NOT_ROLLED;
    }
    
   updateActivePlayer(APlayer) {
    Activefunction=true;
    var csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;

        fetch('/updateActivePlayer', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({
                roomId: roomId,
                ActivePlayer: APlayer
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to update ActivePlayer');
            }
            return response.json();
        })
        .then(data => {
            console.log('Message:', data.message);
  
            Activefunction=false;
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }


    getEligiblePieces(player) {
        return [0, 1, 2, 3].filter(piece => {
            const currentPosition = this.currentPositions[player][piece];

            if(currentPosition === HOME_POSITIONS[player]) {
                return false;
            }

            if(
                BASE_POSITIONS[player].includes(currentPosition)
                && this.diceValue !== 6
            ){
                return false;
            }

            if(
                HOME_ENTRANCE[player].includes(currentPosition)
                && this.diceValue > HOME_POSITIONS[player] - currentPosition
                ) {
                return false;
            }

            return true;
        });
    }

    listenResetClick() {
        UI.listenResetClick(this.resetGame.bind(this))
    }

    resetGame() {
        console.log('reset game');
        this.currentPositions = structuredClone(BASE_POSITIONS);

        PLAYERS.forEach(player => {
            [0, 1, 2, 3].forEach(piece => {
                this.setPiecePosition(player, piece, this.currentPositions[player][piece])
            })
        });

        this.turn = 0;
        this.state = STATE.DICE_NOT_ROLLED;
    }

    listenPieceClick() {
        UI.listenPieceClick(this.onPieceClick.bind(this));
    }

    onPieceClick(event) {
        const target = event.target;

        if(!target.classList.contains('player-piece') || !target.classList.contains('highlight')) {
            return;
        }
        console.log('piece clicked')

        const player = target.getAttribute('player-id');
        const piece = target.getAttribute('piece');
        console.log(player);
        console.log(currentPlayerUid);
        if (player === currentPlayerUid) {
        this.handlePieceClick(player, piece);
        }
    }

    handlePieceClick(player, piece) {
        console.log(player, piece);
        const currentPosition = this.currentPositions[player][piece];
        
        if(BASE_POSITIONS[player].includes(currentPosition)) {
            this.setPiecePosition(player, piece, START_POSITIONS[player]);
            this.state = STATE.DICE_NOT_ROLLED;
            return;
        }

        UI.unhighlightPieces();
        this.movePiece(player, piece, this.diceValue);
    }

    setPiecePosition(player, piece, newPosition) {
        this.currentPositions[player][piece] = newPosition;
        UI.setPiecePosition(player, piece, newPosition)
    }

    movePiece(player, piece, moveBy) {
        const interval = setInterval(() => {
            this.incrementPiecePosition(player, piece);
            moveBy--;
            if(moveBy === 0) {
                clearInterval(interval);
                if(this.hasPlayerWon(player)) {
                    alert(`Player: ${player} has won!`);
                    return;
                }

                const isKill = this.checkForKill(player, piece);

                if(isKill || this.diceValue === 6) {
                    this.state = STATE.DICE_NOT_ROLLED;
                    return;
                }

                this.incrementTurn();
            }
        }, 200);
    }

    checkForKill(player, piece) {
        const currentPosition = this.currentPositions[player][piece];
    
        let kill = false;
        players.forEach(opponent => {
            if (opponent !== player) { 
                [0, 1, 2, 3].forEach(opponentPiece => {
                    const opponentPosition = this.currentPositions[opponent][opponentPiece];
    
                    if (currentPosition === opponentPosition && !SAFE_POSITIONS.includes(currentPosition)) {
                        this.setPiecePosition(opponent, opponentPiece, BASE_POSITIONS[opponent][0]);
                        kill = true; 
                    }
                });
            }
        });
    
        return kill; 
    }

   
    hasPlayerWon(player) {
        const playerHasWon = [0, 1, 2, 3].every(piece => this.currentPositions[player][piece] === HOME_POSITIONS[player]);
        if (playerHasWon) {
        var element = document.getElementById("wynik");
            element.value="+10"
            addRecord();
            setTimeout(() => {box(0);}, 1000);
            this.playersWhoWon=APlayer;
           console.log(this.playersWhoWon);
           this.sendWin();  
        }    
    }

    incrementPiecePosition(player, piece) {
        this.setPiecePosition(player, piece, this.getIncrementedPosition(player, piece));
    }
    
    getIncrementedPosition(player, piece) {
        const currentPosition = this.currentPositions[player][piece];

        if(currentPosition === TURNING_POINTS[player]) {
            return HOME_ENTRANCE[player][0];
        }
        else if(currentPosition === 51) {
            return 0;
        }
        return currentPosition + 1;
    }

    fetchActivePlayer() {
        var csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
        if (Activefunction) {
            console.log('Updating...');
        }
    
        fetch('/getActivePlayer', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({
                roomId: roomId,
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch ActivePlayer');
            }
            return response.json();
        })
        .then(data => {
            if(APlayer!==data.ActivePlayer){
                this.fetchGameState();
            }
            APlayer = data.ActivePlayer;
            
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    updateGameStateInDatabase() {
        const gameState = {
            positions: { ...this.currentPositions }
        };
        gameState.positions[APlayer] = this.currentPositions[APlayer];

        fetch('/updateGameState', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                roomId: roomId, 
                gameState,
            })
        })
        .then(response => response.json())
        .then(data =>{
            console.log('GameState updated successfully', data);
            
        })
            .catch(error => console.error('Error updating game state:', error));
        }

    fetchGameState() {
        var csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
        
        fetch('/getGameState', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({
                roomId: roomId,
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch game state');
            }
            return response.json();
        })
        .then(data => {
            this.applyGameState(data);
            this.currentPositions = data.state.positions;
        })
        .catch(error => {
            console.error('Error fetching game state:', error);
        });
    }

    applyGameState(gameState) {
        console.log('Applying game state:', gameState); 
        if (gameState && gameState.state && typeof gameState.state === 'object' && gameState.state.positions) {
            this.currentPositions = gameState.state.positions;
            this.updateUI();
        } else {
            console.error('Invalid or missing game state:', gameState);
        }
    }

    updateUI() {
        if (this.currentPositions && typeof this.currentPositions === 'object') {
            Object.entries(this.currentPositions).forEach(([playerId, positions]) => {
                if (Array.isArray(positions)) {
                    positions.forEach((position, index) => {
                        if (typeof position === 'number') { 
                            UI.setPiecePosition(playerId, index, position);
                        } else {
                            console.warn('Invalid position:', position, 'for player:', playerId);
                        }
                    });
                } else {
                    console.error('Positions for player', playerId, 'are not an array:', positions);
                }
            });
        } else {
            console.error('Current positions data is not an object:', this.currentPositions);
        }
    }

    sendWin() {
        this.end = true;
        let win=this.end
        let PW = this.playersWhoWon;
        fetch('/sendWin', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                roomId: roomId, 
                win,
                PW
            })
        })
        .then(response => response.json())
        .then(data =>{
            console.log('sendWin updated successfully', data);
            
        })
            .catch(error => console.error('Error updating game state:', error));
        }

        getWin() { 
            fetch('/getWin', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    roomId: roomId,
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to fetch game state');
                }
                return response.json();
            })
            .then(data => {
                if(data.win===true){
                    this.end=true;
                    this.playersWhoWon=data.PW;
                    this.end=true;
                }
            })
            .catch(error => {
                console.error('Error fetching game state:', error);
            });
        }

    ending() {
        let checkPlayers = [];
        if(players.length >= 4){
        if(this.playersWhoWon === players[0]){
            checkPlayers = [secondPlayer,thirdPlayer,fourthPlayer];
        }
        else if(this.playersWhoWon === players[1]){
            checkPlayers = [firstPlayer,thirdPlayer,fourthPlayer];
        }
        else if(this.playersWhoWon === players[2]){
            checkPlayers = [firstPlayer,secondPlayer,fourthPlayer];
        }
        else if(this.playersWhoWon === players[3]){
            checkPlayers = [firstPlayer,secondPlayer,thirdPlayer];
        }    
        let g1,g2,g3;
         g1 = this.currentPositions[checkPlayers[0]].filter(position => position === HOME_POSITIONS[checkPlayers[0]]).length;
         g2 = this.currentPositions[checkPlayers[1]].filter(position => position === HOME_POSITIONS[checkPlayers[1]]).length;
         g3 = this.currentPositions[checkPlayers[2]].filter(position => position === HOME_POSITIONS[checkPlayers[2]]).length;
         console.log('P1:'+ g1);
         console.log('P2:'+ g2);
         console.log('P3:'+ g3);
         if(g1 > g2){
            if(g1 > g3){
                if(currentPlayerUid===checkPlayers[0]){
                    var element = document.getElementById("wynik");
                    element.value="+0"
                    addRecord();
                    setTimeout(() => {box(2);}, 1000);
                }
            }else{
                if(currentPlayerUid===checkPlayers[0]){
                    var element = document.getElementById("wynik");
                    element.value="+0"
                    addRecord();
                    setTimeout(() => {box(2);}, 1000);
                }
            }
        }
        else if(g1 > g3){
            if(currentPlayerUid===checkPlayers[0]){
                var element = document.getElementById("wynik");
                element.value="+0"
                addRecord();
                setTimeout(() => {box(2);}, 1000);
            }
        }else if(g1 === g2 || g1 === g3) {
                if(currentPlayerUid===checkPlayers[0]){
                    var element = document.getElementById("wynik");
                    element.value="+0"
                    addRecord();
                    setTimeout(() => {box(2);}, 1000);
                }
        }else{
            if(currentPlayerUid===checkPlayers[0]){
                var element = document.getElementById("wynik");
                element.value="-10"
                addRecord();
                setTimeout(() => {box(1);}, 1000);
            }
        }

        if(g2 > g1){
            if(g2 > g3){
                if(currentPlayerUid===checkPlayers[1]){
                    var element = document.getElementById("wynik");
                    element.value="+0"
                    addRecord();
                    setTimeout(() => {box(2);}, 1000);
                }
            }else{
                if(currentPlayerUid===checkPlayers[1]){
                    var element = document.getElementById("wynik");
                    element.value="+0"
                    addRecord();
                    setTimeout(() => {box(2);}, 1000);
                }
            }
        }else if(g2 > g3){
            if(currentPlayerUid===checkPlayers[1]){
                var element = document.getElementById("wynik");
                element.value="+0"
                addRecord();
                setTimeout(() => {box(2);}, 1000);
            }
        }else if(g2 === g1 || g2 === g3){
            if(currentPlayerUid===checkPlayers[1]){
                var element = document.getElementById("wynik");
                element.value="+0"
                addRecord();
                setTimeout(() => {box(2);}, 1000);
            }
        }
        else{
            if(currentPlayerUid===checkPlayers[1]){
                var element = document.getElementById("wynik");
                element.value="-10"
                addRecord();
                setTimeout(() => {box(1);}, 1000);
            }
        }

        if(g3 > g1){
            if( g3 > g2){
                if(currentPlayerUid===checkPlayers[2]){
                    var element = document.getElementById("wynik");
                    element.value="+0"
                    addRecord();
                    setTimeout(() => {box(2);}, 1000);
                }
            }else{
                if(currentPlayerUid===checkPlayers[2]){
                    var element = document.getElementById("wynik");
                    element.value="+0"
                    addRecord();
                    setTimeout(() => {box(2);}, 1000);
                }
            }
            
        }else if(g3 > g2){
            if(currentPlayerUid===checkPlayers[2]){
                var element = document.getElementById("wynik");
                element.value="+0"
                addRecord();
                setTimeout(() => {box(2);}, 1000);
            }
        }else if(g3 === g1 || g3 === g2){
            if(currentPlayerUid===checkPlayers[2]){
                var element = document.getElementById("wynik");
                element.value="+0"
                addRecord();
                setTimeout(() => {box(2);}, 1000);
            }
        }else{
            if(currentPlayerUid===checkPlayers[2]){
                var element = document.getElementById("wynik");
                element.value="-10"
                addRecord();
                setTimeout(() => {box(1);}, 1000);
            }
        }
    }
    if(players.length === 3){
        if(this.playersWhoWon === players[0]){
            checkPlayers = [secondPlayer,thirdPlayer];
        }
        else if(this.playersWhoWon === players[1]){
            checkPlayers = [firstPlayer,thirdPlayer];
        }
        else if(this.playersWhoWon === players[2]){
            checkPlayers = [firstPlayer,secondPlayer];
        }  
        let g1,g2;
        g1 = this.currentPositions[checkPlayers[0]].filter(position => position === HOME_POSITIONS[checkPlayers[0]]).length;
        g2 = this.currentPositions[checkPlayers[1]].filter(position => position === HOME_POSITIONS[checkPlayers[1]]).length;
        if(g1>g2){
            if(currentPlayerUid===checkPlayers[0]){
                var element = document.getElementById("wynik");
                element.value="+0"
                addRecord();
                setTimeout(() => {box(2);}, 1000);
            }
        }
      else if(g1===g2||g2===g1){
        if(currentPlayerUid===checkPlayers[0]){
            var element = document.getElementById("wynik");
            element.value="+0"
            addRecord();
            setTimeout(() => {box(2);}, 1000);
        }
      }else{
        if(currentPlayerUid===checkPlayers[0]){
            var element = document.getElementById("wynik");
            element.value="-10"
            addRecord();
            setTimeout(() => {box(1);}, 1000);
        }
      }
      if(g2>g1){
        if(currentPlayerUid===checkPlayers[1]){
            var element = document.getElementById("wynik");
            element.value="+0"
            addRecord();
            setTimeout(() => {box(2);}, 1000);
        }
      }else if(g1===g2||g2===g1){
        if(currentPlayerUid===checkPlayers[1]){
            var element = document.getElementById("wynik");
            element.value="+0"
            addRecord();
            setTimeout(() => {box(2);}, 1000);
        }
      }
      else{
        if(currentPlayerUid===checkPlayers[1]){
            var element = document.getElementById("wynik");
            element.value="-10"
            addRecord();
            setTimeout(() => {box(1);}, 1000);
        }
      }
    }
    if(players.length === 2){
        if(this.playersWhoWon === players[0]){
            checkPlayers = [secondPlayer];
        }
        else if(this.playersWhoWon === players[1]){
            checkPlayers = [firstPlayer];
        }
        let g1;
        g1 = this.currentPositions[checkPlayers[0]].filter(position => position === HOME_POSITIONS[checkPlayers[0]]).length;
        if(currentPlayerUid===checkPlayers[0]){
            var element = document.getElementById("wynik");
            element.value="-10"
            addRecord();
            setTimeout(() => {box(1);}, 1000);
        }
    }

        setTimeout(this.deleteGameRoom, 10000);
    }
    deleteGameRoom() {
        fetch('/deleteRoom', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ roomId: roomId })
        })
        .then(response => response.json())
        .then(data =>{
            setTimeout(() => {
                window.location.href = `/games/play/chinczyk/rooms/${roomId}`;
            }, 10000);
        })
        .catch(error => console.error('Error:', error));
    }

}