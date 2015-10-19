<?php

namespace Troiswa\BackBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Troiswa\BackBundle\Entity\Product;

class LoadProductData extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');
        
        for($i = 0; $i < 2; $i++)
        {
            dump($this->getReference('category_'.$i));
        }
        die;
        /*
        $product = new Product();
        $product->setTitle('Mon super produit fixtures');
        $product->setDescription('Lorem ipsum');
        $product->setPrice(10);
        $product->setQuantity(1);
        $product->setCategory($this->getReference('category'));


        $manager->persist($product);
        $manager->flush();
        */
    }

    public function getOrder()
    {
        return 2;
    }
}