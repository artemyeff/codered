<?php

namespace App\Context\Category\Repository;

use App\Context\Category\Entity\Category;
use App\Handler\Request\RequestHandlers;
use App\Repository\Traits\RelationsTrait;
use App\Repository\Traits\RemoveTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    use RemoveTrait, RelationsTrait;

    private RequestHandlers $requestHandlers;

    /**
     * CategoryRepository constructor.
     * @param ManagerRegistry $registry
     * @param RequestHandlers $requestHandlers
     */
    public function __construct(ManagerRegistry $registry, RequestHandlers $requestHandlers)
    {
        parent::__construct($registry, Category::class);
        $this->requestHandlers = $requestHandlers;
    }

    /**
     * @param Category $category
     * @return Category
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Category $category): Category
    {
        $manager = $this->getEntityManager();
        $manager->persist($category);
        $manager->flush();
        $manager->refresh($category);

        return $category;
    }

    public function findAllForAdmin(array $relations = []): Paginator
    {
        $builder = $this->createQueryBuilder('c');

        $this->requestHandlers->getSortHandler()->handle($builder);

        $this->requestHandlers->getFilterHandler()->handle(function ($field, $value) use ($builder) {
            switch ($field) {
                case 'id':
                    $builder->andWhere('c.id = :id')->setParameter('id', $value);
                    break;
                case 'name':
                    $builder->andWhere('c.name LIKE :name')
                        ->setParameter('name', '%' . $value . '%');
                    break;
            }
        });

        $builder = $this->withRelations($builder, $relations);

        return $this->requestHandlers->getPaginationHandler()->handle($builder);
    }


    /**
     * @param int $id
     * @param string[] $relations
     * @return Category
     * @throws NonUniqueResultException
     */
    public function findOne(int $id, array $relations = []): Category
    {
        $builder = $this->createQueryBuilder('c');

        $builder
            ->andWhere('c.id = :id')
            ->setParameter('id', $id);

        $builder = $this->withRelations($builder, $relations);

        try {
            return $builder->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            throw new NotFoundHttpException();
        }
    }
}
