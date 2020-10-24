<?php
declare(strict_types=1);

namespace App\Exceptions\FileManager\Folder;

use Symfony\Component\Filesystem\Exception\IOException;

/**
 * Ошибка при переименовании папки
 *
 * Class MakeFolderException
 * @package App\Exceptions\FileManager\Folder
 */
class RenameException extends IOException
{
    public const MESSAGE = 'Ошибка при переименовании папки';

    /**
     * MakeFolderException constructor.
     */
    public function __construct()
    {
        parent::__construct(self::MESSAGE, 422, null, null);
    }
}
