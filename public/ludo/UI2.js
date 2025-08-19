import { COORDINATES_MAP, PLAYERS, STEP_LENGTH } from './constants2.js';

const diceButtonElement = document.querySelector('#dice-btn');

const firstPlayer = players[0];
const secondPlayer = players[1];
let thirdPlayer, fourthPlayer;
if (players.length >= 3) {
    thirdPlayer = players[2];
}
if (players.length >= 4) {
    fourthPlayer = players[3];
}
console.log(firstPlayer,secondPlayer,thirdPlayer,fourthPlayer);


let playerPiecesElements = {
    [firstPlayer]: document.querySelectorAll(`[player-id="${firstPlayer}"].player-piece`),
    [secondPlayer]: document.querySelectorAll(`[player-id="${secondPlayer}"].player-piece`),
}
if (players.length >= 3) {
    playerPiecesElements = {
        [firstPlayer]: document.querySelectorAll(`[player-id="${firstPlayer}"].player-piece`),
        [secondPlayer]: document.querySelectorAll(`[player-id="${secondPlayer}"].player-piece`),
        [thirdPlayer]: document.querySelectorAll(`[player-id="${thirdPlayer}"].player-piece`),
    }
}
if (players.length >= 4) {
  playerPiecesElements = {
        [firstPlayer]: document.querySelectorAll(`[player-id="${firstPlayer}"].player-piece`),
        [secondPlayer]: document.querySelectorAll(`[player-id="${secondPlayer}"].player-piece`),
        [thirdPlayer]: document.querySelectorAll(`[player-id="${thirdPlayer}"].player-piece`),
        [fourthPlayer]: document.querySelectorAll(`[player-id="${fourthPlayer}"].player-piece`),
    }
}
console.log(playerPiecesElements);

export class UI {
    static listenDiceClick(callback) {
        diceButtonElement.addEventListener('click', callback);
    }

    static listenResetClick(callback) {
        document.querySelector('button#reset-btn').addEventListener('click', callback)
    }

    static listenPieceClick(callback) {
        document.querySelector('.player-pieces').addEventListener('click', callback)
    }

    /**
     * 
     * @param {string} player 
     * @param {Number} piece 
     * @param {Number} newPosition 
     */
    static setPiecePosition(player, piece, newPosition) {
        if(!playerPiecesElements[player] || !playerPiecesElements[player][piece]) {
            console.error(`Player element of given player: ${player} and piece: ${piece} not found`)
            return;
        }

        const [x, y] = COORDINATES_MAP[newPosition];

        const pieceElement = playerPiecesElements[player][piece];
        pieceElement.style.top = y * STEP_LENGTH + '%';
        pieceElement.style.left = x * STEP_LENGTH + '%';
    }

    static setTurn(index) {
        if(index < 0 || index >= PLAYERS.length) {
            console.error('index out of bound!');
            return;
        }
        
        const player = PLAYERS[index];
    
        document.querySelector('.active-player span').innerText = player;
    
        const activePlayerBase = document.querySelector('.player-base.highlight');
        if(activePlayerBase) {
            activePlayerBase.classList.remove('highlight');
        }
        document.querySelector(`[player-id="${player}"].player-base`).classList.add('highlight')
    }

    static enableDice() {
        diceButtonElement.removeAttribute('disabled');
    }

    static disableDice() {
        diceButtonElement.setAttribute('disabled', '');
    }

    static enableDiceForPlayer(player) {
        const playerDiceButton = document.querySelector(`[player-id="${player}"] .dice-btn`);
        
        if (playerDiceButton) {
            playerDiceButton.removeAttribute('disabled');
        }
    }

    /**
     * 
     * @param {string} player 
     * @param {Number[]} pieces 
     */
    static highlightPieces(player, pieces) {
        pieces.forEach(piece => {
            const pieceElement = playerPiecesElements[player][piece];
            pieceElement.classList.add('highlight');
        })
    }

    static unhighlightPieces() {
        document.querySelectorAll('.player-piece.highlight').forEach(ele => {
            ele.classList.remove('highlight');
        })
    }

    static setDiceValue(value) {
        document.querySelector('.dice-value').innerText = value;
    }
}