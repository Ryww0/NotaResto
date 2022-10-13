<?php

namespace App\Repository;

use App\Entity\Restaurant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Restaurant>
 *
 * @method Restaurant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Restaurant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Restaurant[]    findAll()
 * @method Restaurant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Restaurant::class);
    }

    public function save(Restaurant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Restaurant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllRestaurantOrderByOpinionDesc(): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder("r");
        $queryBuilder->select("r as restaurant , AVG(opinion.note) as avg");
        $queryBuilder->leftJoin('App\Entity\Opinion', 'opinion', Join::WITH, 'r.id = opinion.restaurant');
        $queryBuilder->orderBy('avg', 'DESC');
        $queryBuilder->groupBy('r');
        return $queryBuilder;
    }

    public function findAllRestaurantByBestOpinionOrderByOpinionDesc(): mixed
    {
        $queryBuilder = $this->createQueryBuilder("r");
        $queryBuilder->select("r as restaurant , AVG(opinion.note) as avg");
        $queryBuilder->leftJoin('App\Entity\Opinion', 'opinion', Join::WITH, 'r.id = opinion.restaurant');
        $queryBuilder->orderBy('avg', 'DESC');
        $queryBuilder->groupBy('r');
        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findRestaurant(Restaurant $restaurant): ?array
    {
        $queryBuilder = $this->createQueryBuilder("r");
        $queryBuilder->select("r as restaurant , AVG(opinion.note) as avg");
        $queryBuilder->where("r.id = :rest");
        $queryBuilder->setParameter(":rest", $restaurant->getId());
        $queryBuilder->leftJoin('App\Entity\Opinion', 'opinion', Join::WITH, 'r.id = opinion.restaurant');
        $queryBuilder->orderBy('avg', 'DESC');
        $queryBuilder->groupBy('r');
        return $queryBuilder->getQuery()->getOneOrNullResult(); // on renvoie le résultat
    }

    public function findRestaurantWherePostcodeLikeSearch($where): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder("p");
        $queryBuilder->select("p as restaurant , AVG(opinion.note) as avg");
        $queryBuilder->leftJoin('App\Entity\Opinion', 'opinion', Join::WITH, 'p.id = opinion.restaurant');
        $queryBuilder->where(' p.postcode like :w');
        $queryBuilder->setParameter(':w', $where . '%');
        $queryBuilder->orderBy('avg', 'DESC');
        $queryBuilder->groupBy('p');
        return $queryBuilder;
    }

//    /**
//     * @return Restaurant[] Returns an array of Restaurant objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Restaurant
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
