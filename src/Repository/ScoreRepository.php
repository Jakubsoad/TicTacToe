<?php

namespace App\Repository;

use App\Entity\Score;
use App\Enum\Piece;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;

class ScoreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Score::class);
    }

    public function getSummaryScores(): array
    {
        $query = $this->createQueryBuilder('s')
            ->select(
                "SUM(CASE WHEN s.winner = :x THEN 1 ELSE 0 END) as xWins",
                'SUM(CASE WHEN s.winner = :o THEN 1 ELSE 0 END) as oWins'
            )
            ->setParameter('x', Piece::X->value)
            ->setParameter('o', Piece::O->value)
            ->getQuery();

        $result = $query->getOneOrNullResult();

        return [
            'x' => (int) $result['xWins'],
            'o' => (int) $result['oWins']
        ];
    }
}
