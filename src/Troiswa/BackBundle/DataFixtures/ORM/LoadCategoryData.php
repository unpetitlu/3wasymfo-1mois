<?php

namespace Troiswa\BackBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Troiswa\BackBundle\Entity\Category;

class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $category = new Category();
        $category->setTitle('ma première catégorie fixtures');
        $category->setDescription('Description cat');
        $category->setPosition(1);


        $manager->persist($category);
        $manager->flush();

        $this->addReference('category', $category);
    }

    public function getOrder()
    {
        return 1;
    }
}