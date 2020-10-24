<?php
declare(strict_types=1);

namespace App\Controller\Api\Admin;

use App\Annotation\Validation;
use App\Context\Order\Repository\OrderRepository;
use App\Context\Order\Transformer\OrderTransformer;
use App\Context\Product\Entity\Product;
use App\Context\Product\Hydrator\ProductHydrator;
use App\Context\Product\Repository\ProductRepository;
use App\Context\Product\Transformer\ProductTransformer;
use App\Context\Product\Validator\SaveRequestValidator;
use App\Controller\Api\AbstractApiController;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use JsonException;
use League\Fractal\Resource\Collection;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Rest\Route("/orders")
 *
 * Class AlbumsController
 * @package App\Controller\Api\Admin\Discography
 */
final class OrdersController extends AbstractApiController
{
    /**
     * @Rest\Get("")
     *
     * @param OrderRepository $repository
     * @return View
     * @throws \Exception
     */
    public function all(OrderRepository $repository): View
    {
        $paginator = $repository->findAllForAdmin();

        $count = $paginator->count();
        $products = $paginator->getIterator()->getArrayCopy();

        $resource = new Collection($products, new OrderTransformer(), 'data');
        $resource->setMeta(['count' => $count]);

        return $this->makeView($resource);
    }

    /**
     * @Rest\Delete("/{id<\d+>}")
     *
     * @param int $id
     * @param OrderRepository $repository
     * @return View
     */
    public function delete(int $id, OrderRepository $repository): View
    {
        $repository->removeById($id);
        return $this->view([], Response::HTTP_OK);
    }
}
