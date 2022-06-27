<?php

namespace App\Repository;

use App\Entity\Animal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Animal>
 *
 * @method Animal|null find($id, $lockMode = null, $lockVersion = null)
 * @method Animal|null findOneBy(array $criteria, array $orderBy = null)
 * @method Animal[]    findAll()
 * @method Animal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnimalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Animal::class);
    }

    public function add(Animal $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Animal $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
    * @return Animal[]
    */
   public function findAllGreaterThan($weight)
   {
        // $entity = $this->getEntityManager();
        // return $entity->createQuery('
        //     SELECT a
        //     FROM App\Entity\Animal a
        //     WHERE a.weight > :weight
        //     ORDER BY a.email DESC
        // ')->setParameter('weight',$weight)
        // ->getArrayResult()
        // ;
        //SELECT n.content, c.name FROM `note` n INNER join cat_note c on n.cat_id = c.id
       return $this->createQueryBuilder('a')
       ->select('a.id,a.name')
        ->where('a.weight > :weight')
        ->innerJoin('a.cat','c')
        ->setParameter('weight',$weight)
        ->orderBy('a.email','DESC')
        ->getQuery()
        ->getResult()
       ;
   }

//    SELECT a.name, c.name
// FROM animal a INNER join cat_ani c on a.cat_id = c.id
   /**
    * @return Animal[]
    */
    public function findAniCat(){
        return $this->createQueryBuilder('a')
        ->select('a.name as animalName, c.name as CatName')
        ->innerJoin('a.cat','c')
        ->getQuery()
        ->getResult()
        ;
    }


//    /**
//     * @return Animal[] Returns an array of Animal objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Animal
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
