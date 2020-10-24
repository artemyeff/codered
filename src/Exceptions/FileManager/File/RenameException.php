<?php
declare(strict_types=1);

namespace App\Exceptions\FileManager\File;

use Symfony\Component\Filesystem\Exception\IOException;

/**
 * Ошибка при переименовании файла
 *
 * Class RenameException
 * @package App\Exceptions\FileManager\Folder
 */
class RenameException extends IOException
{
    public const MESSAGE = 'Ошибка при переименовании файла';

    /**
     * MakeFolderException constructor.
     */
    public function __construct()
    {
        parent::__construct(self::MESSAGE, 422, null, null);
    }
}
