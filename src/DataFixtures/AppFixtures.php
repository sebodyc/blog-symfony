<?php

namespace App\DataFixtures;

use App\Entity\Post;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        for ($i = 0; $i < 20; $i++) {
            $post = new Post;
            $post->setTitle("article num $i")
                ->setContent("contenu de larticle $i")
                ->setCreatedAt(new DateTime());

            $manager->persist($post);
        }

        $manager->flush();
    }
}
