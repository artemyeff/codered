<?php
declare(strict_types=1);

namespace App\Validator\Api;

use App\Validator\ArrayValidatorAbstract;

/**
 * Class PaginationRequestValidator
 * @package App\Validator\Api
 */
class PaginationRequestValidator extends ArrayValidatorAbstract
{
    /**
     * @inheritDoc
     */
    protected function getLabels(): array
    {
        return [
            'page' => 'Номер страницы',
            'size' => 'Размер страницы',
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getRules(): array
    {
        return [
            'integer' => ['page', 'size'],
        ];
    }
}
