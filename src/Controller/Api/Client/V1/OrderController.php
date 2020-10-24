<?php

declare(strict_types=1);

namespace App\Controller\Api\Client\V1;

use App\Context\Order\Service\OrderService;
use App\Context\Product\Repository\ProductRepository;
use App\Controller\Api\AbstractApiController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use JsonException;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Rest\Route("/orders")
 *
 * Class CartController
 * @package App\Controller\Api\Client\V1
 */
class OrderController extends AbstractApiController
{
    /**
     * @Rest\Post("")
     *
     * @param Request $request
     * @param OrderService $service
     * @return View
     * @throws JsonException
     */
    public function create(Request $request, OrderService $service)
    {
        $payload = $this->getPayload();
        $service->create($payload['phone'] ?? 0);

        return $this->view([]);
    }
}
