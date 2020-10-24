<?php
declare(strict_types=1);

namespace App\Validator\FileManager\File;

use App\Validator\ArrayValidatorAbstract;

/**
 * Валидатор запроса удаления файлов
 *
 * Class RemoveRequestValidator
 * @package App\Validator\FileManager\File
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
            'files.*.path' => 'Путь файла',
            'files.*.id' => 'ID файла',
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getRules(): array
    {
        return [
            'required' => [
                ['files.*.id'],
                ['files.*.path'],
            ],
            'optional' => ['currentFolder.id'],
            'integer' => [
                'files.*.id',
                'currentFolder.id',
            ],
            'array' => ['files'],
        ];
    }
}
