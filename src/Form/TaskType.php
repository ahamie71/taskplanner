<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class,[
                'required'=> 'required',
            ])
            ->add('description',TextareaType::class,[
                'required' => 'required',
            ])

            ->add('deadline',DateType::class,[

            ])
          
            ->add('category',EntityType::class,[
                'class' => Category::class,
                'choice_label'=> 'nom'
            ])


         ->add('submit', SubmitType::class, [
            'label' => "ADD",
            'attr' => [
                'class' => 'btn btn-primary'
            ] 
        ]);
        

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
