<?php

namespace App\Controller;

use App\Repository\ScoreRepository;
use App\Service\GameFactory;
use App\Service\GameVictoryChecker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class GameController extends AbstractController
{
    private GameFactory $gameFactory;
    private GameVictoryChecker $gameVictoryChecker;
    private ScoreRepository $scoreRepository;

    public function __construct(GameFactory $gameFactory, GameVictoryChecker $gameVictoryChecker, ScoreRepository $scoreRepository)
    {
        $this->gameFactory = $gameFactory;
        $this->gameVictoryChecker = $gameVictoryChecker;
        $this->scoreRepository = $scoreRepository;
    }

    #[Route('/', name: 'game', methods: [Request::METHOD_GET])]
    public function index(): JsonResponse
    {
        $currentGame = $this->gameFactory->getOrCreateGame();

        return $this->json([
            'currentGame' => $currentGame,
            'board' => $currentGame->getBoard(),
            'score' => $this->scoreRepository->getSummaryScores(),
            'turn' => $currentGame->getTurn(),
            'victory' => $currentGame->getScore()->getWinner(),
        ]);
    }

    #[Route('/', name: 'place_piece', methods: [Request::METHOD_POST])]
    public function placePiece(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/GameController.php',
        ]);
    }

    #[Route('/restart', name: 'restart', methods: [Request::METHOD_POST])]
    public function restart(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/GameController.php',
        ]);
    }

    #[Route('/', name: 'delete_game', methods: [Request::METHOD_DELETE])]
    public function deleteGame(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/GameController.php',
        ]);
    }
}
