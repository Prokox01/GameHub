export const COORDINATES_MAP = {
    0: [6, 13],
    1: [6, 12],
    2: [6, 11],
    3: [6, 10],
    4: [6, 9],
    5: [5, 8],
    6: [4, 8],
    7: [3, 8],
    8: [2, 8],
    9: [1, 8],
    10: [0, 8],
    11: [0, 7],
    12: [0, 6],
    13: [1, 6],
    14: [2, 6],
    15: [3, 6],
    16: [4, 6],
    17: [5, 6],
    18: [6, 5],
    19: [6, 4],
    20: [6, 3],
    21: [6, 2],
    22: [6, 1],
    23: [6, 0],
    24: [7, 0],
    25: [8, 0],
    26: [8, 1],
    27: [8, 2],
    28: [8, 3],
    29: [8, 4],
    30: [8, 5],
    31: [9, 6],
    32: [10, 6],
    33: [11, 6],
    34: [12, 6],
    35: [13, 6],
    36: [14, 6],
    37: [14, 7],
    38: [14, 8],
    39: [13, 8],
    40: [12, 8],
    41: [11, 8],
    42: [10, 8],
    43: [9, 8],
    44: [8, 9],
    45: [8, 10],
    46: [8, 11],
    47: [8, 12],
    48: [8, 13],
    49: [8, 14],
    50: [7, 14],
    51: [6, 14],

    // HOME ENTRANCE

    // P1
    100: [7, 13],
    101: [7, 12],
    102: [7, 11],
    103: [7, 10],
    104: [7, 9],
    105: [7, 8],

    // P2
    200: [1, 7],
    201: [2, 7],
    202: [3, 7],
    203: [4, 7],
    204: [5, 7],
    205: [6, 7],

    //P3
    300: [7, 1],
    301: [7, 2],
    302: [7, 3],
    303: [7, 4],
    304: [7, 5],
    305: [7, 6],

    //P4
    400: [13, 7],
    401: [12, 7],
    402: [11, 7],
    403: [10, 7],
    404: [9, 7],
    405: [8, 7],

    // BASE POSITIONS

    // P1
    500: [1.5, 10.58],
    501: [3.57, 10.58],
    502: [1.5, 12.43],
    503: [3.57, 12.43],

    // P2
    600: [1.5, 1.58],
    601: [3.57, 1.58],
    602: [1.5, 3.45],
    603: [3.57, 3.45],

    // P3
    700: [10.5, 1.58],
    701: [12.54, 1.58],
    702: [10.5, 3.45],
    703: [12.54, 3.45],

    // P4
    800: [10.5, 10.58],
    801: [12.54, 10.58],
    802: [10.5, 12.43],
    803: [12.54, 12.43],
};

export const STEP_LENGTH = 6.66;
console.log(players);
const firstPlayer = players[0];
const secondPlayer = players[1];
let thirdPlayer, fourthPlayer;
if (players.length >= 3) {
    thirdPlayer = players[2];
}

if (players.length >= 4) {
    fourthPlayer = players[3];
}

export let PLAYERS = [firstPlayer, secondPlayer];
if (players.length >= 3) {
    PLAYERS = [firstPlayer, secondPlayer, thirdPlayer];
}

if (players.length >= 4) {
    PLAYERS = [firstPlayer, secondPlayer, thirdPlayer,fourthPlayer];
}
console.log(PLAYERS);

export let BASE_POSITIONS = {
    [firstPlayer]: [500, 501, 502, 503],
    [secondPlayer]: [600, 601, 602, 603]
};
if (players.length >= 3) {
     BASE_POSITIONS = {
        [firstPlayer]: [500, 501, 502, 503],
        [secondPlayer]: [600, 601, 602, 603],
        [thirdPlayer]: [700, 701, 702, 703]
    };
}
if (players.length >= 4) {
     BASE_POSITIONS = {
        [firstPlayer]: [500, 501, 502, 503],
        [secondPlayer]: [600, 601, 602, 603],
        [thirdPlayer]: [700, 701, 702, 703],
        [fourthPlayer]: [800, 801, 802, 803]
    };
}
console.log(BASE_POSITIONS);

export let START_POSITIONS = {
    [firstPlayer]: 0,
    [secondPlayer]: 13
};
if (players.length >= 3) {
    START_POSITIONS = {
        [firstPlayer]: 0,
        [secondPlayer]: 13,
        [thirdPlayer]: 26
    };
}
if (players.length >= 4) {
    START_POSITIONS = {
        [firstPlayer]: 0,
        [secondPlayer]: 13,
        [thirdPlayer]: 26,
        [fourthPlayer]: 39
    };
}
console.log(START_POSITIONS);

export let HOME_ENTRANCE = {
    [firstPlayer]: [100, 101, 102, 103, 104],
    [secondPlayer]: [200, 201, 202, 203, 204]
}
if (players.length >= 3) {
    HOME_ENTRANCE = {
        [firstPlayer]: [100, 101, 102, 103, 104],
        [secondPlayer]: [200, 201, 202, 203, 204],
        [thirdPlayer]: [300, 301, 302, 303, 304]
    }
}
if (players.length >= 4) {
     HOME_ENTRANCE = {
        [firstPlayer]: [100, 101, 102, 103, 104],
        [secondPlayer]: [200, 201, 202, 203, 204],
        [thirdPlayer]: [300, 301, 302, 303, 304],
        [fourthPlayer]: [400, 401, 402, 403, 404]
    }
}
console.log(HOME_ENTRANCE);

export let HOME_POSITIONS = {
    [firstPlayer]: 105,
    [secondPlayer]: 205
};
if (players.length >= 3) {
     HOME_POSITIONS = {
        [firstPlayer]: 105,
        [secondPlayer]: 205,
        [thirdPlayer]: 305
    }
}
if (players.length >= 4) {
     HOME_POSITIONS = {
        [firstPlayer]: 105,
        [secondPlayer]: 205,
        [thirdPlayer]: 305,
        [fourthPlayer]: 405
    };
}
console.log(HOME_POSITIONS);

export let TURNING_POINTS = {
    [firstPlayer]: 50,
    [secondPlayer]: 11
};
if (players.length >= 3) {
     TURNING_POINTS = {
        [firstPlayer]: 50,
        [secondPlayer]: 11,
        [thirdPlayer]: 24
    };
}
if (players.length >= 4) {
     TURNING_POINTS = {
        [firstPlayer]: 50,
        [secondPlayer]: 11,
        [thirdPlayer]: 24,
        [fourthPlayer]: 37
    };
}
console.log(TURNING_POINTS);

export const SAFE_POSITIONS = [0, 8, 13, 21, 26, 34, 39, 47];

export const STATE = {
    DICE_NOT_ROLLED: 'DICE_NOT_ROLLED',
    DICE_ROLLED: 'DICE_ROLLED',
}