<?php

namespace App\Service;

use App\Entity\BoardField;
use App\Entity\Game;
use App\Enum\Piece;
use Doctrine\ORM\EntityManagerInterface;

class PieceService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function isPiecePositionConflict(Game $game, int $x, int $y): bool
    {
        $conflict = false;
        foreach ($game->getBoardFields() as $boardField) {
            if ($boardField->getXPosition() === $x && $boardField->getYPosition() === $y) {
                $conflict = true;
                break;
            }
        }

        return $conflict;
    }

    public function placePiece(Game $game, Piece $piece, int $x, int $y): void
    {
        $boardField = new BoardField($game, $piece, $x, $y);
        $this->entityManager->persist($boardField);
        $this->entityManager->flush();
    }
}