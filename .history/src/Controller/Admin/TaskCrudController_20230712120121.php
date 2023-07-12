<?php

namespace App\Controller\Admin;

use App\Entity\Task;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TaskCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Task::class;
    }


    public function configureFields(string $pageName EntityManagerInterface $entityMan): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
            DateTimeField::new('deadline'),
            BooleanField::new('isDone'),
            $someRepository = $this->entityManager->getRepository(User::class),
           AssociationField::new('user')->setQueryBuilder(
         $someRepository->createQueryBuilder('user')
           ->where('user.nom = :nom')
          ->orderBy('user.nom', 'ASC'),
           ),
            AssociationField::new('Category')

        ];

    }
}
    
   

