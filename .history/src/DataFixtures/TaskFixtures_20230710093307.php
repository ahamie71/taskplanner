<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category;
use Faker\Factory;
class TaskFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        //on vz creer 3 catégorie fakées
         for($i = 1; $i <= 3; $i++){
            $category=new Category();
            $category->setNom($faker->word());
                $manager->persist($category);        

         }

        
        // $manager->persist($product);

   
    }
}
