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
            'SELECT ing
        FROM App\entity\Ingredient ing
        WHERE ing.prix > :prix
        ORDER BY ing.prix ASC'
        )->setParameter('prix', $prix);
        return $query->getResult();
    }


    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Méthode Query Builder 
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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


    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Méthode SQL 
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function find_all_sql(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT * FROM ingredient ing
        ';
        $resultSet = $conn->executeQuery($sql);
        // dd($resultSet->fetchAllAssociative());
        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    public function find_ingredient_sql(string $nom): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT * 
        FROM ingredient ing
        WHERE ing.nom = :nom 
        ';
        $resultSet = $conn->executeQuery($sql, ['nom' => $nom]);
        // dd($resultSet->fetchAllAssociative());
        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    public function find_ingredient_nom_prix_sql(string $nom, int $prix): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT * 
        FROM ingredient ing
        WHERE ing.nom = :nom 
        AND ing.prix = :prix
        ';
        $resultSet = $conn->executeQuery($sql, ['nom' => $nom, 'prix' => $prix]);
        // dd($resultSet->fetchAllAssociative());
        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    public function find_ingredient_nomLike_prix_sql(string $nom, int $prix): array
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT * 
        FROM ingredient ing
        WHERE ing.nom like "%" :nom "%" 
        AND ing.prix = :prix
        ';
        $resultSet = $conn->executeQuery($sql, ['nom' => $nom, 'prix' => $prix]);
        // dd($resultSet->fetchAllAssociative());
        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    public function find_ingredient_by_prix_sql(int $prix): array
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT * 
        FROM ingredient ing
        WHERE ing.nom like "%" :nom "%" 
        AND ing.prix = :prix
        ';
        $resultSet = $conn->executeQuery($sql, ['prix' => $prix]);
        // dd($resultSet->fetchAllAssociative());
        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    public function find_ingredient_by_prix_and_name_sql(int $prix, string $nom): array
    {

        return $this->createQueryBuilder("ingredient")
            ->where("ingredient.prix = :prix")
            ->setParameter('prix', $prix)
            ->andWhere("ingredient.nom = :nom")
            ->setParameter('nom', $nom)
            ->getQuery()
            ->getResult();
    }
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Méthode DQL
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function find_all_dql(): array
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT ing
        FROM App\entity\Ingredient ing'
        );
        return $query->getResult();
    }

    function find_ingredient_test_dql()
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT ing
        FROM App\entity\Ingredient ing
        WHERE ing.nom = :nom'
        )->setParameter('nom', 'test');
        return $query->getResult();
    }

    function find_ingredient_test_10_dql()
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT ing
        FROM App\entity\Ingredient ing
        WHERE ing.nom = :nom
        AND ing.prix = :prix'
        )->setParameter('nom', 'test')
            ->setParameter('prix', '15');
        return $query->getResult();
    }


    function find_ingredient_tes_dql()
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT ing
        FROM App\entity\Ingredient ing
        WHERE ing.nom like :nom'
        )->setParameter('nom', '%' . 'tes' . '%');
        return $query->getResult();
    }


    function find_ingredient_by_price_dql($prix)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT ing
        FROM App\entity\Ingredient ing
        WHERE ing.prix = :prix'
        )->setParameter('prix', $prix);
        return $query->getResult();
    }

    function find_ingredient_by_price_and_name_dql($prix, $nom)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT ing
        FROM App\entity\Ingredient ing
        WHERE ing.prix = :prix
        and  ing.nom = :nom'
        )->setParameter('prix', $prix)
            ->setParameter('nom', $nom);
        return $query->getResult();
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
