<?php

namespace App\Service;

use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;

class GameService
{

    public function __construct(
        private GameRepository $gameRepository,
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function removeAllGames(): void
    {
        $games = $this->gameRepository->findAll();
        foreach ($games as $game) {
            $this->entityManager->remove($game);
        }
        $this->entityManager->flush();
    }
}