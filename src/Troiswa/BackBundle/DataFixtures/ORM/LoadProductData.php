<?php

namespace Troiswa\BackBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Troiswa\BackBundle\Entity\Product;

class LoadProductData implements FixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $product = new Product();
        $product->setTitle('Mon super produit fixtures');
        $product->setDescription('Lorem ipsum');
        $product->setPrice(10);
        $product->setQuantity(1);


        $manager->persist($product);
        $manager->flush();
    }
}