<?php

namespace App\Entity;

use App\Repository\BoardRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BoardRepository::class)]
#[ORM\Table(name: 'board', uniqueConstraints: [
    new ORM\UniqueConstraint(name: 'unique_game_x_y', columns: ['game_id', 'x', 'y'])
])]
class Board
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'board')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Game $game_id = null;

    #[ORM\Column]
    private ?int $x = null;

    #[ORM\Column]
    private ?int $y = null;

    //jtd turn instead of piece?
    #[ORM\Column(length: 1)]
    private ?string $piece = null;

    #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTimeImmutable $created_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGameId(): ?Game
    {
        return $this->game_id;
    }

    public function setGameId(?Game $game_id): static
    {
        $this->game_id = $game_id;

        return $this;
    }

    public function getX(): ?int
    {
        return $this->x;
    }

    public function setX(int $x): static
    {
        $this->x = $x;

        return $this;
    }

    public function getY(): ?int
    {
        return $this->y;
    }

    public function setY(int $y): static
    {
        $this->y = $y;

        return $this;
    }

    public function getPiece(): ?string
    {
        return $this->piece;
    }

    public function setPiece(string $piece): static
    {
        $this->piece = $piece;

        return $this;
    }
}
