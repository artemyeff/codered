<?php
declare(strict_types=1);

namespace App\Context\Category\Validator;

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
            'name' => 'Название'
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getRules(): array
    {
        return [
            'required' => ['name'],
            'optional' => ['id'],
            'string' => [
                'name',
            ],
            'integer' => ['id'],
        ];
    }
}
