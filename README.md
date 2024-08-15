# ticTacToe

Init

GET /
Returns the game state. Initialize a new game if no game has been started before

Response body
Status code 200 OK on success, with body:

    {
      "board": [
        [piece, piece, piece],
        [piece, piece, piece],
        [piece, piece, piece]
      ],
      "score": {
        "x": number,
        "o": number
      },
      "currentTurn": "x" | "o"
      "victory": piece
    }

POST /:piece
Places the piece (either x or o) in the requested grid coordinates:

Request body

    {
      "x": number,
      "y": number
    }

Where x and x can be 0, 1 or 2.

Response body
Status code 409 Conflict if a piece is being placed where a piece already is.

Status code 406 Not acceptable if a piece is being placed out of turn.

Status code 200 OK if the request succeeded, with the following response body:

    {
      "board": [
        [piece, piece, piece],
        [piece, piece, piece],
        [piece, piece, piece]
      ],
      "score": {
        "x": number,
        "o": number
      },
      "currentTurn": "x" | "o"
      "victory": piece
    }

Where piece is either "x", "o" or "" (an empty string) and finished is whether somebody won or the game is still going on.

POST /restart
Clears the board and update the score based on who won (the victory variable). If nobody had a victory in the previous game, it basically clears the board without touching the scores.

Response body
Status code 200 OK if the request succeeded, with the following response body:

    {
      "board": [
        [piece, piece, piece],
        [piece, piece, piece],
        [piece, piece, piece]
      ],
      "score": {
        "x": number,
        "o": number
      },
      "currentTurn": "x" | "o",
      "victory": piece
    }

Where piece is either "x", "o" or "" (an empty string) and finished is whether somebody won or the game is still going on.

DELETE /
Clears the board and scores.

Response body
Status code 200 OK if the request succeeded, with the following response body:

    {
      "currentTurn": "x" | "o"
    }

Where currentTurn is either x or o, indicating who should start.