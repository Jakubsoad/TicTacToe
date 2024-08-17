# Tic-Tac-Toe API

This API allows you to play Tic-Tac-Toe games. It supports tracks scores and manages game state.

## Installation

To run this project, you need to have Docker installed on your system.

1. Clone the repository
2. Navigate to the project directory
3. Run the following command:
   ```docker
   docker-compose up -d --build
   ```
   
This will start the application and all necessary services.

### Notes:
- PHPUnit is not installed in this project setup.
- The application is running on port 8082.
### Running HTTP Tests
If you want to run the HTTP tests located in the `tests/http` directory and you're not familiar with JetBrains HTTP Client, please refer to the official documentation:

https://www.jetbrains.com/help/phpstorm/http-client-in-product-code-editor.html

This tool allows you to execute HTTP requests directly from the IDE, which is particularly useful for testing and interacting with the API endpoints described in this documentation.


## Changes from Original Requirements

- Implemented a game entity to potentially support multiple concurrent games in the future.
- Added an empty string option for `currentTurn` to indicate a finished game.
- Score is updated immediately when a player wins, not just on restart.
- Changed the place piece endpoint to `POST /place/:piece` for clarity.
- `DELETE /` returns a success message instead of `currentTurn`.

## Endpoints

### Get Game State

Retrieves the current game state or initializes a new game if none exists.

#### GET /
Returns the game state. Initialize a new game if no game has been started before

##### Response body
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
      "currentTurn": piece,
      "victory": piece
    }

Where piece is either "x", "o" or "" (an empty string).

### Place a Piece

Places a piece (x or o) on the game board.

#### POST /place/:piece
Places the piece (either x or o) in the requested grid coordinates:

##### Request body

    {
      "x": number,
      "y": number
    }

Where x and y can be from 0 to `FIELD_SIZE` - 1. Default `FIELD_SIZE` is 3 and can be modified in BoardField class.

##### Response body
Status code 409 Conflict if a piece is being placed where a piece already is:

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
      "currentTurn": piece,
      "victory": piece
    }

Where piece is either "x", "o" or "" (an empty string).

### Restart Game

Clears the board.

#### POST /restart
Clears the board.

##### Response body
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
      "currentTurn": piece,
      "victory": piece
    }

Where piece is either "x", "o" or "" (an empty string).

### Delete all games

Clears the board and resets all scores.

#### DELETE /
Clears the board and scores.

##### Response body
Status code 200 OK if the request succeeded, with the following response body:

    {
      "message": "All games and scores have been reset"
    }