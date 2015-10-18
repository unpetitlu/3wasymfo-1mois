<?php

namespace Troiswa\BackBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MyProject\Proxies\__CG__\OtherProject\Proxies\__CG__\stdClass;
use Troiswa\BackBundle\Entity\Category;

class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        /*
        $category = new Category();
        $category->setTitle('ma première catégorie fixtures');
        $category->setDescription('Description cat');
        $category->setPosition(1);


        $manager->persist($category);
        $manager->flush();

        $this->addReference('category', $category);
        */
        for($i = 0; $i < 2; $i++)
        {
            $this->addReference('category_'.$i, new Category());
        }
    }

    public function getOrder()
    {
        return 1;
    }
}