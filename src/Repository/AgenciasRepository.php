<?php
namespace App\Repository;
use App\Entity\Agencias;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Agencias>
 *
 * @method Agencias|null find($id, $lockMode = null, $lockVersion = null)
 * @method Agencias|null findOneBy(array $criteria, array $orderBy = null)
 * @method Agencias[]    findAll()
 * @method Agencias[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AgenciasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Agencias::class);
    }

    public function save(Agencias $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Agencias $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
