<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, options: ['default' => 'X'])]
    private string $currentTurn = 'X';

    #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTimeImmutable $createdAt;

    /**
     * @var Collection<int, BoardField>
     */
    #[ORM\OneToMany(targetEntity: BoardField::class, mappedBy: 'gameId')]
    private Collection $boardFields;

    /**
     * @var Collection<int, Score>
     */
    #[ORM\OneToMany(targetEntity: Score::class, mappedBy: 'gameId')]
    private Collection $scores;

    public function __construct()
    {
        $this->boardFields = new ArrayCollection();
        $this->scores = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCurrentTurn(): ?string
    {
        return $this->currentTurn;
    }

    public function setCurrentTurn(string $currentTurn): static
    {
        $this->currentTurn = $currentTurn;

        return $this;
    }

    public function getXScore(): ?int
    {
        return $this->xScore;
    }

    public function setXScore(int $xScore): static
    {
        $this->xScore = $xScore;

        return $this;
    }

    public function getOScore(): ?int
    {
        return $this->oScore;
    }

    public function setOScore(int $oScore): static
    {
        $this->oScore = $oScore;

        return $this;
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

    public function addBoard(BoardField $board): static
    {
        if (!$this->board->contains($board)) {
            $this->board->add($board);
            $board->setGameId($this);
        }

        return $this;
    }

    public function removeBoard(BoardField $board): static
    {
        if ($this->board->removeElement($board)) {
            // set the owning side to null (unless already changed)
            if ($board->getGameId() === $this) {
                $board->setGameId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Score>
     */
    public function getScores(): Collection
    {
        return $this->scores;
    }

    public function getXScores()
    {
        return $this->scores->filter(fn(Score $score) => $score->getScore() === 'X')->count();
    }

    public function getOScores()
    {
        return $this->scores->filter(fn(Score $score) => $score->getScore() === 'O')->count();
    }

    public function addScore(Score $score): static
    {
        if (!$this->scores->contains($score)) {
            $this->scores->add($score);
            $score->setGameId($this);
        }

        return $this;
    }

    public function removeScore(Score $score): static
    {
        if ($this->scores->removeElement($score)) {
            // set the owning side to null (unless already changed)
            if ($score->getGameId() === $this) {
                $score->setGameId(null);
            }
        }

        return $this;
    }

    public function getTurn(): string
    {
        return $this->boardFields->count() % 2 === 0 ? $turn = 'X' : $turn = 'O';
    }
}
