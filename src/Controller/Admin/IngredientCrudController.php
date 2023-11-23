<?php

namespace App\Controller\Admin;

use App\Entity\Ingredient;
use DateTime;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use phpDocumentor\Reflection\Types\Integer;

class IngredientCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Ingredient::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('nom'),
            MoneyField::new('Prix')->setCurrency('EUR')->setCustomOption('storedAsCents', false),
            DateTimeField::new('created_at')->onlyOnIndex(),
            DateTimeField::new('updated_at')->onlyOnIndex(),
            SlugField::new('slug')->setTargetFieldName('nom')->onlyOnIndex(),
            AssociationField::new('recettes')->setFormTypeOption('by_reference', false)->formatValue(function ($value, $entity) {
                $recettes = $entity->getRecettes();
                $label = "";
                foreach ($recettes as $recette) {
                    $label = $label . $recette->getNom() . "(" . $recette->getId() . ")" . ", ";
                }
                return $label;
            }),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // ...
            ->showEntityActionsInlined();
    }
}
