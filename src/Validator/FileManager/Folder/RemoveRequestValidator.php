<?php
declare(strict_types=1);

namespace App\Validator\FileManager\Folder;

use App\Validator\ArrayValidatorAbstract;

/**
 * Валидатор запроса удаления папки
 *
 * Class RemoveRequestValidator
 * @package App\Validator\FileManager\Folder
 */
class RemoveRequestValidator extends ArrayValidatorAbstract
{
    /**
     * @inheritDoc
     */
    protected function getLabels(): array
    {
        return [
            'currentFolder.id' => 'ID текущей папки',
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getRules(): array
    {
        return [
            'optional' => ['currentFolder.id'],
            'integer' => ['currentFolder.id'],
        ];
    }
}
