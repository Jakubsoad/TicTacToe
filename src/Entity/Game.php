<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::JSON)]
    private array $board = [];

    #[ORM\Column(length: 1, options: ['default' => 'X'])]
    private string $current_turn = 'X';

    #[ORM\Column(options: ['default' => 0])]
    private int $x_score = 0;

    #[ORM\Column(options: ['default' => 0])]
    private int $y_score = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBoard(): array
    {
        return $this->board;
    }

    public function setBoard(array $board): static
    {
        $this->board = $board;

        return $this;
    }

    public function getCurrentTurn(): ?string
    {
        return $this->current_turn;
    }

    public function setCurrentTurn(string $current_turn): static
    {
        $this->current_turn = $current_turn;

        return $this;
    }

    public function getXScore(): ?int
    {
        return $this->x_score;
    }

    public function setXScore(int $x_score): static
    {
        $this->x_score = $x_score;

        return $this;
    }

    public function getYScore(): ?int
    {
        return $this->y_score;
    }

    public function setYScore(int $y_score): static
    {
        $this->y_score = $y_score;

        return $this;
    }
}
