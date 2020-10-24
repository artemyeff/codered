<?php
declare(strict_types=1);

namespace App\Validator\FileManager\Folder;

use App\Validator\ArrayValidatorAbstract;

/**
 * Валидатор запроса создания папки
 *
 * Class MakeRequestValidator
 * @package App\Validator\FileManager\Folder
 */
class MakeRequestValidator extends ArrayValidatorAbstract
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
