<?php

namespace App\Entity;

use App\Enum\Piece;
use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private Piece $currentTurn = Piece::X;

    #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTimeImmutable $createdAt;

    /**
     * @var Collection<int, BoardField>
     */
    #[ORM\OneToMany(targetEntity: BoardField::class, mappedBy: 'gameId')]
    private Collection $boardFields;

    #[ORM\OneToOne(mappedBy: 'game', cascade: ['persist', 'remove'])]
    private ?Score $score = null;

    public function __construct()
    {
        $this->boardFields = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, BoardField>
     */
    public function getBoard(): array
    {
        $boardArray = [
            ['', '', ''],
            ['', '', ''],
            ['', '', '']
        ];

        foreach ($this->boardFields as $boardField) {
            $x = $boardField->getX();
            $y = $boardField->getY();
            $boardArray[$x][$y] = $boardField->getPiece();
        }

        return $boardArray;
    }


    public function getScore(): ?Score
    {
        return $this->score;
    }

    public function setScore(Score $score): static
    {
        // set the owning side of the relation if necessary
        if ($score->getGame() !== $this) {
            $score->setGame($this);
        }

        $this->score = $score;

        return $this;
    }

    public function getBoardFields(): Collection
    {
        return $this->boardFields;
    }
}
