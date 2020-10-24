<?php

namespace App\Context\Product\Repository;

use App\Context\Product\Entity\Product;
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
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    use RemoveTrait, RelationsTrait;

    private RequestHandlers $requestHandlers;

    private PaginatorInterface $paginator;

    /**
     * ProductRepository constructor.
     * @param ManagerRegistry $registry
     * @param RequestHandlers $requestHandlers
     */
    public function __construct(
        ManagerRegistry $registry,
        RequestHandlers $requestHandlers,
        PaginatorInterface $paginator
    )
    {
        parent::__construct($registry, Product::class);
        $this->requestHandlers = $requestHandlers;
        $this->paginator = $paginator;
    }

    /**
     * @param Product $product
     * @return Product
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Product $product): Product
    {
        $manager = $this->getEntityManager();
        $manager->persist($product);
        $manager->flush();
        $manager->refresh($product);

        return $product;
    }

    public function findAllForAdmin(array $relations = []): Paginator
    {
        $builder = $this->createQueryBuilder('p');

        $this->requestHandlers->getSortHandler()->handle($builder);

        $this->requestHandlers->getFilterHandler()
            ->handle(static function (string $field, string $value) use ($builder) {
                switch ($field) {
                    case 'id':
                        $builder->andWhere('p.id = :id')->setParameter('id', $value);
                        break;
                    case 'name':
                        $builder->andWhere('p.name LIKE :name')
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
     * @return Product
     * @throws NonUniqueResultException
     */
    public function findOne(int $id, array $relations = []): Product
    {
        $builder = $this->createQueryBuilder('p');

        $builder
            ->andWhere('p.id = :id')
            ->setParameter('id', $id);

        $builder = $this->withRelations($builder, $relations);

        try {
            return $builder->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            throw new NotFoundHttpException();
        }
    }

    /**
     * @return Product[]
     */
    public function findNewest(): array
    {
        return $this->createQueryBuilder('p')
            ->addSelect('category')
            ->innerJoin('p.category', 'category')
            ->orderBy('p.id', 'DESC')
            ->setMaxResults(12)
            ->getQuery()
            ->getResult();
    }

    public function findAllPaginated(int $page, ?int $categoryId = null): PaginationInterface
    {
        $builder = $this->createQueryBuilder('p')
            ->addSelect('category')
            ->leftJoin('p.category', 'category')
            ->orderBy('p.id', 'DESC');

        if (!empty($categoryId)) {
            $builder->andWhere('category.id = :cat_id')
                ->setParameter('cat_id', $categoryId);
        }

        return $this->paginator->paginate(
            $builder,
            $page,
            12
        );
    }
}
