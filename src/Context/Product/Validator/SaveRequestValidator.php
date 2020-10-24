<?php
declare(strict_types=1);

namespace App\Context\Product\Validator;

use App\Validator\ArrayValidatorAbstract;

/**
 * Class SaveRequestValidator
 * @package App\Api\Admin\Validator\Product
 */
final class SaveRequestValidator extends ArrayValidatorAbstract
{
    /**
     * @inheritDoc
     */
    protected function getLabels(): array
    {
        return [
            'name' => 'Название',
            'description' => 'Описание',
            'price' => 'Цена',
            'category.id' => 'ID Категории',
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getRules(): array
    {
        return [
            'required' => ['name', 'description', 'price', 'category.id'],
            'optional' => ['id'],
            'string' => [
                'name',
                'description',
            ],
            'integer' => ['type.id', 'id'],
            'numeric' => ['price'],
        ];
    }
}
