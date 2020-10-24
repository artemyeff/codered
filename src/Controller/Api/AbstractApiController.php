<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Api\Transformer\AbstractTransformer;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use JsonException;
use League\Fractal\Manager;
use League\Fractal\Resource\{Collection, Item, ResourceAbstract};
use Symfony\Component\HttpFoundation\{Request, Response};

/**
 * Class AbstractApiController
 * @package App\Controller\Api
 */
abstract class AbstractApiController extends AbstractFOSRestController
{
    protected Manager $manager;

    /**
     * AbstractApiController constructor.
     * @param Manager $manager
     * @param string|null $entityClass
     */
    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return array|null
     * @throws JsonException
     */
    protected function getPayload(): ?array
    {
        /** @var Request $request */
        $request = $this->container->get('request_stack')->getCurrentRequest();
        if ($request === null) {
            return null;
        }

        return json_decode($request->getContent() ?? '', true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @param $data
     * @param string|AbstractTransformer $transformerClass
     * @param string|null $includes
     * @param int $status
     * @return View
     */
    protected function makeItemView(
        $data,
        $transformerClass,
        ?string $includes = null,
        int $status = Response::HTTP_OK
    ): View {
        $transformer = is_string($transformerClass) ? new $transformerClass() : $transformerClass;
        $resource = new Item($data, $transformer, 'data');

        if ($includes !== null) {
            $this->manager->parseIncludes($includes);
        }

        return $this->makeView($resource, $status);
    }

    /**
     * @param $data
     * @param string|AbstractTransformer $transformerClass
     * @param string|null $includes
     * @param int $status
     * @return View
     */
    protected function makeCollectionView(
        $data,
        $transformerClass,
        ?string $includes = null,
        int $status = Response::HTTP_OK
    ): View {
        $transformer = is_string($transformerClass) ? new $transformerClass() : $transformerClass;
        $resource = new Collection($data, $transformer, 'data');
        if ($includes !== null) {
            $this->manager->parseIncludes($includes);
        }

        return $this->makeView($resource, $status);
    }

    /**
     * @param ResourceAbstract $resource
     * @param int $status
     * @return View
     */
    protected function makeView(ResourceAbstract $resource, int $status = Response::HTTP_OK): View
    {
        return $this->view($this->manager->createData($resource)->toArray(), $status);
    }
}
