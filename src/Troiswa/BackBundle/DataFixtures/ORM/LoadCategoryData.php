<?php

namespace Troiswa\BackBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Troiswa\BackBundle\Entity\Category;

class LoadCategoryData implements FixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $category = new Category();
        $category->setTitle('ma première catégorie fixtures');
        $category->setDescription('Description cat');
        $category->setPosition(1);


        $manager->persist($category);
        $manager->flush();
    }
}