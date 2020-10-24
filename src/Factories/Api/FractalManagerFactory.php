<?php
declare(strict_types=1);

namespace App\Factories\Api;

use App\Api\Serializer\DataArraySerializer;
use League\Fractal\Manager;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class FractalManagerFactory
 * @package App\Factories\Api
 */
final class FractalManagerFactory
{
    /**
     * @param RequestStack $requestStack
     * @return Manager
     */
    public function create(RequestStack $requestStack): Manager
    {
        $manager = new Manager();
        $manager->setSerializer(new DataArraySerializer());

        $query = $requestStack->getCurrentRequest()->query->all();

        if (isset($query['include']) && is_string($query['include'])) {
            $manager->parseIncludes($query['include']);
        }

        return $manager;
    }
}
