<?php

namespace App\Repository;

use App\Entity\Score;
use App\Enum\Piece;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Score>
 */
class ScoreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Score::class);
    }

//    /**
//     * @return Score[] Returns an array of Score objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Score
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function getSummaryScores(): array
    {
        $query = $this->createQueryBuilder('s')
            ->select(
                "SUM(CASE WHEN s.winner = :x THEN 1 ELSE 0 END) as xWins",
                'SUM(CASE WHEN s.winner = :o THEN 1 ELSE 0 END) as oWins'
            )
            ->setParameters(new ArrayCollection([
                'x' => Piece::X,
                'o' => Piece::O,
            ]))
            ->getQuery();

        $result = $query->getSingleResult();

        return [
            'x' => (int) $result['xWins'],
            'o' => (int) $result['oWins']
        ];
    }
}
