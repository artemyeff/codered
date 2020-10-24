<?php
declare(strict_types=1);

namespace App\Handler\Request;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class SortRequestHandler
 * @package App\Handler\Request
 */
class SortRequestHandler
{
    private RequestStack $requestStack;

    /**
     * FilterHandler constructor.
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @param QueryBuilder $builder
     * @param array $fields
     */
    public function handle(QueryBuilder $builder, array $fields = []): void
    {
        $query = $this->requestStack->getCurrentRequest()->query->all();
        $rootPrefix = $builder->getRootAliases()[0];

        if (isset($query['sort'])) {
            foreach ($query['sort'] as $field => $order) {
                if (array_key_exists($field, $fields)) {
                    if ($fields[$field] instanceof \Closure) {
                        $fields[$field]($builder, $field, $order);
                        continue;
                    }

                    $builder->addOrderBy($rootPrefix . '.' . $this->toSnakeCase($field), $fields[$field]);
                    continue;
                }
                $builder->addOrderBy($rootPrefix . '.' . $this->toSnakeCase($field), $order);
            }
        }
    }

    /**
     * @param string $camelCase
     * @return string
     */
    private function toSnakeCase(string $camelCase): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $camelCase));
    }
}
