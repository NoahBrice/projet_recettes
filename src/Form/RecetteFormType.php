<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\Recette;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class RecetteFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('temps')
            // ->add('createdAt')
            // ->add('updatedAt')
            ->add('description')
            ->add('prix')
            ->add('difficulte')
            // ->add('ingredient')
            ->add('ingredient', EntityType::class, [
                // Entity choisis pour faire la liste
                'class' => Ingredient::class,            
                // Propriété utilisé pour crée la liste 
                'choice_label' => 'nom',
            
                // used to render a select box, check boxes or radios
                'multiple' => true,
                // 'expanded' => true,
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                'class' => 'btn btn-primary mt-4'
                ],
                'label' => $options['submit label']
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
            'submit label' => null,
        ]);
    }
}
