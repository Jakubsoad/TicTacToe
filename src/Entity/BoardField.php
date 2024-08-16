<?php

namespace App\Entity;

use App\Enum\Piece;
use App\Repository\BoardFieldRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BoardFieldRepository::class)]
#[ORM\Table(name: 'board', uniqueConstraints: [
    new ORM\UniqueConstraint(name: 'unique_game_x_y', columns: ['gameId', 'x', 'y']),
])]
class BoardField
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'board')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Game $game = null;

    #[ORM\Column]
    private Piece $piece;

    #[ORM\Column]
    private int $xPosition;

    #[ORM\Column]
    private int $yPosition;

    #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTimeImmutable $createdAt;

    public function __construct(Game $game, Piece $piece, int $xPosition, int $yPosition)
    {
        $this->game = $game;
        $this->xPosition = $xPosition;
        $this->yPosition = $yPosition;
        $this->piece = $piece;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): static
    {
        $this->game = $game;

        return $this;
    }

    public function getPiece(): Piece
    {
        return $this->piece;
    }

    public function setPiece(Piece $piece): void
    {
        $this->piece = $piece;
    }

    public function getYPosition(): int
    {
        return $this->yPosition;
    }

    public function getXPosition(): int
    {
        return $this->xPosition;
    }
}
