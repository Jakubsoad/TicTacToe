<?php

namespace App\Controller;

use App\Enum\Piece;
use App\Repository\ScoreRepository;
use App\Service\GameFactory;
use App\Service\GameResponseService;
use App\Service\PieceService;
use App\Service\GameVictoryChecker;
use App\Service\TurnChecker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class GameController extends AbstractController
{
    public function __construct(
        private GameFactory         $gameFactory,
        private GameVictoryChecker  $gameVictoryChecker,
        private ScoreRepository     $scoreRepository,
        private TurnChecker         $turnChecker,
        private GameResponseService $gameResponseService,
        private PieceService $pieceService
    )
    {
    }

    #[Route('/', name: 'game', methods: [Request::METHOD_GET])]
    public function index(): JsonResponse
    {
        $currentGame = $this->gameFactory->getOrCreateGame();

        return $this->json(
            $this->gameResponseService->createGameResponse($currentGame)
        );
    }

    #[Route('/', name: 'place_piece', methods: [Request::METHOD_POST])]
    public function placePiece(Request $request): JsonResponse
    {
        $currentGame = $this->gameFactory->getOrCreateGame();
        $piece = Piece::tryFrom($request->request->get('piece'));
        $x = (int)$request->request->get('x');
        $y = (int)$request->request->get('y');

        if (!$piece) {
            return $this->json(['error' => 'Invalid piece value'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if ($x < 0 || $x > 2 || $y < 0 || $y > 2) {
            return $this->json(['error' => 'Invalid coordinates'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if ($this->pieceService->isPiecePositionConflict($currentGame, $x, $y)) {
            return $this->json(['error' => 'Invalid move'], JsonResponse::HTTP_CONFLICT);
        }

        if ($this->turnChecker->getTurn($currentGame) !== $piece) {
            return $this->json(['error' => 'Is turn of ' . $piece->value], JsonResponse::HTTP_NOT_ACCEPTABLE);
        }

        $this->pieceService->placePiece(
            $currentGame,
            $piece,
            $x,
            $y
        );

        return $this->json(
            $this->gameResponseService->createGameResponse($currentGame)
        );
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
