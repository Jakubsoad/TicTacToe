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

    #[ORM\Column(length: 1, options: ['default' => 'X'])]
    private string $current_turn = 'X';

    #[ORM\Column(options: ['default' => 0])]
    private int $x_score = 0;

    #[ORM\Column(options: ['default' => 0])]
    private int $o_score = 0;

    #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTimeImmutable $created_at;

    /**
     * @var Collection<int, Board>
     */
    #[ORM\OneToMany(targetEntity: Board::class, mappedBy: 'game_id')]
    private Collection $board;

    /**
     * @var Collection<int, Score>
     */
    #[ORM\OneToMany(targetEntity: Score::class, mappedBy: 'game_id')]
    private Collection $scores;

    public function __construct()
    {
        $this->board = new ArrayCollection();
        $this->scores = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getOScore(): ?int
    {
        return $this->o_score;
    }

    public function setOScore(int $o_score): static
    {
        $this->o_score = $o_score;

        return $this;
    }

    /**
     * @return Collection<int, Board>
     */
    public function getBoard(): Collection
    {
        return $this->board;
    }

    public function addBoard(Board $board): static
    {
        if (!$this->board->contains($board)) {
            $this->board->add($board);
            $board->setGameId($this);
        }

        return $this;
    }

    public function removeBoard(Board $board): static
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
}
