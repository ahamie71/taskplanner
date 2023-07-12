<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Task;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

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
         
          //Creer entre 4 et 6 tasks
             for($j =1; $j <= mt_rand(4,10); $j++){
           $task= new Task();
          
           
           $description = implode( $faker->paragraphs(5));
            
             $user= $task->getUser()->get;
            $task->setTitle($faker->word())
                   ->setDescription($description)
                  ->setUser($user)
                   ->setDeadline($faker->dateTimeBetween('-6 months'))    
                   ->setCategory($category)
                   ->setIsDone(true);
                   
                    
            $manager->persist($task);   
   

    }
    $manager->flush();
}
   
}