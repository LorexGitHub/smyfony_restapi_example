<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Product;
use DateTime;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $product = new Product;

        $product->setName('Product 1');
        $product->setSize(10);
        $product->setPublishedOn(new \DateTime('2025-07-12'));

        $manager->persist($product);

        $product = new Product;

        $product->setName('Product 2');
        $product->setSize(20);
        $product->setPublishedOn(new \DateTime('2025-07-13'));
        $product->setIsAvailable(false);

        $manager->persist($product);

        $manager->flush();
    }
}
