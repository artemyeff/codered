<?php
declare(strict_types=1);

namespace App\Handler\Request;

use App\Exceptions\Validation\HttpException;
use App\Validator\Api\PaginationRequestValidator;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class PaginationRequestHandler
 * @package App\Handler\Request
 */
class PaginationRequestHandler
{
    public const DEFAULT_PAGE_SIZE = 20;

    private RequestStack $requestStack;

    private PaginationRequestValidator $validator;

    /**
     * PaginationRequestHandler constructor.
     * @param RequestStack $requestStack
     * @param PaginationRequestValidator $validator
     */
    public function __construct(RequestStack $requestStack, PaginationRequestValidator $validator)
    {
        $this->requestStack = $requestStack;
        $this->validator = $validator;
    }

    /**
     * @param QueryBuilder $builder
     * @return Paginator|null
     * @throws HttpException
     */
    public function handle(QueryBuilder $builder): ?Paginator
    {
        $currentRequest = $this->requestStack->getCurrentRequest();

        if (null === $currentRequest) {
            return null;
        }

        $page = $currentRequest->query->get('page');
        $size = $currentRequest->query->get('size');

        $this->validator->validate([
            'page' => $page,
            'size' => $size,
        ]);

        $currentPage = $page ?? 1;

        if ('0' === (string)$size) {
            return null;
        }

        $sizePage = $size ?? self::DEFAULT_PAGE_SIZE;

        $offset = ($currentPage - 1) * $sizePage;
        $builder
            ->setFirstResult($offset)
            ->setMaxResults($sizePage);

        return new Paginator($builder);
    }
}
