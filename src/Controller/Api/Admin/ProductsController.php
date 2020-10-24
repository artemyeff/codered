<?php
declare(strict_types=1);

namespace App\Controller\Api\Admin;

use App\Annotation\Validation;
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
 * @Rest\Route("/products")
 *
 * Class AlbumsController
 * @package App\Controller\Api\Admin\Discography
 */
final class ProductsController extends AbstractApiController
{
    /**
     * @Rest\Get("")
     *
     * @param ProductRepository $repository
     * @return View
     * @throws \Exception
     */
    public function all(ProductRepository $repository): View
    {
        $paginator = $repository->findAllForAdmin(['category']);

        $count = $paginator->count();
        $products = $paginator->getIterator()->getArrayCopy();

        $resource = new Collection($products, new ProductTransformer(), 'data');
        $resource->setMeta(['count' => $count]);

        return $this->makeView($resource);
    }

    /**
     * @Rest\Get("/{id<\d+>}")
     *
     * @param int $id
     * @param ProductRepository $repository
     * @return View
     * @throws NonUniqueResultException
     */
    public function show(int $id, ProductRepository $repository): View
    {
        $album = $repository->findOne($id, ['category']);

        return $this->makeItemView($album, ProductTransformer::class);
    }

    /**
     * @Validation(SaveRequestValidator::class)
     * @Rest\Post("")
     *
     * @param ProductRepository $repository
     * @param ProductHydrator $hydrator
     * @return View
     * @throws JsonException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(ProductRepository $repository, ProductHydrator $hydrator): View
    {
        $album = $hydrator->hydrate($this->getPayload());
        $albumSaved = $repository->save($album);

        return $this->makeItemView($albumSaved, ProductTransformer::class, 'category');
    }

    /**
     * @Validation(SaveRequestValidator::class)
     * @Rest\Patch("/{id<\d+>}")
     *
     * @param Product $product
     * @param ProductRepository $repository
     * @param ProductHydrator $hydrator
     * @return View
     * @throws JsonException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(Product $product, ProductRepository $repository, ProductHydrator $hydrator): View
    {
        $albumHydrated = $hydrator->hydrate($this->getPayload(), $product);

        $albumSaved = $repository->save($albumHydrated);

        return $this->makeItemView($albumSaved, ProductTransformer::class, 'category');
    }

    /**
     * @Rest\Delete("/{id<\d+>}")
     *
     * @param int $id
     * @param ProductRepository $repository
     * @return View
     */
    public function delete(int $id, ProductRepository $repository): View
    {
        $repository->removeById($id);
        return $this->view([], Response::HTTP_OK);
    }
}
