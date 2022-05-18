<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        $mainCategory = new Category();
        $mainCategory->setName('Türkçe İçerikler')
                     ->setSlug('turkce-icerikler');
        $manager->persist($mainCategory);

        $category = new Category();
        $category->setName('Gündem')
                 ->setSlug('gundem')
             ->setParent($mainCategory);
        $manager->persist($category);

        $category2 = new Category();
        $category2->setName("Demo")
            ->setSlug("demo");
        $manager->persist($category2);
        $manager->flush();
    }
}
