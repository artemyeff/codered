<?php
declare(strict_types=1);

namespace App\Handler\Request;

/**
 * Class RequestHandlers
 * @package App\Handler\Request
 */
final class RequestHandlers
{
    private PaginationRequestHandler $paginationHandler;

    private FilterRequestHandler $filterHandler;

    private SortRequestHandler $sortHandler;

    /**
     * RequestHandlers constructor.
     * @param PaginationRequestHandler $paginationHandler
     * @param FilterRequestHandler $filterHandler
     * @param SortRequestHandler $sortHandler
     */
    public function __construct(
        PaginationRequestHandler $paginationHandler,
        FilterRequestHandler $filterHandler,
        SortRequestHandler $sortHandler
    ) {
        $this->paginationHandler = $paginationHandler;
        $this->filterHandler = $filterHandler;
        $this->sortHandler = $sortHandler;
    }

    /**
     * @return PaginationRequestHandler
     */
    public function getPaginationHandler(): PaginationRequestHandler
    {
        return $this->paginationHandler;
    }

    /**
     * @return FilterRequestHandler
     */
    public function getFilterHandler(): FilterRequestHandler
    {
        return $this->filterHandler;
    }

    /**
     * @return SortRequestHandler
     */
    public function getSortHandler(): SortRequestHandler
    {
        return $this->sortHandler;
    }
}
