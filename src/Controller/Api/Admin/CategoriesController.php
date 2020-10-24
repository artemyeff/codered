<?php
declare(strict_types=1);

namespace App\Controller\Api\Admin;

use App\Annotation\Validation;
use App\Context\Category\Entity\Category;
use App\Context\Category\Hydrator\CategoryHydrator;
use App\Context\Category\Repository\CategoryRepository;
use App\Context\Category\Transformer\CategoryTransformer;
use App\Context\Category\Validator\SaveRequestValidator;
use App\Controller\Api\AbstractApiController;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use JsonException;
use League\Fractal\Resource\Collection;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Rest\Route("/categories")
 *
 * Class AlbumsController
 * @package App\Controller\Api\Admin\Discography
 */
final class CategoriesController extends AbstractApiController
{
    /**
     * @Rest\Get("")
     *
     * @param CategoryRepository $repository
     * @return View
     * @throws Exception
     */
    public function all(CategoryRepository $repository): View
    {
        $paginator = $repository->findAllForAdmin();

        $count = $paginator->count();
        $products = $paginator->getIterator()->getArrayCopy();

        $resource = new Collection($products, new CategoryTransformer(), 'data');
        $resource->setMeta(['count' => $count]);

        return $this->makeView($resource);
    }

    /**
     * @Rest\Get("/{id<\d+>}")
     *
     * @param int $id
     * @param CategoryRepository $repository
     * @return View
     * @throws NonUniqueResultException
     */
    public function show(int $id, CategoryRepository $repository): View
    {
        $album = $repository->findOne($id);

        return $this->makeItemView($album, CategoryTransformer::class);
    }

    /**
     * @Validation(SaveRequestValidator::class)
     * @Rest\Post("")
     *
     * @param CategoryRepository $repository
     * @param CategoryHydrator $hydrator
     * @return View
     * @throws JsonException
     */
    public function create(CategoryRepository $repository, CategoryHydrator $hydrator): View
    {
        $album = $hydrator->hydrate($this->getPayload());
        $albumSaved = $repository->save($album);

        return $this->makeItemView($albumSaved, CategoryTransformer::class);
    }

    /**
     * @Validation(SaveRequestValidator::class)
     * @Rest\Patch("/{id<\d+>}")
     *
     * @param Category $album
     * @param CategoryRepository $repository
     * @param CategoryHydrator $hydrator
     * @return View
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws JsonException
     */
    public function update(Category $album, CategoryRepository $repository, CategoryHydrator $hydrator): View
    {
        $albumHydrated = $hydrator->hydrate($this->getPayload(), $album);

        $albumSaved = $repository->save($albumHydrated);

        return $this->makeItemView($albumSaved, CategoryTransformer::class, 'type');
    }

    /**
     * @Rest\Delete("/{id<\d+>}")
     *
     * @param int $id
     * @param CategoryRepository     $repository
     * @return View
     */
    public function delete(int $id, CategoryRepository $repository): View
    {
        $repository->removeById($id);
        return $this->view([], Response::HTTP_OK);
    }
}
