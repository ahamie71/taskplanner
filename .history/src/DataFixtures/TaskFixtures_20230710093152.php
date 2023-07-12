<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TaskFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');

        //on vz creer 3 catégorie fakées
         for($i = 1; $i <= 3; $i++){
            $category=new Category();
            $category->setTitle($faker->word())
                     ->setDescription($faker->paragraph());

                $manager->persist($category);        

         }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
