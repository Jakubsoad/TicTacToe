<?php

namespace App\Service;

use App\Entity\Game;
use App\Repository\ScoreRepository;

class GameResponseService
{
    public function __construct(
        private ScoreRepository $scoreRepository,
        private TurnChecker $turnChecker
    ) {}

    public function createGameResponse(Game $game): array
    {
        return [
            'currentGame' => $game,
            'board' => $game->getBoard(),
            'score' => $this->scoreRepository->getSummaryScores(),
            'turn' => $this->turnChecker->getTurn($game), // jtd check if game will refresh from placepiece
            'victory' => $game->getScore()->getWinner(),
        ];
    }
}