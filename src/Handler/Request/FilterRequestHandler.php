<?php
declare(strict_types=1);

namespace App\Handler\Request;

use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class FilterRequestHandler
 * @package App\Handler\Request
 */
class FilterRequestHandler
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
     * @param \Closure $callback
     */
    public function handle(\Closure $callback): void
    {
        $query = $this->requestStack->getCurrentRequest()->query->all();
        if (isset($query['filter'])) {
            foreach ($query['filter'] as $field => $value) {
                if (!empty($value)) {
                    $callback((string)$field, (string)$value);
                }
            }
        }
    }
}
