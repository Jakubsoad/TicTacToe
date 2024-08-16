<?php

namespace App\Service;

use App\Entity\Game;
use App\Enum\Piece;

class TurnChecker
{
    public function getTurn(Game $game): Piece
    {
        if ($game->getScore() !== null) {
            $turn = Piece::NONE;
        } else {
            $turn = $game->getBoardFields()->count() % 2 === 0 ? Piece::X : Piece::O;
        }

        return $turn;
    }
}