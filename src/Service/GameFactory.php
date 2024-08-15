<?php

namespace App\Service;

use App\Entity\Game;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;

class GameFactory
{
    private GameRepository $gameRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(GameRepository $gameRepository, EntityManagerInterface $entityManager)
    {
        $this->gameRepository = $gameRepository;
        $this->entityManager = $entityManager;
    }

    public function getOrCreateGame(): Game
    {
        $currentGame = $this->gameRepository->getCurrentGame();

        if ($currentGame === null) {
            $currentGame = new Game();
            $this->entityManager->persist($currentGame);
            $this->entityManager->flush();
        }

        return $currentGame;
    }
}