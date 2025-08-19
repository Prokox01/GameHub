import { BASE_POSITIONS, HOME_ENTRANCE, HOME_POSITIONS, PLAYERS, SAFE_POSITIONS, START_POSITIONS, STATE, TURNING_POINTS } from './constants.js';
import { UI } from './UI.js';

export class Ludo {
    currentPositions = {
        P1: [],
        P2: [],
        P3: [],
        P4: []
    }
    
    _ZmSprP3;
    get ZmSprP3(){
        return this._ZmSprP3;
    }
    set ZmSprP3(value){
        this._ZmSprP3 = value;
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
        this._ZmSprP3 = 0;
        this.listenDiceClick();
        this.listenResetClick();
        this.listenPieceClick();

        this.resetGame();
        
    }

    listenDiceClick() {
        UI.listenDiceClick(this.onDiceClick.bind(this))
    }

    onDiceClick() {
        console.log('dice clicked!');
        this.diceValue = 1 + Math.floor(Math.random() * 6);
        this.state = STATE.DICE_ROLLED;
    
        this.changeDiceImage(this.diceValue);
    
        this.checkForEligiblePieces();
    
        if (this.turn !== 0) {
            setTimeout(() => {
                console.log('automatic dice roll!');
                this.autoRollDice();
            }, 1000);
        }
    }
    
    autoRollDice() {
        if (this.turn !== 0) {
            console.log("Current turn before rolling: " + this.turn);
            this.diceValue = 1 + Math.floor(Math.random() * 6);
            this.state = STATE.DICE_ROLLED;
            this.changeDiceImage(this.diceValue);
    
            console.log("Dice rolled: " + this.diceValue);
            const currentPlayer = PLAYERS[this.turn];
            UI.enableDiceForPlayer(currentPlayer);
    
            const eligiblePieces = this.getEligiblePieces(currentPlayer);
            if (eligiblePieces.length > 0) {
                const randomPiece = eligiblePieces[Math.floor(Math.random() * eligiblePieces.length)];
                this.handlePieceClick(currentPlayer, randomPiece.toString());
                console.log("Piece moved for player: " + currentPlayer);
            } else {
                console.log("No eligible pieces to move for player: " + currentPlayer);
                this.incrementTurn();
                console.log("Turn incremented to: " + this.turn);
            }
    
            setTimeout(() => {
                console.log('Automatic dice roll for next turn!');
                this.autoRollDice();
            }, 1000);
        }
    }

    autoRollDiceP3(){
        this.diceValue = 1 + Math.floor(Math.random() * 6);
        this.state = STATE.DICE_ROLLED;
    
        this.changeDiceImage(this.diceValue);
    
        const currentPlayer = PLAYERS[this.turn];
        UI.enableDiceForPlayer(currentPlayer);
        this.checkForEligiblePieces();

        console.log("kostka"+this.diceValue);

        if (this.diceValue === 6) {
            const eligiblePieces = this.getEligiblePieces(currentPlayer);
            if (eligiblePieces.length > 0) {
                const randomPiece = eligiblePieces[Math.floor(Math.random() * eligiblePieces.length)];
                this.handlePieceClick(currentPlayer, randomPiece.toString());
                    this.autoRollDiceP3();

            }
        }
        this.turn = 2;
    }
    
    autoMovePiece(player, moveBy) {
        if (this.turn !== 0) {
            console.log(this.turn);
            const eligiblePieces = this.getEligiblePieces(player);
            console.log(eligiblePieces);
    
            if (eligiblePieces.length > 0) {
                const randomPiece = eligiblePieces[Math.floor(Math.random() * eligiblePieces.length)];
                this.movePiece(player, randomPiece.toString(), moveBy);
                console.log("Moved piece for player " + player);
    
                this.incrementTurn();  // Always increment turn after a move
                console.log("Turn after move: " + this.turn);
                this.autoRollDice();
            } else {
                this.incrementTurn();  // Increment turn if no eligible pieces
                console.log("No move possible, turn incremented to: " + this.turn);
                setTimeout(() => {
                    console.log('Automatic dice roll due to no moves!');
                    this.autoRollDice();
                }, 1000);
            }
        }
    }
    

    changeDiceImage(value) {
        let zdjecia = ["../../../../ludo/jeden.png", "../../../../ludo/dwa.png", "../../../../ludo/trzy.png", "../../../../ludo/cztery.png", "../../../../ludo/piec.png", "../../../../ludo/szesc.png"];
        let kostka = document.querySelector(".kostka img");
         kostka.classList.add("shake");

    setTimeout(function () {
        kostka.classList.remove("shake");
        kostka.src = zdjecia[value - 1];
    }, 200);
    }
    checkForEligiblePieces() {
        const player = PLAYERS[this.turn];
        const eligiblePieces = this.getEligiblePieces(player);
        if(eligiblePieces.length) {
            UI.highlightPieces(player, eligiblePieces);
        } else {
            if(this.turn===3){

            }else{
              this.incrementTurn();  
            }
            
        }
    }

    incrementTurn() {
        this.turn = (this.turn + 1) % PLAYERS.length;
        this.state = STATE.DICE_NOT_ROLLED;
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
        
        if (!target.classList.contains('player-piece') || !target.classList.contains('highlight')) {
            return;
        }
        
        console.log('piece clicked');
        const player = target.getAttribute('player-id');
        const piece = target.getAttribute('piece');
        this.handlePieceClick(player, piece);
        
        if (this.turn === 0 && this.diceValue !==6) {
            setTimeout(() => {
                console.log('automatic dice roll!');
                this.autoRollDice();
            }, 1000);
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
                    this.resetGame();
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
        const opponents = PLAYERS.filter(opponent => opponent !== player);
    
        let kill = false;
    
        opponents.forEach(opponent => {
            [0, 1, 2, 3].forEach(opponentPiece => {
                const opponentPosition = this.currentPositions[opponent][opponentPiece];
    
                if (currentPosition === opponentPosition && !SAFE_POSITIONS.includes(currentPosition)) {
                    this.setPiecePosition(opponent, opponentPiece, BASE_POSITIONS[opponent][opponentPiece]);
                    kill = true;
                }
            });
        });
    
        return kill;
    }

    hasPlayerWon(player) {
        return [0, 1, 2, 3].every(piece => this.currentPositions[player][piece] === HOME_POSITIONS[player])
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
}