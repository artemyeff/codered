<?php
declare(strict_types=1);

namespace App\Api\Transformer;

use DateTimeInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\Proxy;
use League\Fractal\Manager;
use League\Fractal\Resource\{Collection, Item, NullResource};
use League\Fractal\TransformerAbstract;

/**
 * Class AbstractTransformer
 * @package App\Api\Transformer
 */
abstract class AbstractTransformer extends TransformerAbstract
{
    protected ?Manager $manager;

    /**
     * AbstractTransformer constructor.
     * @param Manager|null $manager
     */
    public function __construct(?Manager $manager = null)
    {
        $this->manager = $manager;
    }

    /**
     * @param DateTimeInterface|null $date
     * @return string|null
     */
    protected function formatDate(?DateTimeInterface $date): ?string
    {
        return null === $date ? null : $date->format(DATE_ATOM);
    }

    /**
     * @param object|null $entity
     * @return object|null
     */
    protected function loadInclude(?object $entity): ?object
    {
        if ($entity instanceof Proxy) {
            try {
                $entity->__load();
            } catch (EntityNotFoundException $exception) {
                return null;
            }
        }

        return $entity;
    }

    /**
     * @param $data
     * @param $transformerClass
     * @param string|null $includes
     * @return array
     */
    protected function makeCollection(
        $data,
        $transformerClass,
        ?string $includes = null
    ): array {
        $transformer = is_string($transformerClass) ? new $transformerClass() : $transformerClass;
        $resource = new Collection($data, $transformer, '');
        if ($includes !== null) {
            $this->manager->parseIncludes($includes);
        }

        return $this->manager->createData($resource)->toArray();
    }

    /**
     * @param $data
     * @param $transformerClass
     * @param string|null $includes
     * @return array
     */
    protected function makeItem(
        $data,
        $transformerClass,
        ?string $includes = null
    ): ?array {
        if ($data === null) {
            return null;
        }
        $transformer = is_string($transformerClass) ? new $transformerClass() : $transformerClass;
        $resource = new Item($data, $transformer, '');
        if ($includes !== null) {
            $this->manager->parseIncludes($includes);
        }

        return $this->manager->createData($resource)->toArray();
    }

    /**
     * @param mixed $item
     * @param string $transformerClass
     * @return Item|NullResource
     */
    protected function itemOrNull($item, string $transformerClass)
    {
        if ($item === null) {
            return $this->null();
        }

        return $this->item($item, new $transformerClass(), '');
    }
}
