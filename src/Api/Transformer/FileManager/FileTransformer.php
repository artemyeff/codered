<?php
declare(strict_types=1);

namespace App\Api\Transformer\FileManager;

use App\Api\Transformer\AbstractTransformer;
use App\Entity\FileSystem\File;

/**
 * Class FileTransformer
 * @package App\Api\Transformer\FileManager
 */
final class FileTransformer extends AbstractTransformer
{
    /**
     * @param File $file
     * @return array
     */
    public function transform(File $file): array
    {
        return [
            'id' => $file->getId(),
            'name' => $file->getName(),
            'path' => $file->getPath(),
            'extension' => $file->getExtension(),
        ];
    }
}
