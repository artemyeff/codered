<?php
declare(strict_types=1);

namespace App\Exceptions\FileManager\Folder;

use Symfony\Component\Filesystem\Exception\IOException;

/**
 * Ошибка при удалении папки папки
 *
 * Class RemoveFolderException
 * @package App\Exceptions\FileManager\Folder
 */
class RemoveException extends IOException
{
    public const MESSAGE = 'Ошибка при удалении папки';

    /**
     * RemoveFolderException constructor.
     */
    public function __construct()
    {
        parent::__construct(self::MESSAGE, 422, null, null);
    }
}
