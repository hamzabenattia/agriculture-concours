<?php

namespace App\Repository;

use App\Entity\Concours;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;


/**
 * @extends ServiceEntityRepository<Concours>
 */
class ConcoursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator)
    {
        parent::__construct($registry, Concours::class);
    }

    public function search($type, $title, $status)
    {
       
        $query = $this->createQueryBuilder('c')
        ->orderBy('c.id', 'DESC');

        if ($type != "") {
            $query->andWhere('c.type = :type')
                ->setParameter('type', $type)
                ;
        }

        if ($title != "") {
            $query->andWhere('c.title like :title')
                ->setParameter('title', '%'.$title.'%')
                ;
        }

        if ($status != "") {
            $query->andWhere('c.status = :status')
                ->setParameter('status', $status)
                ;
        }


        $request = Request::createFromGlobals();

        $pagination = $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );


        return $pagination;
    }

       
       public function findCandidatNumber()
       {
           return $this->createQueryBuilder('c')
            
               ->orderBy('c.id', 'ASC')
               ->select('count(c.id) as nbConcours')
               ->setMaxResults(10)
               ->getQuery()
               ->getSingleScalarResult();
           ;
          
       }

    //    public function findOneBySomeField($value): ?Concours
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

}
