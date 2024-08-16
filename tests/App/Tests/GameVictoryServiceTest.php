<?php

namespace App\Tests;

use App\Entity\Game;
use App\Enum\Piece;
use App\Service\GameVictoryService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class GameVictoryServiceTest extends TestCase
{
    private GameVictoryService $gameVictoryService;

    protected function setUp(): void
    {
        $this->gameVictoryService = new GameVictoryService($this->createMock(EntityManagerInterface::class));
    }

    public function testHorizontalWinner()
    {
        $board = [
            [Piece::X, Piece::X, Piece::X],
            [Piece::O, Piece::NONE, Piece::O],
            [Piece::NONE, Piece::NONE, Piece::O]
        ];

        $gameMock = $this->createMock(Game::class);
        $gameMock->method('getBoard')
            ->willReturn($board);
        $this->assertEquals(Piece::X, $this->gameVictoryService->checkVictory($gameMock));
    }

    public function testVerticalWinner()
    {
        $board = [
            [Piece::O, Piece::X, Piece::NONE],
            [Piece::O, Piece::X, Piece::NONE],
            [Piece::O, Piece::NONE, Piece::NONE]
        ];

        $gameMock = $this->createMock(Game::class);
        $gameMock->method('getBoard')
            ->willReturn($board);
        $this->assertEquals(Piece::O, $this->gameVictoryService->checkVictory($gameMock));
    }

    public function testDiagonalWinnerTopLeftToBottomRight()
    {
        $board = [
            [Piece::X, Piece::O, Piece::NONE],
            [Piece::O, Piece::X, Piece::NONE],
            [Piece::NONE, Piece::NONE, Piece::X]
        ];

        $gameMock = $this->createMock(Game::class);
        $gameMock->method('getBoard')
            ->willReturn($board);
        $this->assertEquals(Piece::X, $this->gameVictoryService->checkVictory($gameMock));
    }

    public function testDiagonalWinnerBottomLeftToTopRight()
    {
        $board = [
            [Piece::NONE, Piece::NONE, Piece::O],
            [Piece::NONE, Piece::O, Piece::X],
            [Piece::O, Piece::X, Piece::X]
        ];
        $gameMock = $this->createMock(Game::class);
        $gameMock->method('getBoard')
            ->willReturn($board);
        $this->assertEquals(Piece::O, $this->gameVictoryService->checkVictory($gameMock));
    }

    public function testNoWinner()
    {
        $board = [
            [Piece::X, Piece::X, Piece::NONE],
            [Piece::O, Piece::NONE, Piece::O],
            [Piece::NONE, Piece::NONE, Piece::O]
        ];

        $gameMock = $this->createMock(Game::class);
        $gameMock->method('getBoard')
            ->willReturn($board);
        $this->assertEquals(Piece::NONE, $this->gameVictoryService->checkVictory($gameMock));
    }

    public function testEmptyBoard()
    {
        $board = [
            [Piece::NONE, Piece::NONE, Piece::NONE],
            [Piece::NONE, Piece::NONE, Piece::NONE],
            [Piece::NONE, Piece::NONE, Piece::NONE]
        ];

        $gameMock = $this->createMock(Game::class);
        $gameMock->method('getBoard')
            ->willReturn($board);
        $this->assertEquals(Piece::NONE, $this->gameVictoryService->checkVictory($gameMock));
    }

}
