<?php

namespace App\EventListener;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use \DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Entity\Ingredient;

// class IngredientListener
// {
//     public function preUpdate(Ingredient $ingredient, LifecycleEventArgs $event): void
//     {
//         $ingredient->setUpdatedAt(new \DateTimeImmutable());
//     }
// }

// use Doctrine\ORM\Event\PreUpdateEventArgs;
// use Doctrine\ORM\Mapping\PreUpdate;

#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: Ingredient::class)]
class IngredientListener
{
    public function preUpdate(Ingredient $ingredient, LifecycleEventArgs $event)
    {
        $ingredient->setUpdatedAt(new \DateTimeImmutable());
    }
}
