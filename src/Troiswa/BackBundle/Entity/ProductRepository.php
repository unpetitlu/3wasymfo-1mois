<?php

namespace Troiswa\BackBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends EntityRepository
{
    public function getCountProduct()
    {
        $query = $this->getEntityManager()
            ->createQuery(
                "
                 SELECT COUNT(prod) AS nb
                 FROM TroiswaBackBundle:Product prod"
            );
        return $query->getSingleScalarResult();
    }

    public function findAllMaisonQuery()
    {
        $query = $this->getEntityManager()
            ->createQuery(
                "
                 SELECT prod
                 FROM TroiswaBackBundle:Product prod"
            );
        return $query->getResult();
    }

    public function findAllMaisonQueryBuilder()
    {
        $query = $this->createQueryBuilder("prod")
            ->getQuery();
        return $query->getResult();
    }

    public function findAllMaisonQueryBuilder2()
    {

        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('prod')
            ->from('TroiswaBackBundle:Product', 'prod')
            ->getQuery();

        /*
        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('prod')
            ->from($this->_entityName, 'prod')
            ->getQuery();
        */

        return $query->getResult();
    }

    public function findTitleProductQueryBuilder()
    {

        $query = $this->createQueryBuilder("prod")
            ->select('prod.title')
            ->getQuery();
        return $query->getResult();

        /*
        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('prod')
            ->from('TroiswaBackBundle:Product', 'prod')
            ->getQuery();
        */
        return $query->getResult();
    }

    public function findMaisonQuery($idprod)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                "
                 SELECT prod
                 FROM TroiswaBackBundle:Product prod
                 WHERE prod.id = :id"
            )
            //->setParameters(['id' => $idprod])
            ->setParameter('id', $idprod);
        return $query->getResult();
    }

    public function findAllProductWithCategory()
    {
        $query = $this->getEntityManager()
            ->createQuery(
                "
                 SELECT prod, cat
                 FROM TroiswaBackBundle:Product prod
                 LEFT JOIN prod.category cat"
            );
        return $query->getResult();
    }
}
