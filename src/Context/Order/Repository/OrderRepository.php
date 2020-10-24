<?php

namespace App\Context\Order\Repository;

use App\Context\Order\Entity\Order;
use App\Context\Product\Entity\Product;
use App\Handler\Request\RequestHandlers;
use App\Repository\Traits\RelationsTrait;
use App\Repository\Traits\RemoveTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    use RemoveTrait, RelationsTrait;

    private RequestHandlers $requestHandlers;

    /**
     * ProductRepository constructor.
     * @param ManagerRegistry $registry
     * @param RequestHandlers $requestHandlers
     */
    public function __construct(
        ManagerRegistry $registry,
        RequestHandlers $requestHandlers
    )
    {
        parent::__construct($registry, Order::class);
        $this->requestHandlers = $requestHandlers;
    }

    public function findAllForAdmin(array $relations = []): Paginator
    {
        $builder = $this->createQueryBuilder('o');

        $this->requestHandlers->getSortHandler()->handle($builder);

        $this->requestHandlers->getFilterHandler()
            ->handle(static function (string $field, string $value) use ($builder) {
                switch ($field) {
                    case 'id':
                        $builder->andWhere('o.id = :id')->setParameter('id', $value);
                        break;
                }
            });

        $builder = $this->withRelations($builder, $relations);

        return $this->requestHandlers->getPaginationHandler()->handle($builder);
    }

    public function findOne(int $id, array $relations = []): Order
    {
        $builder = $this->createQueryBuilder('o');

        $builder
            ->andWhere('o.id = :id')
            ->setParameter('id', $id);

        $builder = $this->withRelations($builder, $relations);

        try {
            return $builder->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            throw new NotFoundHttpException();
        }
    }
}
