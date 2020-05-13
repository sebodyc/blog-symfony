<?php

namespace App\DataFixtures;

use App\Entity\Cars;
use App\Entity\Category;
use App\Entity\Comment;
use DateTime;
use Faker\Factory;
use App\Entity\Post;
use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = Factory::create('fr_FR');
        $faker->addProvider(new \Faker\Provider\Fakecar($faker));
        $faker->addProvider(new \Bluemmb\Faker\PicsumPhotosProvider($faker));


        $titles = ['Sport', 'Actualité', 'Automobile', 'Gastronomie'];
        $tags = [];
        foreach ($titles as $title) {
            $tag = new Tag;
            $tag->setTitle($title);
            $tags[] = $tag;
            $manager->persist($tag);
        }

        for ($c = 0; $c < 4; $c++) {
            $category = new Category;
            $category->setTitle($faker->catchPhrase);

            $manager->persist($category);

            for ($i = 0; $i < 20; $i++) {
                $post = new Post;
                $post->setTitle($faker->catchPhrase)
                    ->setContent($faker->paragraph(5, true))
                    ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                    ->setCategory($category)
                    ->setImage($faker->imageUrl(400, 400, true));


                for ($c = 0; $c < mt_rand(0, 4); $c++) {

                    $comment = new Comment;
                    $comment->setAuthor($faker->userName)
                        ->setContent($faker->paragraph(2, true))
                        ->setCreatedAt($faker->dateTimeBetween('-6month'))
                        ->setPost($post);

                    $manager->persist($comment);
                }


                $postTags = $faker->randomElements($tags, mt_rand(0, 3));

                foreach ($postTags as $tag) {
                    $post->addTag($tag);
                }

                $manager->persist($post);
            }
        }

        for ($i = 0; $i < 10; $i++) {
            $cars = new Cars;
            $cars->setBrand($faker->vehicleBrand)
                ->setModel($faker->vehicleModel)
                ->setRegistration($faker->vehicleRegistration)
                ->setPhoto($faker->imageUrl(400, 400, true))
                ->setDescription($faker->paragraph(5, true));


            $manager->persist($cars);
        }



        $manager->flush();
    }
}
