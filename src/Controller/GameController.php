<?php

namespace App\Controller;

use App\Enum\Piece;
use App\Service\GameFactory;
use App\Service\GameResponseService;
use App\Service\GameService;
use App\Service\PieceService;
use App\Service\GameVictoryService;
use App\Service\TurnChecker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GameController extends AbstractController
{
    public function __construct(
        private GameFactory         $gameFactory,
        private GameVictoryService  $gameVictoryService,
        private TurnChecker         $turnChecker,
        private GameResponseService $gameResponseService,
        private PieceService        $pieceService,
        private GameService         $gameService,
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
            return $this->json(['error' => 'Invalid piece value'], Response::HTTP_BAD_REQUEST);
        }

        if ($x < 0 || $x > 2 || $y < 0 || $y > 2) {
            return $this->json(['error' => 'Invalid coordinates'], Response::HTTP_BAD_REQUEST);
        }

        if ($this->pieceService->isPiecePositionConflict($currentGame, $x, $y)) {
            return $this->json(['error' => 'Invalid move'], Response::HTTP_CONFLICT);
        }

        if ($this->turnChecker->getTurn($currentGame) !== $piece) {
            return $this->json(['error' => 'Is turn of ' . $piece->value], Response::HTTP_NOT_ACCEPTABLE);
        }

        $this->pieceService->placePiece(
            $currentGame,
            $piece,
            $x,
            $y
        );

        $winner = $this->gameVictoryService->checkVictory($currentGame);
        $this->gameVictoryService->saveScore($currentGame, $winner);

        return $this->json(
            $this->gameResponseService->createGameResponse($currentGame)
        );
    }


    #[Route('/restart', name: 'restart', methods: [Request::METHOD_POST])]
    public function restart(): JsonResponse
    {
        $game = $this->gameFactory->createGame();

        return $this->json(
            $this->gameResponseService->createGameResponse($game)
        );
    }

    #[Route('/', name: 'delete_game', methods: [Request::METHOD_DELETE])]
    public function deleteGame(): JsonResponse
    {
        $this->gameService->removeAllGames();

        return $this->json(['message' => 'All games deleted']);
    }
}
