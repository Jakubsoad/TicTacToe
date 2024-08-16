<?php

namespace App\Service;

use App\Entity\BoardField;
use App\Entity\Game;
use App\Entity\Score;
use App\Enum\Piece;
use Doctrine\ORM\EntityManagerInterface;

class GameVictoryService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function checkVictory(Game $game): ?Piece
    {
        $board = $game->getBoard();

        $winner = Piece::NONE;

        // Check horizontally
        for ($x = 0; $x < BoardField::BOARD_SIZE; $x++) {
            $possibleWinner = $board[$x][0];

            if ($possibleWinner === Piece::NONE) {
                continue;
            }

            $isWinner = true;
            for ($y = 1; $y < BoardField::BOARD_SIZE; $y++) {
                if ($board[$x][$y] !== $possibleWinner) {
                    $isWinner = false;
                    break;
                }
            }

            if ($isWinner) {
                return $possibleWinner;
            }
        }

        // Check vertically
        for ($y = 0; $y < BoardField::BOARD_SIZE; $y++) {
            $possibleWinner = $board[0][$y];

            if ($possibleWinner === Piece::NONE) {
                continue;
            }

            $isWinner = true;
            for ($x = 1; $x < BoardField::BOARD_SIZE; $x++) {
                if ($board[$x][$y] !== $possibleWinner) {
                    $isWinner = false;
                    break;
                }
            }

            if ($isWinner) {
                return $possibleWinner;
            }
        }

        // Check top-left to bottom-right diagonal
        $possibleWinner = $board[0][0];
        if ($possibleWinner !== Piece::NONE) {
            $isWinner = true;
            for ($i = 1; $i < BoardField::BOARD_SIZE; $i++) {
                if ($board[$i][$i] !== $possibleWinner) {
                    $isWinner = false;
                    break;
                }
            }

            if ($isWinner) {
                return $possibleWinner;
            }
        }

        // Check bottom-left to top-right diagonal
        $possibleWinner = $board[BoardField::BOARD_SIZE - 1][0];
        if ($possibleWinner !== Piece::NONE) {
            $isWinner = true;
            for ($i = 1; $i < BoardField::BOARD_SIZE; $i++) {
                if ($board[BoardField::BOARD_SIZE - 1 - $i][$i] !== $possibleWinner) {
                    $isWinner = false;
                    break;
                }
            }

            if ($isWinner) {
                return $possibleWinner;
            }
        }

        return Piece::NONE;
    }

    public function saveScore(Game $game, ?Piece $winner): void
    {
        if ($winner === null) {
            return;
        }

        $score = new Score($game, $winner);
        $this->entityManager->persist($score);
        $this->entityManager->flush();
    }
}