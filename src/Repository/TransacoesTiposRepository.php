<?php

namespace App\Repository;

use App\Entity\TransacoesTipos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TransacoesTipos>
 *
 * @method TransacoesTipos|null find($id, $lockMode = null, $lockVersion = null)
 * @method TransacoesTipos|null findOneBy(array $criteria, array $orderBy = null)
 * @method TransacoesTipos[]    findAll()
 * @method TransacoesTipos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransacoesTiposRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TransacoesTipos::class);
    }

    public function save(TransacoesTipos $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TransacoesTipos $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return TransacoesTipos[] Returns an array of TransacoesTipos objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TransacoesTipos
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
