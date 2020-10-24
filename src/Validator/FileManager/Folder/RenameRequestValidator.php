<?php
declare(strict_types=1);

namespace App\Validator\FileManager\Folder;

use App\Validator\ArrayValidatorAbstract;

/**
 * Валидатор запроса на переименование папки
 *
 * Class RenameRequestValidator
 * @package App\Validator\FileManager\Folder
 */
class RenameRequestValidator extends ArrayValidatorAbstract
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
