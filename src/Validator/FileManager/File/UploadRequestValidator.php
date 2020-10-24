<?php
declare(strict_types=1);

namespace App\Validator\FileManager\File;

use App\Validator\ArrayValidatorAbstract;

/**
 * Валидатор запроса загрузки файлов
 *
 * Class UploadRequestValidator
 * @package App\Validator\FileManager\File
 */
class UploadRequestValidator extends ArrayValidatorAbstract
{
    /**
     * @inheritDoc
     */
    protected function getLabels(): array
    {
        return [
            'file' => 'Файл',
            'currentFolder.id' => 'ID текущей папки',
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getRules(): array
    {
        return [
            'required' => ['file'],
            'file' => [['file', 20]],
            'optional' => ['currentFolder.id'],
            'integer' => ['currentFolder.id'],
        ];
    }
}
