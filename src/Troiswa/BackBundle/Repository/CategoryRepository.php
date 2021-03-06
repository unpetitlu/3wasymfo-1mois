<?php

namespace Troiswa\BackBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends EntityRepository
{
    // Return createQueryBuilder beacause use in FormType
    public function findTitleOrderPositionForForm()
    {
        $query = $this->createQueryBuilder('cat')
                        ->orderBy('cat.title', 'DESC');
        return $query;
    }
}
