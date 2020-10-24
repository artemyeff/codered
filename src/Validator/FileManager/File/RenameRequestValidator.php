<?php
declare(strict_types=1);

namespace App\Validator\FileManager\File;

use App\Validator\ArrayValidatorAbstract;

/**
 * Валидатор запроса загрузки файлов
 *
 * Class RenameRequestValidator
 * @package App\Validator\FileManager\File
 */
class RenameRequestValidator extends ArrayValidatorAbstract
{
    /**
     * @inheritDoc
     */
    protected function getLabels(): array
    {
        return [
            'name' => 'Название',
            'currentFolder.id' => 'ID текущей папки',
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getRules(): array
    {
        return [
            'required' => ['name'],
            'optional' => ['currentFolder.id'],
            'integer' => ['currentFolder.id'],
            'string' => ['name'],
        ];
    }
}
