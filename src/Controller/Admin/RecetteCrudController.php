<?php

namespace App\Controller\Admin;

use App\Entity\Recette;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use phpDocumentor\Reflection\Types\Integer;

class RecetteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Recette::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // ...
            ->showEntityActionsInlined()
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('nom'),
            MoneyField::new('Prix')->setCurrency('EUR')->setCustomOption('storedAsCents', false),
            DateTimeField::new('created_at'),
            DateTimeField::new('updated_at'),
            IntegerField::new('temps'),
            AssociationField::new('ingredient')->setFormTypeOption('by_reference', false)->formatValue(function ($value, $entity) {
                $ingredients = $entity->getIngredient();
                $label = "";
                foreach($ingredients as $ingredient) {
                 $label = $label.$ingredient->getNom()."(". $ingredient->getId() .")".", ";
                }
                return $label;
                })
        ];
    }
}
