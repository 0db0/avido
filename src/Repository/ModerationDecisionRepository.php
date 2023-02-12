<?php

namespace App\Repository;

use App\Entity\ModerationDecision;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ModerationDecision>
 *
 * @method ModerationDecision|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModerationDecision|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModerationDecision[]    findAll()
 * @method ModerationDecision[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModerationDecisionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModerationDecision::class);
    }

    public function save(ModerationDecision $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ModerationDecision $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ModerationDecision[] Returns an array of ModerationDecision objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ModerationDecision
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
