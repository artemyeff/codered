<?php
declare(strict_types=1);

namespace App\Controller\Api\Admin;

use App\Api\Transformer\UserTransformer;
use App\Controller\Api\AbstractApiController;
use App\Repository\UserRepository;
use Exception;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use League\Fractal\Resource\Collection;

/**
 * @Rest\Route("/users")
 *
 * Class UsersController
 * @package App\Controller\Api\Admin
 */
class UsersController extends AbstractApiController
{
    /**
     * @Rest\Get("")
     * @param UserRepository $repository
     * @return View
     * @throws Exception
     */
    public function all(UserRepository $repository): View
    {
        $paginator = $repository->findAllForAdmin();

        $count = $paginator->count();
        $albums = $paginator->getIterator()->getArrayCopy();

        $resource = new Collection($albums, new UserTransformer(), 'data');
        $resource->setMeta(['count' => $count]);

        return $this->makeView($resource);
    }
}
