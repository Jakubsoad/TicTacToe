<?php

namespace App\Controller;

use App\Entity\BoardField;
use App\Enum\Piece;
use App\Service\GameFactory;
use App\Service\GameResponseService;
use App\Service\GameService;
use App\Service\PieceService;
use App\Service\GameVictoryService;
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

    #[Route('/place/{piece}', name: 'place_piece', methods: [Request::METHOD_POST])]
    public function placePiece(Request $request, string $piece): JsonResponse
    {
        $requestBody = json_decode($request->getContent(), true, flags: JSON_THROW_ON_ERROR);

        $x = filter_var($requestBody['x'] ?? null, FILTER_VALIDATE_INT);
        $y = filter_var($requestBody['y'] ?? null, FILTER_VALIDATE_INT);

        $piece = Piece::tryFrom(strtoupper($piece));

        $currentGame = $this->gameFactory->getOrCreateGame();

        if ($currentGame->getScore() !== null && $currentGame->getScore()->getWinner() !== Piece::NONE) {
            return $this->json(['error' => 'Game is over. Restart to start a new game'], Response::HTTP_NOT_ACCEPTABLE);
        }

        if (!$piece) {
            return $this->json(['error' => 'Invalid piece value'], Response::HTTP_BAD_REQUEST);
        }

        if ($x < 0 || $x >= BoardField::BOARD_SIZE || $y < 0 || $y >= BoardField::BOARD_SIZE) {
            return $this->json(['error' => 'Invalid coordinates'], Response::HTTP_BAD_REQUEST);
        }

        if ($this->pieceService->isPiecePositionConflict($currentGame, $x, $y)) {
            return $this->json(['error' => 'Invalid move'], Response::HTTP_CONFLICT);
        }

        if ($currentGame->getCurrentTurn() !== $piece) {
            return $this->json(['error' => 'Is turn of ' . $currentGame->getCurrentTurn()->value], Response::HTTP_NOT_ACCEPTABLE);
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

        return $this->json(['message' => 'All games and scores have been reset']);
    }
}
