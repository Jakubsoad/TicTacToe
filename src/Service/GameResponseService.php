<?php

namespace App\Service;

use App\Entity\Game;
use App\Enum\Piece;
use App\Repository\ScoreRepository;

class GameResponseService
{
    public function __construct(
        private ScoreRepository $scoreRepository,
    ) {}

    public function createGameResponse(Game $game): array
    {
        $victory = $game->getScore()?->getWinner() ?? Piece::NONE;

        return [
            'board' => $game->getBoard(),
            'score' => $this->scoreRepository->getSummaryScores(),
            'turn' => $victory !== Piece::NONE ? Piece::NONE : $game->getCurrentTurn(),
            'victory' => $victory,
        ];
    }
}