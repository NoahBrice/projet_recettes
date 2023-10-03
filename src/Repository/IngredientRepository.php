<?php

namespace App\Repository;

use App\Entity\Ingredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ingredient>
 *
 * @method Ingredient|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ingredient|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ingredient[]    findAll()
 * @method Ingredient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IngredientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ingredient::class);
    }

    //    /**
    //     * @return Ingredient[] Returns an array of Ingredient objects
    //     */

    public function findAllGreaterThanPrice(int $prix): array
    {

        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT ingredient
        FROM App\entity\Ingredient ingredient
        WHERE ingredient.prix > :prix
        ORDER BY ingredient.prix ASC'
        )->setParameter('prix', $prix);
        return $query->getResult();
    }

    public function find_ingredient(string $nom): array
    {

        return $this->createQueryBuilder("ingredient")
            ->where("ingredient.nom = :nom")
            ->setParameter('nom', $nom)
            ->getQuery()
            ->getResult();
    }

    public function find_ingredient_nom_prix(string $nom, int $prix): array
    {

        return $this->createQueryBuilder("ingredient")
            ->where("ingredient.nom = :nom")
            ->setParameter('nom', $nom)
            ->andWhere("ingredient.prix = :prix")
            ->setParameter('prix', $prix)
            ->getQuery()
            ->getResult();
    }

    public function find_ingredient_nomLike_prix(string $nom, int $prix): array
    {

        return $this->createQueryBuilder("ingredient")
            ->where("ingredient.nom LIKE :nom")
            ->setParameter('nom', '%' . $nom . '%')
            ->andWhere("ingredient.prix = :prix")
            ->setParameter('prix', $prix)
            ->getQuery()
            ->getResult();
    }

    public function find_ingredient_by_prix(int $prix): array
    {

        return $this->createQueryBuilder("ingredient")
            ->where("ingredient.prix = :prix")
            ->setParameter('prix', $prix)
            ->getQuery()
            ->getResult();
    }

    public function find_ingredient_by_prix_and_name(int $prix, string $nom): array
    {

        return $this->createQueryBuilder("ingredient")
            ->where("ingredient.prix = :prix")
            ->setParameter('prix', $prix)
            ->andWhere("ingredient.nom = :nom")
            ->setParameter('nom', $nom)
            ->getQuery()
            ->getResult();
    }

    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Ingredient
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
