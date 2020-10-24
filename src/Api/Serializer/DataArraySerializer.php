<?php
declare(strict_types=1);

namespace App\Api\Serializer;

use League\Fractal\Serializer\DataArraySerializer as BaseDataArraySerializer;

/**
 * Class DataArraySerializer
 * @package App\Api\Serializer
 */
final class DataArraySerializer extends BaseDataArraySerializer
{
    /**
     * @inheritDoc
     */
    public function collection($resourceKey, array $data): array
    {
        if ($resourceKey === '') {
            return $data;
        }

        $resourceKey = $resourceKey ?? 'data';

        return [$resourceKey => $data];
    }

    /**
     * @inheritDoc
     */
    public function item($resourceKey, array $data): array
    {
        if ($resourceKey === '') {
            return $data;
        }

        $resourceKey = $resourceKey ?? 'data';

        return [$resourceKey => $data];
    }

    /**
     * @inheritDoc
     */
    public function null(): ?array
    {
        return null;
    }
}
