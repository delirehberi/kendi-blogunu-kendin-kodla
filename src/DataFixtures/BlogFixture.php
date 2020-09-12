<?php

namespace App\DataFixtures;

use App\Entity\Blog;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BlogFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $blog_item = new Blog();
        $blog_item->setTitle("Hello world!")
                  ->setSlug("hello-world")
                  ->setContent("Demo content!")
              ->setCreatedAt(new \DateTime());
        $manager->persist($blog_item);

        $manager->flush();
    }
}
