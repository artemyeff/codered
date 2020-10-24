<?php
declare(strict_types=1);

namespace App\Repository\Traits;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Select;
use Doctrine\ORM\QueryBuilder;

/**
 * Trait Relations
 * @package App\Repository\Traits
 *
 * @mixin ServiceEntityRepository
 */
trait RelationsTrait
{
    /**
     * @param QueryBuilder $builder
     * @param string[] $relations
     * @return QueryBuilder
     */
    protected function withRelations(QueryBuilder $builder, array $relations): QueryBuilder
    {
        $builder = clone $builder;

        if (empty($relations)) {
            return $builder;
        }

        $rootAlias = $builder->getRootAliases()[0];
        foreach ($relations as $relation) {
            $includeParts = explode('.', $relation);

            $relation = $includeParts[0];
            $alias = $rootAlias . '.' . $relation;
            $this->addInclude($builder, $alias, $relation);

            $includePartsLength = count($includeParts);

            for ($i = 0; $i <= $includePartsLength - 1; $i++) {
                if (isset($includeParts[$i + 1])) {
                    $this->addInclude(
                        $builder,
                        $includeParts[$i] . '.' . $includeParts[$i + 1],
                        $includeParts[$i + 1]
                    );
                }
            }
        }

        return $builder;
    }


    /**
     * @param QueryBuilder $builder
     * @param string $alias
     * @param string $include
     */
    protected function addInclude(QueryBuilder $builder, string $alias, string $include): void
    {
        if (!in_array($include, $builder->getAllAliases(), true)) {
            $builder
                ->leftJoin($alias, $include)
                ->addSelect($include);
        } else {
            /** @var Select $select */
            foreach ($builder->getDQLPart('select') as $select) {
                if (in_array($include, $select->getParts(), true)) {
                    return;
                }
            }

            $builder->addSelect($include);
        }
    }
}
